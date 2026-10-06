<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1>🔐 Login</h1>
<form method="post" action="<?= site_url('auth/login') ?>">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">LogIn</button>
</form>
<p>No account? <a href="<?= site_url('auth/register') ?>">Register</a></p>
    </div>
</body>
</html>