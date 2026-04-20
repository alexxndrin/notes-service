<?php
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        try {
            $stmt->execute([$name, $email, $hash]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) $error = 'Email уже занят';
            else $error = 'Ошибка регистрации';
        }
    } else $error = 'Заполните все поля';
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Регистрация</title><style>body{font-family:sans-serif;max-width:500px;margin:2rem auto;padding:1rem;}input,button{display:block;width:100%;margin:0.5rem 0;padding:0.5rem;}</style></head>
<body>
<h2>Регистрация</h2>
<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="post">
    <input type="text" name="name" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <button type="submit">Зарегистрироваться</button>
</form>
<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>