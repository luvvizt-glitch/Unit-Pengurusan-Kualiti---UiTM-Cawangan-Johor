<?php include 'header.php'; ?>

<?php
// Get counts for dashboard
$news_count = $conn->query("SELECT COUNT(*) as ttl FROM news")->fetch_assoc()['ttl'];
$events_count = $conn->query("SELECT COUNT(*) as ttl FROM events")->fetch_assoc()['ttl'];
$team_count = $conn->query("SELECT COUNT(*) as ttl FROM team_members")->fetch_assoc()['ttl'];
$obj_count = $conn->query("SELECT COUNT(*) as ttl FROM objectives")->fetch_assoc()['ttl'];
$resp_count = $conn->query("SELECT COUNT(*) as ttl FROM responsibilities")->fetch_assoc()['ttl'];
$iso_count = $conn->query("SELECT COUNT(*) as ttl FROM iso_documents")->fetch_assoc()['ttl'];
$kpi_count = $conn->query("SELECT COUNT(*) as ttl FROM kpi_transformasi")->fetch_assoc()['ttl'];
?>

<div class="stats-grid">
    <div class="stat-card">
        <i class="fa-solid fa-newspaper"></i>
        <h3><?= $news_count ?></h3>
        <p>Berita</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-calendar-day"></i>
        <h3><?= $events_count ?></h3>
        <p>Acara</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-users"></i>
        <h3><?= $team_count ?></h3>
        <p>Pasukan</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-file-contract"></i>
        <h3><?= $iso_count ?></h3>
        <p>Dokumen ISO</p>
    </div>
    <div class="stat-card">
        <i class="fa-solid fa-chart-line"></i>
        <h3><?= $kpi_count ?></h3>
        <p>KPI Aktif</p>
    </div>
</div>

<div class="card" style="margin-top: 30px;">
    <h3><i class="fa-solid fa-circle-info"></i> Maklumat Pentadbir</h3>
    <p style="margin-top: 15px; color: var(--text-muted); line-height: 1.6;">
        Halaman ini membolehkan anda untuk menguruskan kandungan utama tapak web Unit Pengurusan Kualiti (UPK).
        Gunakan menu di sebelah kiri untuk menambah, mengubah, atau memadamkan maklumat yang dipaparkan di halaman utama kepada para pelawat.
    </p>
</div>

<?php include 'footer.php'; ?>
