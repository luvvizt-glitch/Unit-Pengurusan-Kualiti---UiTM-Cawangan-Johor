<?php
session_start();
require_once '../config.php';

$token = $_GET['token'] ?? '';
$message = '';
$message_type = '';
$valid_token = false;
$email = '';

if ($token) {
    // Validate token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expiry > NOW() LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $valid_token = true;
        $row = $result->fetch_assoc();
        $email = $row['email'];
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (!empty($new_password) && $new_password === $confirm_password) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt_update->bind_param("ss", $hashed_password, $email);
                
                if ($stmt_update->execute()) {
                    // Delete token after success
                    $stmt_del = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                    $stmt_del->bind_param("s", $email);
                    $stmt_del->execute();
                    
                    header("Location: login.php?msg=Kata laluan berjaya ditukar. Sila log masuk.");
                    exit;
                } else {
                    $message = "Gagal mengemaskini kata laluan.";
                    $message_type = "danger";
                }
            } else {
                $message = "Kata laluan tidak sepadan atau kosong.";
                $message_type = "danger";
            }
        }
    } else {
        $message = "Pautan reset tidak sah atau telah luput (tamat tempoh).";
        $message_type = "danger";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapkan Semula Kata Laluan - UPK UiTM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/uitm-vector-logo.png">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2><i class="fa-solid fa-key"></i> Reset Kata Laluan</h2>
        <p style="margin-bottom:20px; color:#666;">Sila masukkan kata laluan baharu anda.</p>
        
        <?php if($message): ?>
            <div class="alert alert-<?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if($valid_token): ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="password">Kata Laluan Baharu</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label for="confirm_password">Sahkan Kata Laluan</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Kemaskini Kata Laluan</button>
        </form>
        <?php endif; ?>
        
        <div style="margin-top: 20px; font-size: 13px;">
            <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Log Masuk</a>
        </div>
    </div>
</div>

</body>
</html>
