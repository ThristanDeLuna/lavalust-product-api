

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1>📝 Register</h1>
<form method="post" action="<?= site_url('auth/register') ?>">
    <input type="text" name="username" placeholder="Choose a username" required>
    <input type="password" name="password" placeholder="Choose a password" required>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="<?= site_url('auth/login') ?>">Login</a></p>
    </div>
</body>
</html>