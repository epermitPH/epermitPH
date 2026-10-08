<?php
require __DIR__ . "/includes/bootstrap.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "applicant";
    $profession = $_POST["profession"] ?? "";
    $licenseNo = trim($_POST["license_no"] ?? "");

    if (!$name) {
        $error = "Full name is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!in_array($role, ["applicant", "professional"], true)) {
        $error = "Invalid account type.";
    } else {
        $allowedProfessions = ["architect","civil_structural","electrical","mechanical","pece","plumbing_sanitary","other"];

        if ($role === "professional" && !in_array($profession, $allowedProfessions, true)) {
            $error = "Choose a valid professional classification.";
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare(
                    "INSERT INTO users (name,email,password_hash,role)
                     VALUES (?,?,?,?)"
                );
                $stmt->execute([
                    $name,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $role
                ]);
                $uid = (int)$pdo->lastInsertId();

                if ($role === "professional") {
                    $stmt = $pdo->prepare(
                        "INSERT INTO professional_profiles
                         (user_id,profession,license_no,verification_status)
                         VALUES (?,?,?,'pending')"
                    );
                    $stmt->execute([$uid,$profession,$licenseNo]);
                }

                audit($pdo,$uid,null,"Created ".$role." account","user",$uid);
                $pdo->commit();

                header("Location: ".url("login.php?registered=1"));
                exit;
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $error = "Could not create the account. The email may already be registered.";
            }
        }
    }
}

$pageTitle = "Register";
require __DIR__ . "/includes/header.php";
?>
<section class="auth-layout">
    <div class="auth-visual">
        <div class="auth-visual-inner">
            <div class="auth-logo">
                <span class="brand-mark"><span></span><span></span><span></span></span>
                <span class="brand-copy"><strong>ePermit</strong><small>Verify. Approve. Build.</small></span>
            </div>
            <h2>Create your ePermit account</h2>
            <p>Applicants can track individual technical reviews while licensed professionals can manage assigned project documents and corrections.</p>
            <div class="feature-list">
                <div class="feature-item"><span class="feature-icon">1</span> Apply and track permit requirements</div>
                <div class="feature-item"><span class="feature-icon">2</span> Link verified licensed professionals</div>
                <div class="feature-item"><span class="feature-icon">3</span> Respond to review corrections</div>
                <div class="feature-item"><span class="feature-icon">4</span> Preserve document version history</div>
            </div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card">
            <div class="kicker">Account Registration</div>
            <h2>Create an Account</h2>
            <p class="muted">Choose the account type that matches your role.</p>
            <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

                <label>Full Name</label>
                <input name="name" placeholder="Enter your full name" required>

                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required>

                <label>Password</label>
                <input type="password" name="password" minlength="8" placeholder="At least 8 characters" required>

                <label>Account Type</label>
                <select name="role" id="role" onchange="toggleProfessional()" required>
                    <option value="applicant">Applicant / Building Owner</option>
                    <option value="professional">Licensed Professional</option>
                </select>

                <div id="professionalFields" style="display:none">
                    <label>Professional Classification</label>
                    <select name="profession">
                        <option value="architect">Architect</option>
                        <option value="civil_structural">Civil / Structural Engineer</option>
                        <option value="electrical">Electrical Engineer</option>
                        <option value="mechanical">Mechanical Engineer</option>
                        <option value="pece">Professional Electronics Engineer (PECE)</option>
                        <option value="plumbing_sanitary">Plumbing / Sanitary Professional</option>
                        <option value="other">Other Authorized Professional</option>
                    </select>

                    <label>License / Registration Number</label>
                    <input name="license_no" placeholder="Enter license or registration number">
                    <p class="muted">Professional accounts remain pending until verified by an authorized administrator.</p>
                </div>

                <p><button class="btn btn-primary">Create Account</button></p>
            </form>
            <p class="auth-switch">Already have an account? <a href="<?= e(url("login.php")) ?>"><strong>Sign in</strong></a></p>
        </div>
    </div>
</section>

<script>
function toggleProfessional() {
    const isProfessional = document.getElementById("role").value === "professional";
    document.getElementById("professionalFields").style.display = isProfessional ? "block" : "none";
}
toggleProfessional();
</script>
<?php require __DIR__ . "/includes/footer.php"; ?>
