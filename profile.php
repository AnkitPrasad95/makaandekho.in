<?php
ob_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/user-auth.php';
require_user_auth();

$flash  = get_user_flash();
$errors = [];

// Refresh user
$uStmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_deleted=0");
$uStmt->execute([current_user_id()]);
$user = $uStmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city  = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');

    if (!$name) $errors[] = 'Name is required.';

    // Password change
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';
    $updatePass = false;
    if ($newPass) {
        if (strlen($newPass) < 6) $errors[] = 'Password must be at least 6 characters.';
        elseif ($newPass !== $confirmPass) $errors[] = 'Passwords do not match.';
        else $updatePass = true;
    }

    if (empty($errors)) {
        $pdo->prepare("UPDATE users SET name=?, phone=?, city=?, state=? WHERE id=?")
            ->execute([$name, $phone, $city, $state, current_user_id()]);

        if ($updatePass) {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$hash, current_user_id()]);
        }

        // Refresh
        $uStmt->execute([current_user_id()]);
        $user = $uStmt->fetch();
        $_SESSION['user_data'] = $user;
        user_flash('success', 'Profile updated successfully.');
        header('Location: ' . SITE_URL . 'profile.php');
        exit;
    }
}

$pageTitle   = 'My Profile | MakaanDekho';
$pageNoIndex = true;
include __DIR__ . '/includes/header.php';
?>

<section class="user-dashboard">
<div class="container">
    <div class="dash-nav">
        <a href="<?= SITE_URL ?>dashboard.php" class="dash-nav-item"><i class="fas fa-chart-pie"></i> Overview</a>
        <a href="<?= SITE_URL ?>my-properties.php" class="dash-nav-item"><i class="fas fa-building"></i> My Properties</a>
        <a href="<?= SITE_URL ?>my-enquiries.php" class="dash-nav-item"><i class="fas fa-envelope"></i> Enquiries</a>
        <a href="<?= SITE_URL ?>my-favourites.php" class="dash-nav-item"><i class="fas fa-heart"></i> Saved</a>
        <a href="<?= SITE_URL ?>profile.php" class="dash-nav-item active"><i class="fas fa-user"></i> Profile</a>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?>" style="border-radius:10px;">
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" style="border-radius:10px;font-size:13px;">
        <?php foreach($errors as $e): ?><p class="mb-1"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="dash-card">
                <div class="dash-card-header"><h5>Edit Profile</h5></div>
                <div style="padding:20px;">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="wz-label">Full Name *</label>
                            <input type="text" name="name" class="wz-input" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="wz-label">Email (cannot change)</label>
                            <input type="email" class="wz-input" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="col-md-6">
                            <label class="wz-label">Phone</label>
                            <input type="tel" name="phone" class="wz-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" maxlength="10">
                        </div>
                        <div class="col-md-3">
                            <label class="wz-label">City</label>
                            <input type="text" name="city" class="wz-input" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="wz-label">State</label>
                            <input type="text" name="state" class="wz-input" value="<?= htmlspecialchars($user['state'] ?? '') ?>">
                        </div>
                        <div class="col-12"><hr style="margin:10px 0;"></div>
                        <div class="col-md-6">
                            <label class="wz-label">New Password <small class="text-muted">(leave blank to keep)</small></label>
                            <input type="password" name="new_password" class="wz-input" placeholder="Min 6 characters">
                        </div>
                        <div class="col-md-6">
                            <label class="wz-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="wz-input" placeholder="Re-enter">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-wz-submit"><i class="fas fa-save me-2"></i>Save Changes</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dash-card text-center" style="padding:30px;">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--primary);color:#fff;font-size:32px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <h5 style="font-weight:700;"><?= htmlspecialchars($user['name']) ?></h5>
                <span class="dash-role-badge"><?= ucfirst($user['role']) ?></span>
                <p class="mt-2 text-muted" style="font-size:13px;">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                <p style="font-size:13px;">
                    <span class="dash-status dash-status-<?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
