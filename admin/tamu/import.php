<?php
include '../check-auth.php';
$page = 'tamu';
include '../layout.php';

?>

<div class="table-container">
    <h2>Import Tamu dari CSV</h2>
    
    <form method="POST" enctype="multipart/form-data" style="max-width: 500px;">
        <div class="form-group">
            <label>Pilih File CSV</label>
            <input type="file" name="csv_file" accept=".csv" required>
            <small style="color: var(--text-light); display: block; margin-top: 8px;">
                Format CSV: nama, phone, email, relationship, event_id
            </small>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import</button>
    </form>

    <div style="margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
        <h3>Contoh Format CSV:</h3>
        <pre>Nama,Telepon,Email,Hubungan,Event ID
Budi Santoso,08123456789,budi@email.com,Teman,1
Siti Nurhaliza,08234567890,siti@email.com,Keluarga,1</pre>
    </div>
</div>

<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file'])) {
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 5) {
                $name = $conn->real_escape_string($row[0]);
                $phone = $conn->real_escape_string($row[1]);
                $email = $conn->real_escape_string($row[2]);
                $relationship = $conn->real_escape_string($row[3]);
                $event_id = intval($row[4]);
                $token = generateToken();

                $query = "INSERT INTO guests (event_id, name, phone, email, relationship, token)
                         VALUES ($event_id, '$name', '$phone', '$email', '$relationship', '$token')";
                
                if ($conn->query($query)) {
                    $count++;
                }
            }
        }

        fclose($handle);
        echo "<div class='alert alert-success' style='margin-top: 20px;'><i class='fas fa-check'></i> $count tamu berhasil diimport</div>";
    }
}

include '../../includes/admin-footer.php'; ?>
