<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = 'Beranda';

// Get event slug from URL
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : null;

if (!$slug) {
    die('Event tidak ditemukan');
}

// Get event details
$event = $conn->query("SELECT * FROM events WHERE slug = '$slug' AND is_active = 1")->fetch_assoc();

if (!$event) {
    die('Event tidak ditemukan');
}

$event_id = $event['id'];

// Get guest info if token exists
$guest = null;
if (isset($_GET['token'])) {
    $token = sanitize($_GET['token']);
    $guest = $conn->query("SELECT * FROM guests WHERE token = '$token' AND event_id = $event_id")->fetch_assoc();
}

// Get photos
$photos = $conn->query("SELECT * FROM photos WHERE event_id = $event_id ORDER BY order_position ASC")->fetch_all(MYSQLI_ASSOC);

// Get media
$media = $conn->query("SELECT * FROM media WHERE event_id = $event_id AND is_featured = 1 LIMIT 1")->fetch_assoc();

// Get guest book entries
$guestbook = $conn->query("SELECT * FROM guestbook_entries WHERE event_id = $event_id AND status = 'approved' ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Get RSVP stats
$rsvp_stats = $conn->query("SELECT 
    COUNT(CASE WHEN status = 'confirmed') as confirmed,
    COUNT(CASE WHEN status = 'pending') as pending,
    COUNT(CASE WHEN status = 'rejected') as rejected,
    COUNT(*) as total
    FROM guests WHERE event_id = $event_id")->fetch_assoc();

?>
<?php include 'includes/header.php'; ?>

<style>
    .invitation-hero {
        background: linear-gradient(135deg, <?php echo $event['background_color']; ?> 0%, #ffffff 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .invitation-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M20,80 Q40,40 60,80 T100,80" stroke="%23C9A961" stroke-width="0.5" fill="none" opacity="0.1"/></pattern></defs><rect width="1200" height="800" fill="url(%23pattern)"/></svg>');
        opacity: 0.3;
    }

    .invitation-container {
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        z-index: 2;
        animation: fadeIn 1s ease;
    }

    .couple-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideInDown 0.8s ease;
    }

    .invitation-header {
        margin-bottom: 30px;
    }

    .invitation-header .label {
        font-size: 14px;
        letter-spacing: 2px;
        color: <?php echo $event['accent_color']; ?>;
        text-transform: uppercase;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .couple-names {
        font-size: 3.5rem;
        font-family: var(--font-heading);
        color: <?php echo $event['text_color']; ?>;
        margin: 20px 0;
        line-height: 1.1;
    }

    .couple-names .ampersand {
        color: <?php echo $event['accent_color']; ?>;
        font-size: 2.5rem;
        margin: 0 10px;
    }

    .divider {
        width: 60px;
        height: 2px;
        background: linear-gradient(to right, transparent, <?php echo $event['accent_color']; ?>, transparent);
        margin: 20px auto;
    }

    .event-date {
        font-size: 1.2rem;
        color: <?php echo $event['text_color']; ?>;
        margin-bottom: 30px;
        font-weight: 500;
    }

    .btn-open {
        background: linear-gradient(135deg, <?php echo $event['accent_color']; ?> 0%, #000 100%);
        color: white;
        padding: 16px 48px;
        font-size: 1.1rem;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        display: inline-block;
        text-decoration: none;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .btn-open:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    }

    .section {
        padding: 60px 20px;
        background: white;
        border-top: 1px solid var(--border-light);
    }

    .section-title {
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 40px;
        color: <?php echo $event['text_color']; ?>;
    }

    .countdown {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 40px;
    }

    .countdown-item {
        background: linear-gradient(135deg, <?php echo $event['background_color']; ?> 0%, #fff 100%);
        padding: 30px 20px;
        border-radius: 12px;
        text-align: center;
        border: 2px solid var(--border-light);
    }

    .countdown-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: <?php echo $event['accent_color']; ?>;
        font-family: var(--font-heading);
    }

    .countdown-label {
        font-size: 0.9rem;
        color: var(--text-light);
        margin-top: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .event-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .event-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-left: 4px solid <?php echo $event['accent_color']; ?>;
        transition: all 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .event-card-icon {
        font-size: 2rem;
        color: <?php echo $event['accent_color']; ?>;
        margin-bottom: 15px;
    }

    .event-card-title {
        font-size: 1.3rem;
        margin-bottom: 10px;
        color: <?php echo $event['text_color']; ?>;
    }

    .event-card-time {
        font-size: 1rem;
        color: var(--text-light);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .event-card-location {
        font-size: 0.95rem;
        color: var(--text-light);
        margin-bottom: 15px;
    }

    .gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .gallery-item {
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .guestbook-section {
        max-width: 600px;
        margin: 0 auto;
    }

    .guestbook-entry {
        background: #f9f9f9;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 12px;
        border-left: 4px solid <?php echo $event['accent_color']; ?>;
    }

    .guestbook-author {
        font-weight: 600;
        color: <?php echo $event['text_color']; ?>;
        margin-bottom: 5px;
    }

    .guestbook-time {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 10px;
    }

    .guestbook-message {
        color: var(--text-light);
        line-height: 1.6;
    }

    .rsvp-form {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        margin: 0 auto 40px;
    }

    .form-group label {
        color: <?php echo $event['text_color']; ?>;
        font-weight: 600;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        border: 2px solid var(--border-light);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: <?php echo $event['accent_color']; ?>;
        box-shadow: 0 0 0 3px rgba(<?php echo implode(',', array_slice(sscanf($event['accent_color'], '#%02x%02x%02x'), 0, 3)); ?>, 0.1);
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, <?php echo $event['accent_color']; ?> 0%, #000 100%);
        color: white;
        padding: 14px;
        font-size: 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .couple-names {
            font-size: 2rem;
        }

        .couple-names .ampersand {
            font-size: 1.5rem;
            margin: 0 5px;
        }

        .countdown {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .countdown-number {
            font-size: 1.8rem;
        }

        .gallery {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="invitation-hero">
    <div class="invitation-container">
        <?php if ($event['couple_image']): ?>
            <img src="<?php echo APP_URL; ?>/uploads/<?php echo $event['couple_image']; ?>" alt="<?php echo $event['couple_name_1']; ?> & <?php echo $event['couple_name_2']; ?>" class="couple-image">
        <?php endif; ?>

        <div class="invitation-header">
            <div class="label">Undangan Pernikahan</div>
            <div class="divider"></div>
            <div class="couple-names">
                <?php echo $event['couple_name_1']; ?>
                <span class="ampersand">&</span>
                <?php echo $event['couple_name_2']; ?>
            </div>
            <div class="divider"></div>
        </div>

        <div class="event-date">
            <?php echo formatDateID($event['event_date']); ?>
        </div>

        <?php if ($guest): ?>
            <div style="margin-bottom: 20px; font-size: 1.1rem;">
                <p>Kepada: <strong><?php echo $guest['name']; ?></strong></p>
            </div>
        <?php endif; ?>

        <button class="btn-open" onclick="document.getElementById('invitation-content').scrollIntoView({behavior:'smooth'})">
            <i class="fas fa-envelope"></i> Buka Undangan
        </button>
    </div>
</div>

<div id="invitation-content">
    <!-- Countdown Section -->
    <div class="section">
        <h2 class="section-title">Hitung Mundur</h2>
        <div id="countdown" class="countdown"></div>
    </div>

    <!-- Event Info Section -->
    <div class="section">
        <h2 class="section-title">Acara</h2>
        <div class="event-info">
            <?php if ($event['ceremony_time']): ?>
            <div class="event-card">
                <div class="event-card-icon"><i class="fas fa-mosque"></i></div>
                <div class="event-card-title">Akad Nikah</div>
                <div class="event-card-time"><i class="fas fa-clock"></i> <?php echo formatTime($event['ceremony_time']); ?> WIB</div>
                <div class="event-card-location"><i class="fas fa-map-marker-alt"></i> <?php echo $event['ceremony_location']; ?></div>
                <a href="<?php echo $event['event_maps_url']; ?>" target="_blank" class="btn btn-sm btn-outline">Lihat Lokasi</a>
            </div>
            <?php endif; ?>

            <?php if ($event['reception_time']): ?>
            <div class="event-card">
                <div class="event-card-icon"><i class="fas fa-utensils"></i></div>
                <div class="event-card-title">Resepsi</div>
                <div class="event-card-time"><i class="fas fa-clock"></i> <?php echo formatTime($event['reception_time']); ?> WIB</div>
                <div class="event-card-location"><i class="fas fa-map-marker-alt"></i> <?php echo $event['reception_location']; ?></div>
                <a href="<?php echo $event['event_maps_url']; ?>" target="_blank" class="btn btn-sm btn-outline">Lihat Lokasi</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Gallery Section -->
    <?php if (!empty($photos)): ?>
    <div class="section">
        <h2 class="section-title">Galeri Foto</h2>
        <div class="gallery">
            <?php foreach ($photos as $photo): ?>
            <div class="gallery-item">
                <img src="<?php echo APP_URL; ?>/uploads/<?php echo $photo['image_path']; ?>" alt="<?php echo $photo['caption']; ?>" data-src="<?php echo APP_URL; ?>/uploads/<?php echo $photo['image_path']; ?>">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- RSVP Section -->
    <div class="section">
        <h2 class="section-title">Konfirmasi Kehadiran</h2>
        <div class="rsvp-form">
            <?php if ($guest): ?>
            <form id="rsvpForm" method="POST" action="<?php echo APP_URL; ?>/api/rsvp.php">
                <input type="hidden" name="guest_id" value="<?php echo $guest['id']; ?>">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" value="<?php echo $guest['name']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Apakah Anda akan hadir?</label>
                    <div style="display: flex; gap: 20px; margin-top: 10px;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="status" value="confirmed" <?php echo $guest['status'] == 'confirmed' ? 'checked' : ''; ?> required>
                            <span style="margin-left: 8px;">Ya, saya akan hadir</span>
                        </label>
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="status" value="rejected" <?php echo $guest['status'] == 'rejected' ? 'checked' : ''; ?>>
                            <span style="margin-left: 8px;">Maaf, tidak bisa hadir</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jumlah Tamu</label>
                    <input type="number" name="num_guests" value="<?php echo $guest['num_guests']; ?>" min="1" max="10" required>
                </div>

                <div class="form-group">
                    <label>Catatan Khusus / Alergi</label>
                    <textarea name="dietary_notes" placeholder="Tuliskan catatan apapun (opsional)"><?php echo $guest['dietary_notes']; ?></textarea>
                </div>

                <button type="submit" class="btn-submit"><i class="fas fa-check"></i> Kirim Konfirmasi</button>
            </form>
            <?php else: ?>
            <p style="color: var(--text-light);">Silakan gunakan link undangan pribadi Anda untuk mengkonfirmasi kehadiran.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Guest Book Section -->
    <div class="section">
        <h2 class="section-title">Buku Tamu</h2>
        <div class="guestbook-section">
            <div style="margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px; text-align: center;">Tulis Ucapan</h3>
                <form id="guestbookForm" method="POST" action="<?php echo APP_URL; ?>/api/guest-book.php">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <?php if ($guest): ?>
                        <input type="hidden" name="guest_id" value="<?php echo $guest['id']; ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <input type="text" name="guest_name" placeholder="Nama Anda" required>
                    </div>

                    <div class="form-group">
                        <textarea name="message" placeholder="Tulis ucapan atau doa untuk pengantin..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Kirim Ucapan</button>
                </form>
            </div>

            <h3 style="margin-bottom: 20px; text-align: center; margin-top: 30px;">Ucapan Terbaru</h3>
            <?php if (!empty($guestbook)): ?>
                <?php foreach ($guestbook as $entry): ?>
                <div class="guestbook-entry">
                    <div class="guestbook-author"><?php echo $entry['guest_name']; ?></div>
                    <div class="guestbook-time"><?php echo timeAgo($entry['created_at']); ?></div>
                    <div class="guestbook-message">"<?php echo $entry['message']; ?>"</div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light);">Belum ada ucapan. Jadilah yang pertama!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Countdown timer
    startCountdown('countdown', '<?php echo $event['event_date']; ?>');

    // RSVP Form
    document.getElementById('rsvpForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        fetch('<?php echo APP_URL; ?>/api/rsvp.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Terima kasih, konfirmasi Anda telah dikirim!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan', 'danger');
        });
    });

    // Guest Book Form
    document.getElementById('guestbookForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        fetch('<?php echo APP_URL; ?>/api/guest-book.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Ucapan Anda telah dikirim!', 'success');
                document.getElementById('guestbookForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan', 'danger');
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
