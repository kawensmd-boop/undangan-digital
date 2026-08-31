<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($id) {
        // Update
        if ($password) {
            $hashed = hashPassword($password);
            $conn->query("UPDATE users SET name = '$name', email = '$email', password = '$hashed' WHERE id = $id");
        } else {
            $conn->query("UPDATE users SET name = '$name', email = '$email' WHERE id = $id");
        }
    } else {
        // Insert
        if (!$password) {
            $error = 'Password harus diisi';
        } else {
            $hashed = hashPassword($password);
            $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed', 'admin')");
        }
    }

    if (!isset($error)) {
        redirect('index.php');
    }
}

$user_edit = null;
if ($id) {
    $user_edit = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
}

include '../layout.php';
?>

<div style="max-width: 500px; margin: 0 auto;">
    <div class="table-container">
        <h2><?php echo $id ? 'Edit Pengguna' : 'Tambah Pengguna'; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" value="<?php echo $user_edit['name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user_edit['email'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Password <?php echo $id ? '(Kosongkan jika tidak ingin mengubah)' : ''; ?></label>
                <input type="password" name="password" <?php echo !$id ? 'required' : ''; ?>>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>
