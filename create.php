<?php
require 'config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO notes (user_id, title, body) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $title, $body]);
    $noteId = $pdo->lastInsertId();

    $tagName = trim($_POST['tag'] ?? '');
    if ($tagName !== '') {
        $stmt = $pdo->prepare('INSERT IGNORE INTO tags (user_id, name) VALUES (?, ?)');
        $stmt->execute([$userId, $tagName]);
        $stmt = $pdo->prepare('SELECT id FROM tags WHERE user_id = ? AND name = ?');
        $stmt->execute([$userId, $tagName]);
        $tag = $stmt->fetch();
        if ($tag) {
            $stmt = $pdo->prepare('INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)');
            $stmt->execute([$noteId, $tag['id']]);
        }
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Новая заметка</title><style>body{font-family:sans-serif;max-width:600px;margin:2rem auto;}input,textarea,button{width:100%;margin:0.5rem 0;padding:0.5rem;}</style></head>
<body>
<h2>Новая заметка</h2>
<form method="post">
    <input type="text" name="title" placeholder="Заголовок (необязательно)">
    <textarea name="body" rows="8" placeholder="Текст заметки"></textarea>
    <input type="text" name="tag" placeholder="Тег (один, опционально)">
    <button type="submit">Сохранить</button>
</form>
<p><a href="index.php">← Назад</a></p>
</body>
</html>