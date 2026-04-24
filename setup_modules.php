<?php
require_once '../config.php';

echo "<h2>Setup Modul Baharu (ISO & KPI)</h2>";

// 1. Create iso_documents table
$sql1 = "CREATE TABLE IF NOT EXISTS iso_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doc_code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('Draf', 'Diluluskan', 'Obsolete') DEFAULT 'Draf',
    category ENUM('SOP', 'Manual Kualiti', 'Garis Panduan', 'Lain-lain') DEFAULT 'SOP',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql1)) {
    echo "<p style='color:green;'>Jadual 'iso_documents' berjaya dicipta atau sudah wujud.</p>";
} else {
    echo "<p style='color:red;'>Ralat cipta jadual ISO: " . $conn->error . "</p>";
}

// 2. Create kpi_transformasi table
$sql2 = "CREATE TABLE IF NOT EXISTS kpi_transformasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    quarter ENUM('Q1', 'Q2', 'Q3', 'Q4') NOT NULL,
    achievement DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql2)) {
    echo "<p style='color:green;'>Jadual 'kpi_transformasi' berjaya dicipta atau sudah wujud.</p>";
} else {
    echo "<p style='color:red;'>Ralat cipta jadual KPI: " . $conn->error . "</p>";
}

// 3. Create uploads directory
$target_dir = "../uploads/iso/";
if (!file_exists($target_dir)) {
    if (mkdir($target_dir, 0777, true)) {
        echo "<p style='color:green;'>Folder 'uploads/iso/' berjaya dicipta.</p>";
    } else {
        echo "<p style='color:red;'>Gagal mencipta folder 'uploads/iso/'. Periksa kebenaran (permissions).</p>";
    }
} else {
    echo "<p style='color:blue;'>Folder 'uploads/iso/' sudah wujud.</p>";
}

echo "<br><a href='index.php' class='btn btn-primary'>Kembali ke Dashboard</a>";
?>
<style>
    body { font-family: 'Inter', sans-serif; padding: 40px; }
    .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
</style>
