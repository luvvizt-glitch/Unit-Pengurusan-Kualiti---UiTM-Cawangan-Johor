USE uitm_upk_db;

TRUNCATE TABLE news;
TRUNCATE TABLE events;
TRUNCATE TABLE team_members;
TRUNCATE TABLE objectives;
TRUNCATE TABLE responsibilities;

-- 1. Berita & Pengumuman
-- Assuming columns are news_type and date_posted based on DESC news
INSERT INTO news (title, news_type, date_posted) VALUES
('Sesi Libat Urus SPK UiTM bersama PTJ Fokus Kualiti 2026', 'Berita', '2026-03-25'),
('Pengumuman Audit iQMS Siri 1/2026: Semua Unit Bersedia', 'Pengumuman', '2026-04-10'),
('Bengkel Penulisan COPPA 2.0 untuk Program Akademik Baharu', 'Acara', '2026-05-02');

-- 2. Acara Akan Datang
INSERT INTO events (title, day, month, location) VALUES
('Mesyuarat Kajian Semula Pengurusan (MKSP)', 15, 'APR', 'Bilik Mesyuarat Utama'),
('Audit Swaakreditasi MQA', 22, 'MEI', 'Fakulti Pengurusan'),
('Sambutan Hari Kualiti UiTM Johor', 05, 'JUN', 'Dewan Seri Utama');

-- 3. Ahli Jawatankuasa
INSERT INTO team_members (initials, full_name, role, campus, email, phone_number) VALUES
('HH', 'Cik Henny Hazliza binti Mohd Tahir', 'Ketua Unit Kualiti', 'Kampus Segamat', 'henny030@uitm.edu.my', '07-935 2271'),
('AR', 'Pn Aida Rohani binti Samsudin', 'Koordinator Akreditasi', 'Kampus Segamat', 'aidar551@uitm.edu.my', '07-935 2383'),
('SN', 'Cik Siti Nurul ''Ain binti Zaiton', 'Penyelaras Kualiti', 'Kampus Pasir Gudang', 'siti6687@uitm.edu.my', '07-381 8534');

-- 4. Objektif Kualiti
INSERT INTO objectives (letter, description) VALUES
('A', 'Memastikan 100% kurikulum melalui proses semakan sekurang-kurangnya sekali dalam tempoh 3–5 tahun'),
('B', 'Mencapai 80% pelajar sepenuh masa tamat pengajian dalam tempoh yang ditetapkan setiap semester'),
('C', 'Mencapai 25% pelajar sepenuh masa tamat pengajian dengan HPNG 3.5 dan ke atas setiap semester'),
('D', 'Mencapai kadar GE melebihi 80% Sarjana Muda, 95% Diploma dan 5% Bekerja Sendiri'),
('E', 'Mencapai 250 penerbitan berindeks dan 5 penerbitan berindeks Q1 dalam tahun semasa'),
('F', 'Mencapai jumlah nilai geran penyelidikan RM1.6 juta dalam tahun semasa'),
('G', 'Mengkomersilkan 1 produk hasil penyelidikan menjelang 2025'),
('H', 'Mencapai 50% pensyarah berkelayakan PhD menjelang 2025');

-- 5. Tugas & Tanggungjawab
INSERT INTO responsibilities (category, task) VALUES
('Ketua Unit Kualiti', 'Merancang penganjuran aktiviti berkaitan kualiti akademik dan bukan akademik peringkat cawangan.'),
('Ketua Unit Kualiti', 'Mengurus dan melaksanakan audit (iQMS, audit pematuhan swaakreditasi, audit dalaman).'),
('Ketua Unit Kualiti', 'Menyelaras pengurusan kualiti menyeluruh (TQM) peringkat cawangan Johor.'),
('Ketua Unit Kualiti', 'Memastikan standard kualiti dipatuhi selaras dengan MQA dan UiTM.'),
('Koordinator Akreditasi', 'Membantu menyelaras urusan akreditasi program akademik peringkat cawangan.'),
('Koordinator Akreditasi', 'Mengurus pangkalan data akreditasi dan fail-fail kualiti.'),
('Koordinator Akreditasi', 'Memberi khidmat nasihat kepada fakulti berkaitan penyediaan dokumen akreditasi.'),
('Koordinator Akreditasi', 'Memastikan pematuhan kepada standard COPPA dan COPIA.');
