# Image Upload Instructions

## How to Add Product Images

1. **When Adding a Product:**
   - Go to Seller Dashboard
   - Click "Add Product"
   - Fill in product details
   - Click "Choose File" under "Product Image"
   - Select a JPG, PNG, or GIF image
   - Click "Add Product"

2. **Supported Image Formats:**
   - JPG/JPEG
   - PNG
   - GIF

3. **Image Storage:**
   - Images are stored in the `uploads/` folder
   - Each image gets a unique filename to prevent conflicts

4. **Viewing Images:**
   - Product images appear automatically in the shop
   - If no image is uploaded, a default icon (📦) is shown

## Folder Structure

```
Progect_2025/
└── uploads/          ← Product images are saved here
    ├── product_1234567890_1234.jpg
    ├── product_1234567891_5678.png
    └── ...
```

## Troubleshooting

**Images not uploading?**
- Make sure the `uploads` folder exists
- Check folder permissions (should be writable)
- Verify file size is reasonable (PHP default max: 2MB)

**Images not displaying?**
- Check that the image file exists in `uploads/` folder
- Verify the image path in the database
- Try refreshing the page

## Testing

1. Login as seller (seller1 / seller123)
2. Add a product with an image
3. Login as customer (customer1 / customer123)
4. View the product in the shop
5. The image should display properly
