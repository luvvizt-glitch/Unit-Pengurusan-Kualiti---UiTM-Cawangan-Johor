<?php
session_start();
require_once '../config.php';

// Cek jika sudah log masuk, redirect ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_input = $_POST['username'] ?? ''; // Boleh jadi username atau email
    $password = $_POST['password'] ?? '';
    
    // Cari pengguna berdasarkan username ATAU email
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Berjaya Log Masuk
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Kata laluan tidak sah.";
        }
    } else {
        $error = "Nama pengguna atau emel tidak wujud.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk Admin - UPK UiTM</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="admin_style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/uitm-vector-logo.png?v=<?= time(); ?>">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2><i class="fa-solid fa-lock"></i> Pengurusan UPK</h2>
        <p style="margin-bottom:20px; color:#666;">Log masuk ke panel kawalan</p>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Kata Laluan</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Log Masuk</button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="forgot_password.php" style="font-size: 13px; color: var(--primary); text-decoration: none;">Lupa Kata Laluan?</a>
            </div>
        </form>
        
        <div style="margin-top: 20px; font-size: 13px;">
            <a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Laman Utama</a>
        </div>
    </div>
</div>

</body>
</html>
