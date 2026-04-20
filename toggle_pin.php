<?php
require 'config.php';
requireLogin();
$userId = $_SESSION['user_id'];
$noteId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('UPDATE notes SET is_pinned = NOT is_pinned WHERE id = ? AND user_id = ?');
$stmt->execute([$noteId, $userId]);
header('Location: index.php');
exit;