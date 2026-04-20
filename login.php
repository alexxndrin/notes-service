<?php
require 'config.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: index.php');
        exit;
    } else $error = 'Неверный email или пароль';
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Вход</title><style>body{font-family:sans-serif;max-width:500px;margin:2rem auto;padding:1rem;}input,button{display:block;width:100%;margin:0.5rem 0;padding:0.5rem;}</style></head>
<body>
<h2>Вход</h2>
<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Войти</button>
</form>
<p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</body>
</html>