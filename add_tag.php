<?php
require 'config.php';
requireLogin();
$userId = $_SESSION['user_id'];
$noteId = (int)($_GET['note_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
<head><meta charset="UTF-8"><title>Добавить тег</title><style>body{font-family:sans-serif;max-width:400px;margin:2rem auto;}input,button{width:100%;padding:0.5rem;margin:0.5rem 0;}</style></head>
<body>
<h2>Добавить тег к заметке</h2>
<form method="post">
    <input type="text" name="tag" placeholder="Название тега" required>
    <button type="submit">Добавить</button>
</form>
<p><a href="index.php">← Назад</a></p>
</body>
</html>