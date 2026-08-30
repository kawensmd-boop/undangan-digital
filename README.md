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
- 👤 Dashboard dengan statistik acara
- 📧 Manajemen undangan (CRUD)
- 👥 Manajemen tamu dengan detail lengkap
- 📋 Manajemen buku tamu (moderasi komentar)
- 👨‍💼 Manajemen pengguna admin
- 📱 QR Code scanner untuk check-in
- 📊 Laporan RSVP per undangan
- 🎯 Multi-acara support

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
   - Edit file `config/database.php`
   - Sesuaikan kredensial database Anda

4. **Akses Aplikasi**
   - Guest: http://localhost/undangan-digital/
   - Admin: http://localhost/undangan-digital/admin/
   - Default Admin: admin@example.com / password123

## Struktur Folder

```
undangan-digital/
├── admin/              # Panel admin
│   ├── dashboard.php
│   ├── undangan/
│   ├── tamu/
│   ├── buku-tamu/
│   ├── pengguna/
│   └── scan-qr/
├── api/                # API endpoints
│   ├── rsvp.php
│   ├── guest-book.php
│   ├── check-in.php
│   └── qr-code.php
├── assets/             # CSS, JS, Images
│   ├── css/
│   ├── js/
│   ├── img/
│   └── lib/
├── config/             # Konfigurasi
│   ├── database.php
│   └── constants.php
├── database/           # SQL Files
│   └── undangan_digital.sql
├── includes/           # Helper & Functions
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── pages/              # Pages
│   ├── undangan.php
│   ├── rsvp.php
│   └── guest-book.php
└── index.php           # Entry point
```

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: password123

*Pastikan untuk mengubah password setelah login pertama kali!*

## Fitur Animasi & Design

- 🎨 Palet warna modern (emas, krem, hijau)
- ✨ Animasi smooth pada scroll dan hover
- 📹 Video background support
- 🖼️ Image lazy loading
- 🎞️ Photo gallery carousel
- ⏱️ Countdown timer real-time
- 💫 Particle animation
- 🎭 Transition effects

## Performance

- Lightweight (tanpa framework heavy)
- Optimized untuk mobile
- Cache-friendly
- Fast loading time
- Minimal external dependencies

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

MIT License - Bebas untuk digunakan dan dimodifikasi

## Support

Untuk pertanyaan atau issue, silakan hubungi developer.
