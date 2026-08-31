<?php
include '../check-auth.php';
$page = 'tamu';
include '../layout.php';

$id = intval($_GET['id'] ?? 0);
$guest = null;

if ($id) {
    $guest = $conn->query("SELECT * FROM guests WHERE id = $id")->fetch_assoc();
}

$events = $conn->query("SELECT * FROM events WHERE user_id = {$user['id']}")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $relationship = sanitize($_POST['relationship'] ?? '');
    $event_id = intval($_POST['event_id'] ?? 0);
    $num_guests = intval($_POST['num_guests'] ?? 1);

    if (!$name || !$event_id) {
        $error = 'Data tidak lengkap';
    } else {
        if ($id) {
            // Update
            $query = "UPDATE guests SET name = '$name', phone = '$phone', email = '$email', 
                     relationship = '$relationship', num_guests = $num_guests WHERE id = $id";
            if ($conn->query($query)) {
                redirect('index.php?success=1');
            }
        } else {
            // Insert
            $token = generateToken();
            $query = "INSERT INTO guests (event_id, name, phone, email, relationship, token, num_guests)
                     VALUES ($event_id, '$name', '$phone', '$email', '$relationship', '$token', $num_guests)";
            if ($conn->query($query)) {
                redirect('index.php?success=1');
            }
        }
    }
}

?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="table-container">
        <h2><?php echo $id ? 'Edit Tamu' : 'Tambah Tamu'; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Undangan</label>
                <select name="event_id" required>
                    <option value="">Pilih Undangan</option>
                    <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>" <?php echo (isset($guest['event_id']) && $guest['event_id'] == $event['id']) ? 'selected' : ''; ?>>
                        <?php echo $event['title']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" value="<?php echo $guest['name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="tel" name="phone" value="<?php echo $guest['phone'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $guest['email'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Hubungan</label>
                <input type="text" name="relationship" placeholder="Keluarga, Teman, Kolega" value="<?php echo $guest['relationship'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Jumlah Tamu</label>
                <input type="number" name="num_guests" min="1" max="10" value="<?php echo $guest['num_guests'] ?? 1; ?>">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>
