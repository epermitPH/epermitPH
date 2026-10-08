<?php
require __DIR__ . "/includes/bootstrap.php";
require_login();

$permitId = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare(
    "SELECT pe.*,a.application_no,p.project_name,p.project_location,p.owner_user_id,u.name owner_name
     FROM permits pe
     JOIN permit_applications a ON a.id=pe.application_id
     JOIN projects p ON p.id=a.project_id
     JOIN users u ON u.id=p.owner_user_id
     WHERE pe.id=?"
);
$stmt->execute([$permitId]);
$permit = $stmt->fetch();

if (!$permit || !user_can_access_application($pdo,(int)$permit["application_id"],user_id(),user_role())) {
    http_response_code(404);
    exit("Permit record not found.");
}

$approved = $pdo->prepare(
    "SELECT di.name discipline_name,
            doc.title,dv.version_number,dv.file_path,
            pu.name professional_name,pp.profession,pp.license_no
     FROM application_disciplines ad
     JOIN disciplines di ON di.id=ad.discipline_id
     JOIN document_versions dv ON dv.id=ad.approved_version_id
     JOIN documents doc ON doc.id=dv.document_id
     LEFT JOIN application_professionals ap ON ap.application_discipline_id=ad.id
     LEFT JOIN users pu ON pu.id=ap.professional_user_id
     LEFT JOIN professional_profiles pp ON pp.user_id=ap.professional_user_id
     WHERE ad.application_id=? AND ad.status='approved'
     ORDER BY di.sort_order"
);
$approved->execute([$permit["application_id"]]);
$approvedPlans = $approved->fetchAll();

$verificationUrl = absolute_url("verify.php?token=".urlencode($permit["verification_token"]));

$pageTitle = "Digital Permit";
require __DIR__ . "/includes/header.php";
?>
<div class="card">
    <div class="kicker">Digital Permit Record</div>
    <h2><?= e($permit["permit_no"]) ?></h2>
    <p><strong>Application:</strong> <?= e($permit["application_no"]) ?></p>
    <p><strong>Project:</strong> <?= e($permit["project_name"]) ?></p>
    <p><strong>Owner:</strong> <?= e($permit["owner_name"]) ?></p>
    <p><strong>Location:</strong> <?= e($permit["project_location"]) ?></p>
    <p><strong>Status:</strong> <span class="badge status-<?= e($permit["status"]) ?>"><?= e(status_label($permit["status"])) ?></span></p>
    <p><strong>Issued:</strong> <?= e($permit["issued_at"]) ?></p>

    <div class="grid-2">
        <div>
            <h3>Verification Link</h3>
            <div class="code-box"><?= e($verificationUrl) ?></div>
            <p class="muted">The QR code points to the official permit verification record.</p>
        </div>
        <div>
            <h3>QR Verification</h3>
            <div id="qrcode"></div>
        </div>
    </div>
</div>

<div class="section-title"><h3>Approved Plan of Record</h3></div>
<table>
    <tr><th>Discipline</th><th>Approved Document</th><th>Professional</th></tr>
    <?php foreach ($approvedPlans as $p): ?>
        <tr>
            <td><?= e($p["discipline_name"]) ?></td>
            <td>
                <?= e($p["title"]) ?> · Version <?= (int)$p["version_number"] ?><br>
                <a target="_blank" href="<?= e(url($p["file_path"])) ?>">Open approved version</a>
            </td>
            <td>
                <?= e($p["professional_name"] ?? "Not assigned") ?>
                <?php if ($p["profession"]): ?>
                    <br><small class="muted"><?= e(profession_label($p["profession"])) ?> · <?= e($p["license_no"]) ?></small>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById("qrcode"), {
    text: <?= json_encode($verificationUrl) ?>,
    width: 180,
    height: 180
});
</script>
<?php require __DIR__ . "/includes/footer.php"; ?>
