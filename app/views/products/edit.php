<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1>✏️ Edit Product</h1>
        <form method="post" action="<?= site_url('products/edit/' . $product['id']) ?>">
            <label>Product Name</label>
            <input type="text" name="product_name" value="<?= html_escape($product['product_name']) ?>" required>
            <label>Description</label>
            <textarea name="description"><?= html_escape($product['description']) ?></textarea>
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?= html_escape($product['price']) ?>" required>
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= html_escape($product['quantity']) ?>" required>
            <button type="submit">Update Product</button>
        </form>

        <a href="<?= site_url('products') ?>" class="btn-back">⬅ Back to Products</a>
    </div>
</body>
</html>