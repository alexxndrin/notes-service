<?php
require 'config.php';
requireLogin();
$userId = $_SESSION['user_id'];
$noteId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
$stmt->execute([$noteId, $userId]);
header('Location: index.php');
exit;