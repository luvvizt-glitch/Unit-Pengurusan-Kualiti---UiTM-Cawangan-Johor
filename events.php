<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM events WHERE id = $id");
    header("Location: events.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $location = $conn->real_escape_string($_POST['location']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE events SET title='$title', location='$location', event_date='$event_date' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO events (title, location, event_date) VALUES ('$title', '$location', '$event_date')");
    }
    header("Location: events.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM events WHERE id = $id")->fetch_assoc();
}

$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<div class="card">
    <h3><?= $edit_data ? 'Kemaskini Acara' : 'Tambah Acara Baharu' ?></h3>
    <form action="events.php" method="POST" style="margin-top: 20px;">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Tajuk Acara</label>
            <input type="text" name="title" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['title']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['location']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Tarikh Acara</label>
            <input type="date" name="event_date" class="form-control" required value="<?= $edit_data ? $edit_data['event_date'] : '' ?>">
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini' : 'Tambah' ?></button>
        <?php if($edit_data): ?>
            <a href="events.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Senarai Acara Akan Datang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tajuk</th>
                <th>Lokasi</th>
                <th>Tarikh Acara</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while($row = $events->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= htmlspecialchars($row['event_date']) ?></td>
                <td class="action-btns">
                    <a href="events.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                    <a href="events.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam rekod ini?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
