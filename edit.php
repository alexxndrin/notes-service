<?php
require 'config.php';
requireLogin();
$userId = $_SESSION['user_id'];
$noteId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM notes WHERE id = ? AND user_id = ?');
$stmt->execute([$noteId, $userId]);
$note = $stmt->fetch();
if (!$note) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $stmt = $pdo->prepare('UPDATE notes SET title = ?, body = ? WHERE id = ? AND user_id = ?');
    $stmt->execute([$title, $body, $noteId, $userId]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Редактировать</title><style>body{font-family:sans-serif;max-width:600px;margin:2rem auto;}input,textarea,button{width:100%;margin:0.5rem 0;padding:0.5rem;}</style></head>
<body>
<h2>Редактирование заметки</h2>
<form method="post">
    <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>">
    <textarea name="body" rows="8"><?= htmlspecialchars($note['body']) ?></textarea>
    <button type="submit">Обновить</button>
</form>
<p><a href="index.php">← Назад</a></p>
</body>
</html>