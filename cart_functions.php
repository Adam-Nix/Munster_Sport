<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart_session_id'])) {
    $_SESSION['cart_session_id'] = session_id();
}

function add_to_cart($product_id, $quantity = 1) {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    $product_id = sanitize_input($product_id);
    $quantity = (int)$quantity;
    
    // Validate product exists and has stock
    $product_query = "SELECT * FROM products WHERE id = '$product_id' AND stock > 0";
    $product_result = mysqli_query($conn, $product_query);
    
    if (mysqli_num_rows($product_result) == 0) {
        return array('success' => false, 'message' => 'Product not found or out of stock');
    }
    
    $product = mysqli_fetch_assoc($product_result);
    
    // Check if item already exists in cart
    $check_query = "SELECT * FROM cart_items WHERE session_id = '$session_id' AND product_id = '$product_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update quantity
        $existing_item = mysqli_fetch_assoc($check_result);
        $new_quantity = $existing_item['quantity'] + $quantity;
        
        // Check stock availability
        if ($new_quantity > $product['stock']) {
            return array('success' => false, 'message' => 'Not enough stock available. Only ' . $product['stock'] . ' items in stock.');
        }
        
        $update_query = "UPDATE cart_items SET quantity = '$new_quantity' WHERE session_id = '$session_id' AND product_id = '$product_id'";
        if (mysqli_query($conn, $update_query)) {
            return array('success' => true, 'message' => 'Cart updated successfully');
        }
    } else {
        // Check stock availability
        if ($quantity > $product['stock']) {
            return array('success' => false, 'message' => 'Not enough stock available. Only ' . $product['stock'] . ' items in stock.');
        }
        
        // Add new item
        $price = $product['price'];
        $insert_query = "INSERT INTO cart_items (session_id, product_id, quantity, price) VALUES ('$session_id', '$product_id', '$quantity', '$price')";
        if (mysqli_query($conn, $insert_query)) {
            return array('success' => true, 'message' => 'Item added to cart successfully');
        }
    }
    
    return array('success' => false, 'message' => 'Failed to add item to cart');
}

function remove_from_cart($product_id) {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    $product_id = sanitize_input($product_id);
    
    $delete_query = "DELETE FROM cart_items WHERE session_id = '$session_id' AND product_id = '$product_id'";
    if (mysqli_query($conn, $delete_query)) {
        return array('success' => true, 'message' => 'Item removed from cart');
    }
    
    return array('success' => false, 'message' => 'Failed to remove item from cart');
}

function update_cart_quantity($product_id, $quantity) {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    $product_id = sanitize_input($product_id);
    $quantity = (int)$quantity;
    
    if ($quantity <= 0) {
        return remove_from_cart($product_id);
    }
    
    // Check stock availability
    $product_query = "SELECT stock FROM products WHERE id = '$product_id'";
    $product_result = mysqli_query($conn, $product_query);
    
    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        if ($quantity > $product['stock']) {
            return array('success' => false, 'message' => 'Not enough stock available. Only ' . $product['stock'] . ' items in stock.');
        }
    }
    
    $update_query = "UPDATE cart_items SET quantity = '$quantity' WHERE session_id = '$session_id' AND product_id = '$product_id'";
    if (mysqli_query($conn, $update_query)) {
        return array('success' => true, 'message' => 'Cart updated successfully');
    }
    
    return array('success' => false, 'message' => 'Failed to update cart');
}

function get_cart_items() {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    
    $query = "SELECT ci.*, p.name, p.description, p.image, p.stock 
              FROM cart_items ci 
              JOIN products p ON ci.product_id = p.id 
              WHERE ci.session_id = '$session_id' 
              ORDER BY ci.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $items = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    
    return $items;
}

function get_cart_total() {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    
    $query = "SELECT SUM(ci.quantity * ci.price) as total 
              FROM cart_items ci 
              WHERE ci.session_id = '$session_id'";
    
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row['total'] ? $row['total'] : 0;
}

function get_cart_count() {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    
    $query = "SELECT SUM(quantity) as count FROM cart_items WHERE session_id = '$session_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    return $row['count'] ? $row['count'] : 0;
}

function clear_cart() {
    global $conn;
    
    $session_id = $_SESSION['cart_session_id'];
    
    $delete_query = "DELETE FROM cart_items WHERE session_id = '$session_id'";
    return mysqli_query($conn, $delete_query);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = array();
    
    switch ($_POST['action']) {
        case 'add_to_cart':
            $product_id = $_POST['product_id'] ?? '';
            $quantity = $_POST['quantity'] ?? 1;
            $response = add_to_cart($product_id, $quantity);
            break;
            
        case 'remove_from_cart':
            $product_id = $_POST['product_id'] ?? '';
            $response = remove_from_cart($product_id);
            break;
            
        case 'update_quantity':
            $product_id = $_POST['product_id'] ?? '';
            $quantity = $_POST['quantity'] ?? 0;
            $response = update_cart_quantity($product_id, $quantity);
            break;
            
        case 'get_cart_count':
            $response = array('success' => true, 'count' => get_cart_count());
            break;
            
        default:
            $response = array('success' => false, 'message' => 'Invalid action');
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>