<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM team_members WHERE id = $id");
    header("Location: team.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $initials = $conn->real_escape_string($_POST['initials']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $role = $conn->real_escape_string($_POST['role']);
    $campus = $conn->real_escape_string($_POST['campus']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE team_members SET initials='$initials', full_name='$full_name', role='$role', campus='$campus', email='$email', phone_number='$phone_number' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO team_members (initials, full_name, role, campus, email, phone_number) VALUES ('$initials', '$full_name', '$role', '$campus', '$email', '$phone_number')");
    }
    header("Location: team.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM team_members WHERE id = $id")->fetch_assoc();
}

$team = $conn->query("SELECT * FROM team_members ORDER BY id ASC");
?>

<div class="card">
    <h3><?= $edit_data ? 'Kemaskini Ahli Pasukan' : 'Tambah Ahli Baharu' ?></h3>
    <form action="team.php" method="POST" style="margin-top: 20px;">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Nama Penuh</label>
                <input type="text" name="full_name" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['full_name']) : '' ?>">
            </div>
            <div class="form-group">
                <label>N. Singkatan (Initials cth: HH)</label>
                <input type="text" name="initials" class="form-control" required maxlength="5" value="<?= $edit_data ? htmlspecialchars($edit_data['initials']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Jawatan / Peranan</label>
                <input type="text" name="role" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['role']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Kampus</label>
                <select name="campus" class="form-control" required>
                    <option value="Kampus Segamat" <?= ($edit_data && $edit_data['campus'] == 'Kampus Segamat') ? 'selected' : '' ?>>Kampus Segamat</option>
                    <option value="Kampus Pasir Gudang" <?= ($edit_data && $edit_data['campus'] == 'Kampus Pasir Gudang') ? 'selected' : '' ?>>Kampus Pasir Gudang</option>
                </select>
            </div>
            <div class="form-group">
                <label>Emel Rasmi</label>
                <input type="email" name="email" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['email']) : '' ?>">
            </div>
            <div class="form-group">
                <label>No. Telefon</label>
                <input type="text" name="phone_number" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['phone_number']) : '' ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;"><?= $edit_data ? 'Kemaskini' : 'Tambah' ?></button>
        <?php if($edit_data): ?>
            <a href="team.php" class="btn btn-secondary" style="margin-top: 10px;">Batal</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Senarai Ahli Jawatankuasa / Pasukan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Peranan</th>
                <th>Kampus</th>
                <th>Hubungan</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while($row = $team->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td>
                    <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
                    <small style="color:#888;">(<?= htmlspecialchars($row['initials']) ?>)</small>
                </td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['campus']) ?></td>
                <td>
                    <small><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($row['email']) ?></small><br>
                    <small><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($row['phone_number']) ?></small>
                </td>
                <td class="action-btns">
                    <a href="team.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                    <a href="team.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam rekod ini?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
