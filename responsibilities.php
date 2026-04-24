<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM responsibilities WHERE id = $id");
    header("Location: responsibilities.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $conn->real_escape_string($_POST['category']);
    $task = $conn->real_escape_string($_POST['task']);
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE responsibilities SET category='$category', task='$task' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO responsibilities (category, task) VALUES ('$category', '$task')");
    }
    header("Location: responsibilities.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM responsibilities WHERE id = $id")->fetch_assoc();
}

$responsibilities = $conn->query("SELECT * FROM responsibilities ORDER BY category, id ASC");
?>

<div class="card">
    <h3><?= $edit_data ? 'Kemaskini Tanggungjawab' : 'Tambah Tanggungjawab Baharu' ?></h3>
    <form action="responsibilities.php" method="POST" style="margin-top: 20px;">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Kategori / Jawatan</label>
            <select name="category" class="form-control" required>
                <option value="Ketua Unit Kualiti" <?= ($edit_data && $edit_data['category'] == 'Ketua Unit Kualiti') ? 'selected' : '' ?>>Ketua Unit Kualiti</option>
                <option value="Koordinator Akreditasi" <?= ($edit_data && $edit_data['category'] == 'Koordinator Akreditasi') ? 'selected' : '' ?>>Koordinator Akreditasi</option>
                <option value="Penyelaras Kualiti" <?= ($edit_data && $edit_data['category'] == 'Penyelaras Kualiti') ? 'selected' : '' ?>>Penyelaras Kualiti</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tugas / Tanggungjawab</label>
            <textarea name="task" class="form-control" required><?= $edit_data ? htmlspecialchars($edit_data['task']) : '' ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini' : 'Tambah' ?></button>
        <?php if($edit_data): ?>
            <a href="responsibilities.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Senarai Tugas & Tanggungjawab</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Tanggungjawab</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while($row = $responsibilities->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><strong><?= htmlspecialchars($row['category']) ?></strong></td>
                <td><?= htmlspecialchars($row['task']) ?></td>
                <td class="action-btns">
                    <a href="responsibilities.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                    <a href="responsibilities.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam rekod ini?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
