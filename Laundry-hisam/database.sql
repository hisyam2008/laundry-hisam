
CREATE TABLE IF NOT EXISTS tb_outlet (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nama   VARCHAR(100) NOT NULL,
    alamat TEXT,
    tlp    VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS user (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nama      VARCHAR(100) NOT NULL,
    username  VARCHAR(50) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,
    role      ENUM('admin','owner','kasir') NOT NULL DEFAULT 'kasir',
    id_outlet INT DEFAULT NULL,
    FOREIGN KEY (id_outlet) REFERENCES tb_outlet(id) ON DELETE SET NULL
);

-- Default admin: username=admin, password=admin123
INSERT INTO user (nama, username, password, role) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

CREATE TABLE IF NOT EXISTS tb_paket (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    harga      INT NOT NULL,
    satuan     ENUM('kg','pcs') NOT NULL DEFAULT 'kg',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_transaksi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kode       VARCHAR(20) NOT NULL UNIQUE,
    id_member  INT DEFAULT NULL,
    id_outlet  INT DEFAULT NULL,
    id_user    INT DEFAULT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp      VARCHAR(20) NOT NULL,
    tgl_masuk  DATE NOT NULL,
    tgl_selesai DATE DEFAULT NULL,
    status     ENUM('antri','proses','selesai','diambil') NOT NULL DEFAULT 'antri',
    catatan    TEXT,
    total      INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_outlet) REFERENCES tb_outlet(id) ON DELETE SET NULL,
    FOREIGN KEY (id_user)   REFERENCES user(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS tb_detail_transaksi (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_paket     INT NOT NULL,
    qty          DECIMAL(7,2) NOT NULL,
    harga        INT NOT NULL,
    subtotal     INT NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES tb_transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (id_paket)     REFERENCES tb_paket(id) ON DELETE RESTRICT
);
