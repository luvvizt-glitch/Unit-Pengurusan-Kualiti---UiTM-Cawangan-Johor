<?php
include 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM news WHERE id = $id");
    header("Location: news.php");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $news_type = $conn->real_escape_string($_POST['news_type']);
    $date_posted = $conn->real_escape_string($_POST['date_posted']);
    
    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("UPDATE news SET title='$title', news_type='$news_type', date_posted='$date_posted' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO news (title, news_type, date_posted) VALUES ('$title', '$news_type', '$date_posted')");
    }
    header("Location: news.php");
    exit;
}

// Fetch Data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM news WHERE id = $id")->fetch_assoc();
}

$news = $conn->query("SELECT * FROM news ORDER BY date_posted DESC");
?>

<div class="card">
    <h3><?= $edit_data ? 'Kemaskini Berita/Pengumuman' : 'Tambah Berita Baharu' ?></h3>
    <form action="news.php" method="POST" style="margin-top: 20px;">
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Tajuk</label>
            <input type="text" name="title" class="form-control" required value="<?= $edit_data ? htmlspecialchars($edit_data['title']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Jenis</label>
            <select name="news_type" class="form-control" required>
                <option value="Berita" <?= ($edit_data && $edit_data['news_type'] == 'Berita') ? 'selected' : '' ?>>Berita</option>
                <option value="Pengumuman" <?= ($edit_data && $edit_data['news_type'] == 'Pengumuman') ? 'selected' : '' ?>>Pengumuman</option>
                <option value="Acara" <?= ($edit_data && $edit_data['news_type'] == 'Acara') ? 'selected' : '' ?>>Acara</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tarikh Dilancarkan</label>
            <input type="date" name="date_posted" class="form-control" required value="<?= $edit_data ? $edit_data['date_posted'] : '' ?>">
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Kemaskini' : 'Tambah' ?></button>
        <?php if($edit_data): ?>
            <a href="news.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Senarai Berita & Pengumuman</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tajuk</th>
                <th>Jenis</th>
                <th>Tarikh</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while($row = $news->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><span style="background:#eee; padding:3px 8px; border-radius:4px; font-size:12px;"><?= htmlspecialchars($row['news_type']) ?></span></td>
                <td><?= htmlspecialchars($row['date_posted']) ?></td>
                <td class="action-btns">
                    <a href="news.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-pen"></i></a>
                    <a href="news.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Pasti mahu padam rekod ini?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
