<?php
require __DIR__ . "/includes/bootstrap.php";

$token = trim($_GET["token"] ?? $_POST["token"] ?? "");
$permit = null;
$disciplines = [];

if ($token) {
    $stmt = $pdo->prepare(
        "SELECT pe.permit_no,pe.status,pe.issued_at,
                a.id application_id,a.application_no,
                p.project_name,p.project_location
         FROM permits pe
         JOIN permit_applications a ON a.id=pe.application_id
         JOIN projects p ON p.id=a.project_id
         WHERE pe.verification_token=?"
    );
    $stmt->execute([$token]);
    $permit = $stmt->fetch();

    if ($permit) {
        $stmt = $pdo->prepare(
            "SELECT di.name,ad.status
             FROM application_disciplines ad
             JOIN disciplines di ON di.id=ad.discipline_id
             WHERE ad.application_id=?
             ORDER BY di.sort_order"
        );
        $stmt->execute([$permit["application_id"]]);
        $disciplines = $stmt->fetchAll();
    }
}

$pageTitle = "Permit Verification";
require __DIR__ . "/includes/header.php";
?>
<div class="card form-card">
    <div class="kicker">Official Record Check</div>
    <h2>Verify ePermit</h2>

    <form method="get">
        <label>Verification Token</label>
        <input name="token" value="<?= e($token) ?>" required>
        <p><button class="btn btn-primary">Verify Permit</button></p>
    </form>

    <?php if ($token && !$permit): ?>
        <div class="alert error">No permit record matches this verification token.</div>
    <?php endif; ?>

    <?php if ($permit): ?>
        <div class="alert"><strong>VERIFIED ePERMIT RECORD</strong></div>
        <p><strong>Permit No.:</strong> <?= e($permit["permit_no"]) ?></p>
        <p><strong>Application:</strong> <?= e($permit["application_no"]) ?></p>
        <p><strong>Project:</strong> <?= e($permit["project_name"]) ?></p>
        <p><strong>Project Location:</strong> <?= e($permit["project_location"]) ?></p>
        <p><strong>Permit Status:</strong> <span class="badge status-<?= e($permit["status"]) ?>"><?= e(status_label($permit["status"])) ?></span></p>
        <p><strong>Issued:</strong> <?= e($permit["issued_at"]) ?></p>

        <h3>Technical Review Summary</h3>
        <?php foreach ($disciplines as $d): ?>
            <div class="progress-row">
                <strong><?= e($d["name"]) ?></strong>
                <span class="badge status-<?= e($d["status"]) ?>"><?= e(status_label($d["status"])) ?></span>
                <span></span>
            </div>
        <?php endforeach; ?>

        <p class="muted">
            Public verification intentionally shows limited information. Authorized inspectors and LGU users see additional records after login.
        </p>
    <?php endif; ?>
</div>
<?php require __DIR__ . "/includes/footer.php"; ?>
