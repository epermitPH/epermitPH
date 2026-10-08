<?php
require __DIR__ . "/includes/bootstrap.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf();

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT id,name,email,password_hash,role,is_active
         FROM users WHERE email=?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && (int)$user["is_active"] === 1 && password_verify($password,$user["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["user"] = [
            "id" => (int)$user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "role" => $user["role"]
        ];
        audit($pdo,(int)$user["id"],null,"Logged in","user",(int)$user["id"]);
        redirect_dashboard();
    }

    $error = "Invalid email or password.";
}

$pageTitle = "Login";
require __DIR__ . "/includes/header.php";
?>
<section class="auth-layout">
    <div class="auth-visual">
        <div class="auth-visual-inner">
            <div class="auth-logo">
                <span class="brand-mark"><span></span><span></span><span></span></span>
                <span class="brand-copy"><strong>ePermit</strong><small>Verify. Approve. Build.</small></span>
            </div>
            <h2>Digital Building Permit Workflow and Verification</h2>
            <p>A unified permit workflow for applicants, professionals, LGU reviewers, building officials, and field inspectors.</p>
            <div class="feature-list">
                <div class="feature-item"><span class="feature-icon">▣</span> Online Application & Discipline Tracking</div>
                <div class="feature-item"><span class="feature-icon">✓</span> Multidisciplinary Technical Review</div>
                <div class="feature-item"><span class="feature-icon">◎</span> Professional & PECE Verification</div>
                <div class="feature-item"><span class="feature-icon">↻</span> Document Version Control</div>
                <div class="feature-item"><span class="feature-icon">⌗</span> QR Permit Verification</div>
                <div class="feature-item"><span class="feature-icon">⌕</span> Field Inspection Continuity</div>
            </div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card">
            <div class="kicker">Secure Access</div>
            <h2>Welcome Back</h2>
            <p class="muted">Sign in to your ePermit account.</p>

            <?php if (isset($_GET["registered"])): ?>
                <div class="alert">Account created successfully. You can now log in.</div>
            <?php endif; ?>
            <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required>
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
                <p><button class="btn btn-primary">Sign In</button></p>
            </form>
            <p class="auth-switch">Don't have an account? <a href="<?= e(url("register.php")) ?>"><strong>Register here</strong></a></p>
        </div>
    </div>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
