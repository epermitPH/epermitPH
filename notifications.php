<?php
require __DIR__ . "/includes/bootstrap.php";
require_login();

$stmt = $pdo->prepare(
    "SELECT * FROM notifications
     WHERE user_id=?
     ORDER BY created_at DESC
     LIMIT 100"
);
$stmt->execute([user_id()]);
$items = $stmt->fetchAll();

$pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?")->execute([user_id()]);

$pageTitle = "Notifications";
require __DIR__ . "/includes/header.php";
?>
<div class="section-title">
    <div><div class="kicker">Updates</div><h2>Notifications</h2></div>
</div>

<?php if (!$items): ?>
    <div class="card">No notifications yet.</div>
<?php endif; ?>

<?php foreach ($items as $item): ?>
    <div class="card" style="margin-bottom:12px">
        <strong><?= e($item["title"]) ?></strong>
        <p><?= e($item["message"]) ?></p>
        <small class="muted"><?= e($item["created_at"]) ?></small>
    </div>
<?php endforeach; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
