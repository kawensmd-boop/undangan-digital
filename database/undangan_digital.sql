-- Database untuk Aplikasi Undangan Digital

CREATE DATABASE IF NOT EXISTS undangan_digital;
USE undangan_digital;

-- Tabel Users (Admin)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    avatar VARCHAR(255),
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Acara/Event (Undangan)
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    couple_name_1 VARCHAR(100) NOT NULL,
    couple_name_2 VARCHAR(100) NOT NULL,
    couple_image VARCHAR(255),
    description TEXT,
    event_date DATETIME NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_address TEXT NOT NULL,
    event_maps_url VARCHAR(255),
    ceremony_time TIME,
    ceremony_location VARCHAR(255),
    reception_time TIME,
    reception_location VARCHAR(255),
    background_color VARCHAR(7) DEFAULT '#F5E6D3',
    accent_color VARCHAR(7) DEFAULT '#C9A961',
    text_color VARCHAR(7) DEFAULT '#333333',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Tamu
CREATE TABLE guests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    relationship VARCHAR(100),
    token VARCHAR(255) NOT NULL UNIQUE,
    qr_code VARCHAR(255),
    status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending',
    num_guests INT DEFAULT 1,
    dietary_notes TEXT,
    checked_in BOOLEAN DEFAULT FALSE,
    check_in_time TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_token (token),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Buku Tamu (Guest Book)
CREATE TABLE guestbook_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    guest_id INT,
    guest_name VARCHAR(100) NOT NULL,
    guest_image VARCHAR(255),
    message TEXT NOT NULL,
    rating INT DEFAULT 5,
    status ENUM('approved', 'pending', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE SET NULL,
    INDEX idx_event (event_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Photo Gallery
CREATE TABLE photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    order_position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_position (order_position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Media (Video, etc)
CREATE TABLE media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    type ENUM('video', 'image') DEFAULT 'video',
    media_path VARCHAR(255) NOT NULL,
    media_url VARCHAR(500),
    caption VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Check-in Log
CREATE TABLE checkin_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    guest_id INT NOT NULL,
    event_id INT NOT NULL,
    check_in_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    device_info VARCHAR(255),
    ip_address VARCHAR(45),
    FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_guest (guest_id),
    INDEX idx_time (check_in_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Settings
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT,
    setting_key VARCHAR(100) NOT NULL,
    setting_value LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_setting (event_id, setting_key),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@example.com', '$2y$10$YQv8kVWN2NHrIQy0K9h7n.U6Z5Zt5QW5pZq0K9h7n.U6Z5Zt5QW5C', 'admin');

-- Insert sample event
INSERT INTO events (user_id, title, slug, couple_name_1, couple_name_2, description, event_date, event_location, event_address, ceremony_time, ceremony_location, reception_time, reception_location) VALUES 
(1, 'Pernikahan Raka & Nadia', 'raka-nadia-2026', 'Raka Pratama', 'Nadia Salsabila', 'Kami dengan senang hati mengundang Anda...', '2026-11-14 08:00:00', 'Bandung', 'Jl. Masjid Al-Ikhlas, Bandung', '08:00', 'Masjid Al-Ikhlas, Bandung', '11:00', 'Gedung Saparua Ballroom, Bandung');

-- Create indexes for better performance
CREATE INDEX idx_guests_checkin ON guests(event_id, status);
CREATE INDEX idx_guestbook_created ON guestbook_entries(event_id, created_at);
CREATE INDEX idx_events_active ON events(is_active, created_at);
