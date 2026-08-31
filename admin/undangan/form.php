<?php
// Handle undangan form submission
include '../check-auth.php';
$page = 'undangan';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$event = null;

if ($id) {
    $event = $conn->query("SELECT * FROM events WHERE id = $id AND user_id = {$user['id']}")->fetch_assoc();
    if (!$event) {
        redirect('index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $couple_name_1 = sanitize($_POST['couple_name_1'] ?? '');
    $couple_name_2 = sanitize($_POST['couple_name_2'] ?? '');
    $event_date = sanitize($_POST['event_date'] ?? '');
    $event_location = sanitize($_POST['event_location'] ?? '');
    $event_address = sanitize($_POST['event_address'] ?? '');
    $ceremony_time = sanitize($_POST['ceremony_time'] ?? '');
    $ceremony_location = sanitize($_POST['ceremony_location'] ?? '');
    $reception_time = sanitize($_POST['reception_time'] ?? '');
    $reception_location = sanitize($_POST['reception_location'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $slug = generateSlug($title);

    if (!$title || !$couple_name_1 || !$couple_name_2 || !$event_date) {
        $error = 'Data tidak lengkap';
    } else {
        if ($id) {
            // Update
            $query = "UPDATE events SET title = '$title', couple_name_1 = '$couple_name_1', 
                     couple_name_2 = '$couple_name_2', event_date = '$event_date',
                     event_location = '$event_location', event_address = '$event_address',
                     ceremony_time = '$ceremony_time', ceremony_location = '$ceremony_location',
                     reception_time = '$reception_time', reception_location = '$reception_location',
                     description = '$description', is_active = $is_active
                     WHERE id = $id";
            if ($conn->query($query)) {
                redirect('index.php?success=1');
            }
        } else {
            // Insert
            $query = "INSERT INTO events (user_id, title, slug, couple_name_1, couple_name_2, 
                     event_date, event_location, event_address, ceremony_time, ceremony_location,
                     reception_time, reception_location, description, is_active)
                     VALUES ({$user['id']}, '$title', '$slug', '$couple_name_1', '$couple_name_2',
                     '$event_date', '$event_location', '$event_address', '$ceremony_time', 
                     '$ceremony_location', '$reception_time', '$reception_location', 
                     '$description', $is_active)";
            if ($conn->query($query)) {
                redirect('index.php?success=1');
            }
        }
    }
}

include '../layout.php';
?>

<div style="max-width: 800px; margin: 0 auto;">
    <div class="table-container">
        <h2><?php echo $id ? 'Edit Undangan' : 'Tambah Undangan'; ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Judul Acara</label>
                <input type="text" name="title" value="<?php echo $event['title'] ?? ''; ?>" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nama Pengantin 1</label>
                    <input type="text" name="couple_name_1" value="<?php echo $event['couple_name_1'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Nama Pengantin 2</label>
                    <input type="text" name="couple_name_2" value="<?php echo $event['couple_name_2'] ?? ''; ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Tanggal & Waktu Acara</label>
                    <input type="datetime-local" name="event_date" value="<?php echo $event['event_date'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Lokasi Acara</label>
                <input type="text" name="event_location" value="<?php echo $event['event_location'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="event_address"><?php echo $event['event_address'] ?? ''; ?></textarea>
            </div>

            <h3 style="margin-top: 30px; margin-bottom: 20px;">Detail Acara</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Waktu Akad</label>
                    <input type="time" name="ceremony_time" value="<?php echo $event['ceremony_time'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label>Lokasi Akad</label>
                    <input type="text" name="ceremony_location" value="<?php echo $event['ceremony_location'] ?? ''; ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Waktu Resepsi</label>
                    <input type="time" name="reception_time" value="<?php echo $event['reception_time'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label>Lokasi Resepsi</label>
                    <input type="text" name="reception_location" value="<?php echo $event['reception_location'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" placeholder="Deskripsi acara..."><?php echo $event['description'] ?? ''; ?></textarea>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" <?php echo (isset($event['is_active']) && $event['is_active']) ? 'checked' : ''; ?> style="width: auto; margin-right: 10px;">
                    <span>Aktifkan Undangan</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>
