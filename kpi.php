<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM kpi_transformasi WHERE id = $id");
    header("Location: kpi.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $year = (int)$_POST['year'];
    $quarter = $conn->real_escape_string($_POST['quarter']);
    $achievement = (float)$_POST['achievement'];
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE kpi_transformasi SET title='$title', year=$year, quarter='$quarter', achievement=$achievement WHERE id=$id");
    } else {
        $conn->query("INSERT INTO kpi_transformasi (title, year, quarter, achievement) VALUES ('$title', $year, '$quarter', $achievement)");
    }
    header("Location: kpi.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM kpi_transformasi WHERE id = $id")->fetch_assoc();
}

// Filter Logic
$f_year = isset($_GET['f_year']) ? (int)$_GET['f_year'] : date('Y');
$f_quarter = isset($_GET['f_quarter']) ? $conn->real_escape_string($_GET['f_quarter']) : '';

$where_clause = " WHERE year = $f_year";
if (!empty($f_quarter)) {
    $where_clause .= " AND quarter = '$f_quarter'";
}

$kpis = $conn->query("SELECT * FROM kpi_transformasi $where_clause ORDER BY quarter ASC, title ASC");

// Get distinctive years for filter
$years_res = $conn->query("SELECT DISTINCT year FROM kpi_transformasi ORDER BY year DESC");
$years = [];
while($yr = $years_res->fetch_assoc()) $years[] = $yr['year'];
if(!in_array(date('Y'), $years)) array_unshift($years, date('Y'));
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3><?= $edit_data ? 'Kemaskini Pencapaian KPI' : 'Tambah Pencapaian KPI Baharu' ?></h3>
        <?php if($edit_data): ?>
            <a href="kpi.php" class="btn btn-secondary btn-sm">Batal</a>
        <?php endif; ?>
    </div>
    
    <form action="kpi.php" method="POST">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Nama/Tajuk KPI (Performance Metric)</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Bilangan Penyelidikan Berindeks" value="<?= $edit_data ? htmlspecialchars($edit_data['title']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="year" class="form-control" required value="<?= $edit_data ? $edit_data['year'] : date('Y') ?>">
            </div>
            
            <div class="form-group">
                <label>Suku Tahun</label>
                <select name="quarter" class="form-control" required>
                    <option value="Q1" <?= ($edit_data && $edit_data['quarter'] == 'Q1') ? 'selected' : '' ?>>Q1 (Jan-Mac)</option>
                    <option value="Q2" <?= ($edit_data && $edit_data['quarter'] == 'Q2') ? 'selected' : '' ?>>Q2 (Apr-Jun)</option>
                    <option value="Q3" <?= ($edit_data && $edit_data['quarter'] == 'Q3') ? 'selected' : '' ?>>Q3 (Jul-Sep)</option>
                    <option value="Q4" <?= ($edit_data && $edit_data['quarter'] == 'Q4') ? 'selected' : '' ?>>Q4 (Okt-Dis)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Pencapaian (%)</label>
                <input type="number" step="0.01" name="achievement" class="form-control" required placeholder="0.00" value="<?= $edit_data ? $edit_data['achievement'] : '' ?>">
            </div>
        </div>
        
        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini Data' : 'Simpan Pencapaian' ?></button>
        </div>
    </form>
</div>

<div class="card" style="margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Monitor Transformasi (Prestasi Unit)</h3>
        
        <form action="kpi.php" method="GET" style="display: flex; gap: 10px; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 11px;">Tahun</label>
                <select name="f_year" class="form-control" style="width: 100px;">
                    <?php foreach($years as $y): ?>
                        <option value="<?= $y ?>" <?= $f_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label style="font-size: 11px;">Suku</label>
                <select name="f_quarter" class="form-control" style="width: 100px;">
                    <option value="">Semua</option>
                    <option value="Q1" <?= $f_quarter == 'Q1' ? 'selected' : '' ?>>Q1</option>
                    <option value="Q2" <?= $f_quarter == 'Q2' ? 'selected' : '' ?>>Q2</option>
                    <option value="Q3" <?= $f_quarter == 'Q3' ? 'selected' : '' ?>>Q3</option>
                    <option value="Q4" <?= $f_quarter == 'Q4' ? 'selected' : '' ?>>Q4</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Tapis</button>
        </form>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun / Suku</th>
                <th>Indikator KPI</th>
                <th>Prestasi</th>
                <th>Status</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($kpis->num_rows > 0): ?>
                <?php $i = 1; while($row = $kpis->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= $row['year'] ?> - <?= $row['quarter'] ?></strong></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex-grow: 1; height: 8px; background: #eee; border-radius: 4px; overflow: hidden; width: 100px;">
                                <div style="width: <?= $row['achievement'] ?>%; height: 100%; background: <?= $row['achievement'] >= 80 ? 'var(--accent-color)' : ($row['achievement'] >= 50 ? '#f1c40f' : '#e74c3c') ?>;"></div>
                            </div>
                            <span style="font-weight: 600; font-size: 13px;"><?= number_format($row['achievement'], 1) ?>%</span>
                        </div>
                    </td>
                    <td>
                        <?php if($row['achievement'] >= 90): ?>
                            <span class="badge status-active">Cemerlang</span>
                        <?php elseif($row['achievement'] >= 75): ?>
                            <span class="badge status-active" style="background: #27ae60;">Baik</span>
                        <?php else: ?>
                            <span class="badge status-pending">Perlu Tindakan</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-btns">
                        <a href="kpi.php?edit=<?= $row['id'] ?>&f_year=<?= $f_year ?>&f_quarter=<?= $f_quarter ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                        <a href="kpi.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam data pencapaian ini?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: var(--text-muted);">Tiada data pencapaian untuk filter ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
