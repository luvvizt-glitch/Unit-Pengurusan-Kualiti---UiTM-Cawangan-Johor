<?php
session_start();
require_once '../config.php';
require_once '../smtp_config.php';

// PHPMailer classes
require '../lib/phpmailer/Exception.php';
require '../lib/phpmailer/PHPMailer.php';
require '../lib/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    
    // Check if email exists in users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // Generate Token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Save to password_resets
        $stmt_reset = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
        $stmt_reset->bind_param("sss", $email, $token, $expiry);
        $stmt_reset->execute();
        
        // Send Email
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->SMTPDebug = 0; // Ubah kepada 2 jika ingin melihat log ralat terperinci
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = (SMTP_PORT == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;

            // Bypass SSL Verification (Penting untuk XAMPP)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Recipients
            $mail->setFrom(SMTP_FROM, SMTP_NAME);
            $mail->addAddress($email);
            
            // Content
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("forgot_password.php", "reset_password.php", $_SERVER['PHP_SELF']) . "?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Reset Kata Laluan - UPK UiTM';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee;'>
                    <h2 style='color: #4A0072;'>Permohonan Reset Kata Laluan</h2>
                    <p>Kami menerima permintaan untuk menetapkan semula kata laluan anda bagi portal Admin UPK UiTM.</p>
                    <p>Sila klik butang di bawah untuk meneruskan:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='$reset_link' style='background-color: #4A0072; color: #FFD700; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Reset Kata Laluan</a>
                    </div>
                    <p>Pautan ini akan luput dalam masa 1 jam.</p>
                    <p>Jika anda tidak membuat permintaan ini, sila abaikan emel ini.</p>
                    <hr style='border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #999;'>Ini adalah emel automatik, sila jangan balas.</p>
                </div>
            ";
            
            $mail->send();
            $message = "Pautan penetapan semula telah dihantar ke emel anda.";
            $message_type = "success";
        } catch (Exception $e) {
            $message = "Gagal menghantar emel. Ralat: " . $mail->ErrorInfo;
            $message_type = "danger";
        }
    } else {
        $message = "Alamat emel tidak dijumpai dalam sistem.";
        $message_type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Laluan - UPK UiTM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/uitm-vector-logo.png?v=<?= time(); ?>">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2><i class="fa-solid fa-envelope-open-text"></i> Lupa Kata Laluan</h2>
        <p style="margin-bottom:20px; color:#666;">Masukkan emel admin anda untuk menerima pautan reset.</p>
        
        <?php if($message): ?>
            <div class="alert alert-<?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Alamat Emel</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="nama@uitm.edu.my">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Hantar Pautan Reset</button>
        </form>
        
        <div style="margin-top: 20px; font-size: 13px;">
            <a href="login.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Log Masuk</a>
        </div>
    </div>
</div>

</body>
</html>
