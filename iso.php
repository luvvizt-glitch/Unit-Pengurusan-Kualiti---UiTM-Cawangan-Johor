<?php
include 'header.php';

// Create uploads directory if not exists
$target_dir = "../uploads/iso/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM iso_documents WHERE id = $id");
    header("Location: iso.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doc_code = $conn->real_escape_string($_POST['doc_code']);
    $title = $conn->real_escape_string($_POST['title']);
    $status = $conn->real_escape_string($_POST['status']);
    $category = $conn->real_escape_string($_POST['category']);
    
    $file_path = "";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $file_name;
        }
    }

    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE iso_documents SET doc_code='$doc_code', title='$title', status='$status', category='$category'";
        if (!empty($file_path)) {
            $sql .= ", file_path='$file_path'";
        }
        $sql .= " WHERE id=$id";
        $conn->query($sql);
    } else {
        $conn->query("INSERT INTO iso_documents (doc_code, title, file_path, status, category) VALUES ('$doc_code', '$title', '$file_path', '$status', '$category')");
    }
    header("Location: iso.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM iso_documents WHERE id = $id")->fetch_assoc();
}

// Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE doc_code LIKE '%$search%' OR title LIKE '%$search%'";
}

$documents = $conn->query("SELECT * FROM iso_documents $where_clause ORDER BY created_at DESC");
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3><?= $edit_data ? 'Kemaskini Dokumen ISO' : 'Tambah Dokumen ISO Baharu' ?></h3>
        <a href="iso.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate-right"></i> Reset</a>
    </div>
    
    <form action="iso.php" method="POST" enctype="multipart/form-data">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Kod Dokumen (Contoh: ISO 9001:2015)</label>
                <input type="text" name="doc_code" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['doc_code']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Tajuk Dokumen</label>
                <input type="text" name="title" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['title']) : '' ?>">
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <select name="category" class="form-control" required>
                    <option value="SOP" <?= ($edit_data && $edit_data['category'] == 'SOP') ? 'selected' : '' ?>>SOP</option>
                    <option value="Manual Kualiti" <?= ($edit_data && $edit_data['category'] == 'Manual Kualiti') ? 'selected' : '' ?>>Manual Kualiti</option>
                    <option value="Garis Panduan" <?= ($edit_data && $edit_data['category'] == 'Garis Panduan') ? 'selected' : '' ?>>Garis Panduan</option>
                    <option value="Lain-lain" <?= ($edit_data && $edit_data['category'] == 'Lain-lain') ? 'selected' : '' ?>>Lain-lain</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Draf" <?= ($edit_data && $edit_data['status'] == 'Draf') ? 'selected' : '' ?>>Draf</option>
                    <option value="Diluluskan" <?= ($edit_data && $edit_data['status'] == 'Diluluskan') ? 'selected' : '' ?>>Diluluskan</option>
                    <option value="Obsolete" <?= ($edit_data && $edit_data['status'] == 'Obsolete') ? 'selected' : '' ?>>Obsolete (Arkib)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Fail Dokumen (PDF/Word) <?= $edit_data ? '<small style="color:red;">*Biarkan kosong jika tidak mahu tukar fail</small>' : '' ?></label>
                <input type="file" name="file" class="form-control" <?= $edit_data ? '' : 'required' ?>>
            </div>
        </div>
        
        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini Dokumen' : 'Muat Naik Dokumen' ?></button>
            <?php if($edit_data): ?>
                <a href="iso.php" class="btn btn-secondary">Batal</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card" style="margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Senarai Dokumen ISO (Audit-Ready)</h3>
        <form action="iso.php" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Cari kod atau tajuk..." value="<?= htmlspecialchars($search) ?>" style="width: 250px;">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kod</th>
                <th>Tajuk</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Fail</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($documents->num_rows > 0): ?>
                <?php $i = 1; while($row = $documents->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($row['doc_code']) ?></strong></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td>
                        <?php 
                        $status_class = '';
                        if($row['status'] == 'Diluluskan') $status_class = 'status-active';
                        else if($row['status'] == 'Draf') $status_class = 'status-expired'; // reusing existing color or mapping
                        else $status_class = 'status-pending';
                        ?>
                        <span class="badge <?= $status_class ?>" style="padding: 5px 10px; border-radius: 20px; font-size: 11px; text-transform: uppercase;">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if(!empty($row['file_path'])): ?>
                            <a href="../uploads/iso/<?= $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-secondary" title="Lihat Fail"><i class="fa-solid fa-file-pdf"></i></a>
                        <?php else: ?>
                            <span style="color: #ccc;">Tiada Fail</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-btns">
                        <a href="iso.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                        <a href="iso.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam/arkibkan dokumen ini?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: var(--text-muted);">Tiada rekod dijumpai.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
