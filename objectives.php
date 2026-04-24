<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM objectives WHERE id = $id");
    header("Location: objectives.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $letter = $conn->real_escape_string($_POST['letter']);
    $description = $conn->real_escape_string($_POST['description']);
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE objectives SET letter='$letter', description='$description' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO objectives (letter, description) VALUES ('$letter', '$description')");
    }
    header("Location: objectives.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM objectives WHERE id = $id")->fetch_assoc();
}

$objectives = $conn->query("SELECT * FROM objectives ORDER BY letter ASC");
?>

<div class="card">
    <h3><?= $edit_data ? 'Kemaskini Objektif Kualiti' : 'Tambah Objektif Baharu' ?></h3>
    <form action="objectives.php" method="POST" style="margin-top: 20px;">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Huruf Objektif (cth: A)</label>
            <input type="text" name="letter" class="form-control" required maxlength="1" value="<?= $edit_data ? htmlspecialchars($edit_data['letter']) : '' ?>" style="width: 100px; text-transform: uppercase;">
        </div>
        
        <div class="form-group">
            <label>Keterangan Objektif</label>
            <textarea name="description" class="form-control" required><?= $edit_data ? htmlspecialchars($edit_data['description']) : '' ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini' : 'Tambah' ?></button>
        <?php if($edit_data): ?>
            <a href="objectives.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Senarai Objektif Kualiti</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">Huruf</th>
                <th width="75%">Keterangan</th>
                <th width="15%">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $objectives->fetch_assoc()): ?>
            <tr>
                <td><strong style="font-size:18px; color:var(--primary);"><?= htmlspecialchars($row['letter']) ?></strong></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td class="action-btns">
                    <a href="objectives.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                    <a href="objectives.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam objektif ini?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
