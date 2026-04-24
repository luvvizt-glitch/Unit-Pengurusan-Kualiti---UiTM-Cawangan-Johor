<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
require_once '../config.php';

// Get current page name to active link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - UPK UiTM</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="admin_style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/uitm-vector-logo.png?v=<?= time(); ?>">
    <link rel="apple-touch-icon" href="../assets/uitm-vector-logo.png?v=<?= time(); ?>">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../assets/uitm-vector-logo.png" alt="UiTM Logo">
            <div class="brand-name">UPK ADMIN PANEL</div>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="news.php" class="<?= $current_page == 'news.php' ? 'active' : '' ?>"><i class="fa-solid fa-newspaper"></i> Urus Berita</a></li>
            <li><a href="events.php" class="<?= $current_page == 'events.php' ? 'active' : '' ?>"><i class="fa-solid fa-calendar-day"></i> Urus Acara</a></li>
            <li><a href="team.php" class="<?= $current_page == 'team.php' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Urus Pasukan</a></li>
            <li><a href="responsibilities.php" class="<?= $current_page == 'responsibilities.php' ? 'active' : '' ?>"><i class="fa-solid fa-list-check"></i> Urus Tanggungjawab</a></li>
            <li><a href="objectives.php" class="<?= $current_page == 'objectives.php' ? 'active' : '' ?>"><i class="fa-solid fa-bullseye"></i> Urus Objektif</a></li>
            <li><a href="iso.php" class="<?= $current_page == 'iso.php' ? 'active' : '' ?>"><i class="fa-solid fa-file-contract"></i> Pengurusan ISO</a></li>
            <li><a href="kpi.php" class="<?= $current_page == 'kpi.php' ? 'active' : '' ?>"><i class="fa-solid fa-chart-line"></i> Monitor Transformasi</a></li>
            <li><a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>"><i class="fa-solid fa-user-shield"></i> Urus Admin</a></li>
        </ul>
        <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Keluar</a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['admin_username']) ?></h2>
            <a href="../index.php" target="_blank" class="btn btn-secondary"><i class="fa-solid fa-globe"></i> Lihat Laman Sesawang</a>
        </header>

        <!-- Dynamic Content Starts Here -->
        <div class="content-body">
