<?php
/**
 * Application Constants
 * Copy this file to constants.php and update values
 */

// Application
define('APP_NAME', 'Undangan Digital');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/undangan-digital');
define('APP_TIMEZONE', 'Asia/Jakarta');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm']);

// Session
define('SESSION_LIFETIME', 86400); // 24 hours

// QR Code
define('QR_SIZE', 200);
define('QR_ERROR_CORRECTION', 'M');

// Email Settings (untuk integrasi email di masa depan)
define('MAIL_FROM', 'noreply@undangandigital.com');
define('MAIL_FROM_NAME', 'Undangan Digital');

// Security
define('PASSWORD_MIN_LENGTH', 6);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

?>
