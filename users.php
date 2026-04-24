<?php
require_once 'header.php';

$message = '';
$message_type = '';

// Proses Simpan Admin Baharu
if (isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek jika username atau email sudah wujud
    $check_query = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $message = "Ralat: Nama pengguna atau Emel sudah didaftarkan.";
        $message_type = "danger";
    } else {
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $message = "Admin baharu berjaya ditambahkan!";
            $message_type = "success";
        } else {
            $message = "Ralat semasa menambah admin: " . $conn->error;
            $message_type = "danger";
        }
        $stmt->close();
    }
    $stmt_check->close();
}

// Proses Padam Admin
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    
    // Jangan benarkan admin padam diri sendiri
    $logged_in_id = $_SESSION['admin_id'] ?? 0;
    if ($id_to_delete == $logged_in_id) {
        $message = "Ralat: Anda tidak boleh memadam akaun anda sendiri yang sedang aktif.";
        $message_type = "danger";
    } else {
        // Cek jumlah admin (jangan padam jika tinggal satu sahaja)
        $count_query = "SELECT COUNT(*) as total FROM users";
        $total_result = $conn->query($count_query);
        $total_row = $total_result->fetch_assoc();
        
        if ($total_row['total'] <= 1) {
            $message = "Ralat: Tidak boleh memadam admin terakhir. Sekurang-kurangnya satu admin diperlukan.";
            $message_type = "danger";
        } else {
            $delete_query = "DELETE FROM users WHERE id = ?";
            $stmt_del = $conn->prepare($delete_query);
            $stmt_del->bind_param("i", $id_to_delete);
            
            if ($stmt_del->execute()) {
                $message = "Admin berjaya dipadamkan!";
                $message_type = "success";
            } else {
                $message = "Ralat semasa memadam admin.";
                $message_type = "danger";
            }
            $stmt_del->close();
        }
    }
}

// Ambil senarai semua admin
$users_query = "SELECT id, username, email, created_at FROM users ORDER BY id ASC";
$users_result = $conn->query($users_query);
?>

<div class="users-page">
    <div class="page-header" style="margin-bottom: 25px;">
        <h1 style="color: var(--primary);"><i class="fa-solid fa-user-shield"></i> Pengurusan Akaun Admin</h1>
        <p style="color: var(--text-light);">Uruskan siapa yang mempunyai akses kepada panel kawalan ini.</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px;">
        
        <!-- Borang Tambah Admin -->
        <div class="card">
            <h3 style="margin-bottom: 20px; color: var(--primary); border-bottom: 2px solid var(--secondary); padding-bottom: 10px;">
                <i class="fa-solid fa-user-plus"></i> Tambah Admin Baharu
            </h3>
            <form action="users.php" method="POST">
                <div class="form-group">
                    <label>Nama Pengguna (Username)</label>
                    <input type="text" name="username" class="form-control" required placeholder="Contoh: admin_upk" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Emel Rasmi</label>
                    <input type="email" name="email" class="form-control" required placeholder="nama@gmail.com">
                </div>
                <div class="form-group">
                    <label>Kata Laluan</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                    <small style="color: #666; font-size: 12px;">Minimum 6 aksara.</small>
                </div>
                <button type="submit" name="add_admin" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-save"></i> Daftar Admin
                </button>
            </form>
        </div>

        <!-- Senarai Admin -->
        <div class="card">
            <h3 style="margin-bottom: 20px; color: var(--primary); border-bottom: 2px solid var(--secondary); padding-bottom: 10px;">
                <i class="fa-solid fa-list"></i> Senarai Admin Sedia Ada
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User / Email</th>
                        <th>Tarikh Daftar</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                            <small style="color: var(--text-light);"><?= htmlspecialchars($row['email']) ?></small>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <?php 
                            $logged_in_id = $_SESSION['admin_id'] ?? 0;
                            if ($row['id'] == $logged_in_id): 
                            ?>
                                <span class="badge" style="background: #e1f5fe; color: #039be5; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Akaun Anda</span>
                            <?php else: ?>
                                <a href="users.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Adakah anda pasti mahu memadam admin ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php require_once 'footer.php'; ?>
