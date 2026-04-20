<?php
require 'config.php';
requireLogin();

$userId = $_SESSION['user_id'];

function getNotesByUser(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare(
        'SELECT n.*, GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ",") AS tags
         FROM notes n
         LEFT JOIN note_tags nt ON nt.note_id = n.id
         LEFT JOIN tags t ON t.id = nt.tag_id
         WHERE n.user_id = :uid
         GROUP BY n.id
         ORDER BY n.is_pinned DESC, n.updated_at DESC'
    );
    $stmt->execute([':uid' => $userId]);
    return $stmt->fetchAll();
}

$notes = getNotesByUser($pdo, $userId);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Мои заметки</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 2rem auto; padding: 1rem; }
        .note { border: 1px solid #ccc; margin: 1rem 0; padding: 0.5rem 1rem; border-radius: 8px; }
        .pinned { background: #fff9c4; border-left: 5px solid #f1c40f; }
        .note h3 { margin: 0.2rem 0; }
        .tags { font-size: 0.8rem; color: #555; }
        .actions a { margin-right: 0.8rem; }
        .btn { display: inline-block; background: #3498db; color: white; padding: 0.3rem 0.8rem; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #e73c5e; }
        hr { margin: 1rem 0; }
    </style>
</head>
<body>
<h1>Привет, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
<p><a href="create.php" class="btn">Новая заметка</a> | <a href="logout.php">Выйти</a></p>
<hr>

<?php if (empty($notes)): ?>
    <p>У вас пока нет заметок. Создайте первую!</p>
<?php else: ?>
    <?php foreach ($notes as $note): ?>
        <div class="note <?= $note['is_pinned'] ? 'pinned' : '' ?>">
            <h3><?= htmlspecialchars($note['title'] ?: 'Без заголовка') ?></h3>
            <p><?= nl2br(htmlspecialchars($note['body'])) ?></p>
            <div class="tags">
                Теги: 
                <?php if ($note['tags']): 
                    $tags = explode(',', $note['tags']);
                    foreach ($tags as $tag) echo "<span style='background:#eee;padding:2px 6px;margin:2px;border-radius:12px;'>" . htmlspecialchars($tag) . "</span> ";
                else: echo "—"; endif; ?>
           </div>
            <div class="actions">
                <a href="edit.php?id=<?= $note['id'] ?>">Редактировать</a>
                <a href="toggle_pin.php?id=<?= $note['id'] ?>"><?= $note['is_pinned'] ? 'Открепить' : 'Закрепить' ?></a>
                <a href="add_tag.php?note_id=<?= $note['id'] ?>">Добавить тег</a>
                <a href="delete.php?id=<?= $note['id'] ?>" onclick="return confirm('Удалить заметку?')" style="color:red;">Удалить</a>
            </div>
            <small>Создано: <?= $note['created_at'] ?> | Обновлено: <?= $note['updated_at'] ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>