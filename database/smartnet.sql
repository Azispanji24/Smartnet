CREATE DATABASE IF NOT EXISTS smartnet_db;

USE smartnet_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_device VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) NOT NULL UNIQUE,
    jenis_device ENUM(
        'Router',
        'Switch',
        'Server',
        'Printer',
        'PC',
        'Laptop',
        'Access Point',
        'Other'
    ) NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    status ENUM('ONLINE', 'OFFLINE', 'ERROR') NOT NULL DEFAULT 'OFFLINE',
    last_check DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS monitor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_id INT NOT NULL,
    old_status ENUM('ONLINE', 'OFFLINE', 'ERROR') NOT NULL,
    new_status ENUM('ONLINE', 'OFFLINE', 'ERROR') NOT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_monitor_logs_device
        FOREIGN KEY (device_id) REFERENCES devices(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
