-- Cipta Pangkalan Data (Database) Baru
CREATE DATABASE IF NOT EXISTS uitm_upk_db;
USE uitm_upk_db;

-- 1. Jadual untuk Berita & Pengumuman (News)
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    news_type ENUM('Acara', 'Pengumuman', 'Berita') NOT NULL,
    date_posted DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan Data Mock untuk Berita & Pengumuman
INSERT INTO news (title, news_type, date_posted) VALUES
('Audit Dalaman iQMS Sesi Jun 2025 – Kampus Segamat', 'Acara', '2025-06-18'),
('Semakan Semula Objektif Kualiti 2025 – Mesyuarat Jawatankuasa', 'Pengumuman', '2025-06-15'),
('UiTM Cawangan Johor Berjaya Pertahankan Akreditasi MQA 2025', 'Berita', '2025-06-10'),
('Bengkel Penambahbaikan Proses Kualiti untuk Staf Akademik', 'Acara', '2025-06-05'),
('Pendaftaran Kursus Kesedaran Kualiti Siri 3/2025 Dibuka', 'Pengumuman', '2025-06-01');

-- 2. Jadual untuk Acara Akan Datang (Events)
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan Data Mock untuk Acara Akan Datang
INSERT INTO events (title, location, event_date) VALUES
('Audit iQMS Sesi 2/2025 – Kampus Pasir Gudang', 'Bilik Mesyuarat Utama, KPG', '2025-06-22'),
('Mesyuarat Jawatankuasa Kualiti Q2 2025', 'Dewan Seminar, Kampus Segamat', '2025-06-28'),
('Bengkel Penulisan Laporan Kualiti Staf', 'Bilik Latihan ICT, KS', '2025-07-05');

-- 3. Jadual untuk Ahli Jawatankuasa (Team Members)
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    initials VARCHAR(5) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    campus VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan Data Rasmi untuk Ahli Jawatankuasa
INSERT INTO team_members (initials, full_name, role, campus, email, phone_number) VALUES
('HH', 'Cik Henny Hazliza binti Mohd Tahir', 'Ketua Unit Kualiti', 'Kampus Segamat', 'henny030@uitm.edu.my', '07-935 2271'),
('AR', 'Pn Aida Rohani binti Samsudin', 'Koordinator Akreditasi', 'Kampus Segamat', 'aidar551@uitm.edu.my', '07-935 2383'),
('SN', 'Cik Siti Nurul ''Ain binti Zaiton', 'Penyelaras Kualiti', 'Kampus Pasir Gudang', 'siti6687@uitm.edu.my', '07-381 8534');

-- 4. Jadual untuk Objektif Kualiti (Objectives)
CREATE TABLE IF NOT EXISTS objectives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    letter CHAR(1) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan Data Rasmi untuk Objektif Kualiti
INSERT INTO objectives (letter, description) VALUES
('A', 'Memastikan 100% kurikulum melalui proses semakan sekurang-kurangnya sekali dalam tempoh 3–5 tahun'),
('B', 'Mencapai 80% pelajar sepenuh masa tamat pengajian dalam tempoh yang ditetapkan setiap semester'),
('C', 'Mencapai 25% pelajar sepenuh masa tamat pengajian dengan HPNG 3.5 dan ke atas setiap semester'),
('D', 'Mencapai kadar GE melebihi 80% Sarjana Muda, 95% Diploma dan 5% Bekerja Sendiri'),
('E', 'Mencapai 250 penerbitan berindeks dan 5 penerbitan berindeks Q1 dalam tahun semasa'),
('F', 'Mencapai jumlah nilai geran penyelidikan RM1.6 juta dalam tahun semasa'),
('G', 'Mengkomersilkan 1 produk hasil penyelidikan menjelang 2025'),
('H', 'Mencapai 50% pensyarah berkelayakan PhD menjelang 2025');

-- 5. Jadual untuk Tugas & Tanggungjawab (Responsibilities)
CREATE TABLE IF NOT EXISTS responsibilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    task TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan Data Rasmi untuk Tugas & Tanggungjawab
INSERT INTO responsibilities (category, task) VALUES
('Ketua Unit Kualiti', 'Merancang penganjuran aktiviti berkaitan kualiti akademik dan bukan akademik peringkat cawangan.'),
('Ketua Unit Kualiti', 'Mengurus dan melaksanakan audit (iQMS, audit pematuhan swaakreditasi, audit dalaman).'),
('Ketua Unit Kualiti', 'Menyelaras pengurusan kualiti menyeluruh (TQM) peringkat cawangan Johor.'),
('Ketua Unit Kualiti', 'Memastikan standard kualiti dipatuhi selaras dengan MQA dan UiTM.'),
('Koordinator Akreditasi', 'Membantu menyelaras urusan akreditasi program akademik peringkat cawangan.'),
('Koordinator Akreditasi', 'Mengurus pangkalan data akreditasi dan fail-fail kualiti.'),
('Koordinator Akreditasi', 'Memberi khidmat nasihat kepada fakulti berkaitan penyediaan dokumen akreditasi.'),
('Koordinator Akreditasi', 'Memastikan pematuhan kepada standard COPPA dan COPIA.');

-- 6. Jadual untuk Pengguna Sistem (Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Jadual untuk Pengurusan Dokumen ISO (Compliance)
CREATE TABLE IF NOT EXISTS iso_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doc_code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    status ENUM('Draf', 'Diluluskan', 'Obsolete') DEFAULT 'Draf',
    category ENUM('SOP', 'Manual Kualiti', 'Garis Panduan', 'Lain-lain') DEFAULT 'SOP',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 8. Jadual untuk KPI & Transformasi (Performance)
CREATE TABLE IF NOT EXISTS kpi_transformasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    quarter ENUM('Q1', 'Q2', 'Q3', 'Q4') NOT NULL,
    achievement DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Masukkan Data Mock untuk Admin ('admin', password 'admin123' bcrypt hash)
INSERT IGNORE INTO users (username, password) VALUES
('admin', '$2y$10$QOInVz16jKROdGntFjJ.xOP60S0e8q0.Vb.5uXGgQh7oV/q8D/xH2');

-- Masukkan Data Mock untuk ISO
INSERT INTO iso_documents (doc_code, title, file_path, status, category) VALUES
('ISO 9001:2015', 'Manual Kualiti UiTM', 'manual_kualiti.pdf', 'Diluluskan', 'Manual Kualiti'),
('SOP-UPK-01', 'Prosedur Audit Dalaman', 'sop_audit.pdf', 'Draf', 'SOP');

-- Masukkan Data Mock untuk KPI
INSERT INTO kpi_transformasi (title, year, quarter, achievement) VALUES
('Bilangan Kursus yang Diakreditasi', 2025, 'Q1', 85.50),
('Kadar Kebolehpasaran Graduan (GE)', 2025, 'Q1', 92.00);
