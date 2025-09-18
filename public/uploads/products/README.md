# Product Images Upload Directory

This directory stores uploaded product images.

## Directory Structure
```
public/uploads/products/
├── README.md (this file)
└── [uploaded images will appear here]
```

## Image Requirements
- **Formats**: JPEG, PNG, JPG, GIF
- **Max Size**: 2MB
- **Recommended Dimensions**: 800x800 pixels
- **Naming**: Images are automatically renamed with timestamp prefix

## Default Image
If no image is uploaded, the system uses `default.jpg` from the public/images directory.

## Security
- Only authenticated admin users can upload images
- File types are validated on both client and server side
- File size is limited to prevent abuse
