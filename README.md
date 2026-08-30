# Aplikasi Undangan Digital

Aplikasi web undangan digital dengan desain modern, animasi, dan fitur lengkap untuk mengelola acara pernikahan atau event lainnya.

## Fitur Utama

### Untuk Tamu Undangan
- ✨ Tampilan undangan yang elegan dan responsif
- ⏱️ Countdown timer hingga acara
- 📸 Gallery foto acara dengan carousel
- ✅ RSVP/Konfirmasi kehadiran
- 💬 Guest book (buku tamu)
- 📱 Responsive design untuk mobile

### Untuk Admin
- 📊 Dashboard dengan statistik acara
- 📧 Manajemen undangan (CRUD)
- 👥 Manajemen tamu dengan detail lengkap
- 📋 Manajemen buku tamu (moderasi komentar)
- 👨‍💼 Manajemen pengguna admin
- 📱 QR Code scanner untuk check-in
- 📈 Laporan RSVP per undangan
- 🎪 Multi-acara support

## Teknologi

- **Backend**: PHP (Native)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Database**: MySQL
- **Server**: Apache (XAMPP)
- **No Dependencies**: Tanpa Composer atau Docker

## Instalasi

### Requirements
- XAMPP (Apache + MySQL + PHP)
- Browser modern (Chrome, Firefox, Safari, Edge)

### Langkah Instalasi

1. **Extract ke folder XAMPP**
   ```
   Extract undangan-digital.zip ke C:\xampp\htdocs\undangan-digital
   ```

2. **Import Database**
   - Buka phpMyAdmin: http://localhost/phpmyadmin
   - Create database baru: `undangan_digital`
   - Import file `database/undangan_digital.sql`

3. **Konfigurasi Database**
   - Copy file `config/database.example.php` ke `config/database.php`
   - Sesuaikan kredensial database Anda

4. **Akses Aplikasi**
   - Guest: http://localhost/undangan-digital/
   - Admin: http://localhost/undangan-digital/admin/login.php
   - Default Admin: admin@example.com / password123

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: password123

*Pastikan untuk mengubah password setelah login pertama kali!*

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

MIT License
