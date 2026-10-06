<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1>➕ Add Product</h1>
        <form method="post" action="<?= site_url('products/create') ?>">
            <label>Product Name</label>
            <input type="text" name="product_name" required>
            <label>Description</label>
            <textarea name="description"></textarea>
            <label>Price</label>
            <input type="number" step="0.01" name="price" required>
            <label>Quantity</label>
            <input type="number" name="quantity" required>
            <button type="submit">Save Product</button>
        </form>

        <a href="<?= site_url('products') ?>" class="btn-back">⬅ Back to Products</a>
    </div>
</body>
</html>