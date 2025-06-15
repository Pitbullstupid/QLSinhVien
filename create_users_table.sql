-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS ql_sinhvien CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ql_sinhvien;

-- Bảng: khoa
CREATE TABLE khoa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ten_khoa VARCHAR(100) NOT NULL
);

-- Dữ liệu mẫu cho khoa
INSERT INTO khoa (id, ten_khoa) VALUES
(1, 'Công nghệ thông tin'),
(2, 'Kinh tế'),
(3, 'Du lịch'),
(4, 'Kỹ thuật xây dựng'),
(5, 'Ngôn ngữ học');

-- Bảng: lop
CREATE TABLE lop (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ten_lop VARCHAR(100) NOT NULL,
  khoa_id INT DEFAULT NULL,
  FOREIGN KEY (khoa_id) REFERENCES khoa(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu cho lop
INSERT INTO lop (ten_lop, khoa_id) VALUES
('CNTT01', 1), ('CNTT02', 1), ('CNTT03', 1),
('KT01', 2), ('KT02', 2), ('KT03', 2),
('DL01', 3), ('DL02', 3), ('DL03', 3),
('XD01', 4), ('XD02', 4), ('XD03', 4),
('NN01', 5), ('NN02', 5), ('NN03', 5);

-- Bảng: mon_hoc
CREATE TABLE mon_hoc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_mon VARCHAR(20) NOT NULL UNIQUE,
  ten_mon VARCHAR(100) NOT NULL,
  so_tin_chi INT DEFAULT NULL
);

-- Dữ liệu mẫu cho mon_hoc
INSERT INTO mon_hoc (ma_mon, ten_mon, so_tin_chi) VALUES
('MH001', 'Lập trình PHP', 4),
('MH002', 'Kinh tế vi mô', 2),
('MH003', 'Marketing du lịch', 2),
('MH004', 'Kỹ thuật móng', 3),
('MH005', 'Ngữ pháp tiếng Anh', 2),
('MH006', 'Cơ học kết cấu', 3),
('MH007', 'Văn học Anh', 2),
('MH008', 'Quản lý dự án xây dựng', 3);

-- Bảng: sinh_vien
CREATE TABLE sinh_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_sv VARCHAR(20) NOT NULL UNIQUE,
  ho_ten VARCHAR(100) NOT NULL,
  ngay_sinh DATE DEFAULT NULL,
  gioi_tinh ENUM('Nam','Nữ') DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  so_dien_thoai VARCHAR(20) DEFAULT NULL,
  lop_id INT DEFAULT NULL,
  FOREIGN KEY (lop_id) REFERENCES lop(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu cho sinh_vien
INSERT INTO sinh_vien (ma_sv, ho_ten, ngay_sinh, gioi_tinh, email, so_dien_thoai, lop_id) VALUES
('SV001', 'Nguyễn Văn A', '2003-01-10', 'Nam', 'a@example.com', '0123456789', 1),
('SV002', 'Trần Thị B', '2003-02-15', 'Nữ', 'b@example.com', '0123456790', 2),
('SV003', 'Lê Văn C', '2003-03-20', 'Nam', 'c@example.com', '0123456791', 3),
('SV004', 'Phạm Thị D', '2003-04-25', 'Nữ', 'd@example.com', '0123456792', 4),
('SV005', 'Hoàng Văn E', '2003-05-30', 'Nam', 'e@example.com', '0123456793', 5),
('SV006', 'Ngô Thị F', '2003-06-05', 'Nữ', 'f@example.com', '0123456794', 6),
('SV007', 'Đặng Văn G', '2003-07-10', 'Nam', 'g@example.com', '0123456795', 7),
('SV008', 'Vũ Thị H', '2003-08-15', 'Nữ', 'h@example.com', '0123456796', 8),
('SV009', 'Bùi Văn I', '2003-09-20', 'Nam', 'i@example.com', '0123456797', 9),
('SV010', 'Đỗ Thị K', '2003-10-25', 'Nữ', 'k@example.com', '0123456798', 10);

-- Bảng: diem
CREATE TABLE diem (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sinh_vien_id INT DEFAULT NULL,
  mon_hoc_id INT DEFAULT NULL,
diem_so FLOAT DEFAULT NULL CHECK (diem_so >= 0 AND diem_so <= 10),
  ngay_thi DATE DEFAULT NULL,
  FOREIGN KEY (sinh_vien_id) REFERENCES sinh_vien(id) ON DELETE CASCADE,
  FOREIGN KEY (mon_hoc_id) REFERENCES mon_hoc(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu cho diem
INSERT INTO diem (sinh_vien_id, mon_hoc_id, diem_so, ngay_thi) VALUES
(1, 1, 8.5, '2025-06-01'),
(2, 2, 7.0, '2025-06-02'),
(3, 3, 6.5, '2025-06-03'),
(4, 4, 9.0, '2025-06-04'),
(5, 5, 7.5, '2025-06-05'),
(6, 6, 6.0, '2025-06-06'),
(7, 7, 8.0, '2025-06-07'),
(8, 8, 9.5, '2025-06-08'),
(9, 1, 7.2, '2025-06-09'),
(10, 2, 8.1, '2025-06-10');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (username, password) VALUES 
('admin', 'admin123'); 

