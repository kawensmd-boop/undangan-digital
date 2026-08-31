<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect to guest invitation if slug provided
if (isset($_GET['slug'])) {
    $slug = sanitize($_GET['slug']);
    $token = isset($_GET['token']) ? sanitize($_GET['token']) : '';
    $url = APP_URL . '/pages/invitation.php?slug=' . $slug;
    if ($token) {
        $url .= '&token=' . $token;
    }
    redirect($url);
}

// If admin logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(APP_URL . '/admin/dashboard.php');
}

?>
<?php include 'includes/header.php'; ?>

<style>
    .homepage {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        color: white;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .homepage::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        z-index: 1;
    }

    .homepage-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 600px;
        padding: 20px;
        animation: fadeIn 1s ease;
    }

    .homepage-content h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .homepage-content p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.95;
        line-height: 1.6;
    }

    .homepage-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .homepage-buttons a {
        padding: 14px 40px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid white;
        display: inline-block;
        font-size: 1.1rem;
    }

    .homepage-buttons .btn-primary {
        background: white;
        color: var(--accent-color);
    }

    .homepage-buttons .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .homepage-buttons .btn-secondary {
        background: transparent;
        color: white;
    }

    .homepage-buttons .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-3px);
    }

    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 60px;
        padding: 60px 20px;
        background: white;
    }

    .feature {
        text-align: center;
        padding: 30px 20px;
    }

    .feature i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .feature h3 {
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .feature p {
        color: var(--text-light);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .homepage-content h1 {
            font-size: 2rem;
        }

        .homepage-content p {
            font-size: 1rem;
        }

        .homepage-buttons {
            flex-direction: column;
        }

        .homepage-buttons a {
            width: 100%;
        }
    }
</style>

<div class="homepage">
    <div class="homepage-content">
        <h1><i class="fas fa-gift" style="margin-right: 10px;"></i> Undangan Digital</h1>
        <p>Platform lengkap untuk membuat undangan pernikahan digital yang elegan dan interaktif dengan fitur RSVP, guest book, dan QR code check-in.</p>
        <div class="homepage-buttons">
            <a href="admin/login.php" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login Admin
            </a>
            <a href="#features" class="btn-secondary">
                <i class="fas fa-arrow-down"></i> Pelajari Lebih Lanjut
            </a>
        </div>
    </div>
</div>

<div id="features" class="features">
    <div class="feature">
        <i class="fas fa-envelope"></i>
        <h3>Undangan Elegan</h3>
        <p>Desain modern dan responsif yang dapat dikustomisasi sesuai tema acara Anda.</p>
    </div>
    <div class="feature">
        <i class="fas fa-users"></i>
        <h3>Manajemen Tamu</h3>
        <p>Kelola data tamu, konfirmasi kehadiran, dan tracking check-in dengan mudah.</p>
    </div>
    <div class="feature">
        <i class="fas fa-comments"></i>
        <h3>Buku Tamu Digital</h3>
        <p>Izinkan tamu meninggalkan ucapan dan doa untuk pengantin secara online.</p>
    </div>
    <div class="feature">
        <i class="fas fa-qrcode"></i>
        <h3>QR Code Check-in</h3>
        <p>Scanning QR code untuk verifikasi kehadiran tamu secara real-time.</p>
    </div>
    <div class="feature">
        <i class="fas fa-chart-pie"></i>
        <h3>Dashboard Analytics</h3>
        <p>Pantau statistik RSVP, check-in, dan data tamu dalam satu dashboard.</p>
    </div>
    <div class="feature">
        <i class="fas fa-mobile-alt"></i>
        <h3>Mobile Responsive</h3>
        <p>Akses dari perangkat apapun dengan tampilan yang sempurna di semua ukuran layar.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
