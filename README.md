# SmartNet - Sistem Informasi Monitoring Jaringan Laboratorium Berbasis Web

## 📌 Deskripsi

SmartNet merupakan sistem informasi berbasis web yang digunakan untuk memonitor perangkat jaringan laboratorium secara real-time menggunakan IP Address dan mekanisme ping otomatis.

Sistem ini membantu administrator memantau kondisi perangkat jaringan seperti komputer, router, server, printer, dan perangkat lainnya melalui dashboard interaktif.

SmartNet dikembangkan sebagai proyek mata kuliah Sistem Informasi.

---

## 🎯 Tujuan

Tujuan dari pengembangan SmartNet:

* Mempermudah monitoring perangkat jaringan
* Mengetahui status perangkat secara real-time
* Menyimpan histori perubahan status perangkat
* Menampilkan statistik monitoring dalam bentuk dashboard
* Membantu administrator mendeteksi gangguan lebih cepat

---

## ✨ Fitur

### Dashboard

* Total perangkat
* Total perangkat online
* Total perangkat offline
* Grafik statistik monitoring
* Aktivitas terbaru

### Device Management

* Menambahkan perangkat
* Mengedit perangkat
* Menghapus perangkat
* Menyimpan informasi IP Address dan lokasi

### Monitoring Real-Time

* Monitoring otomatis setiap 10 detik
* Status Online / Offline / Error
* Last check monitoring

### Monitoring Logs

* Menyimpan histori perubahan status perangkat
* Menampilkan waktu perubahan status

### Authentication

* Login Admin
* Logout
* Session management

### Reports

* Riwayat monitoring
* Filter laporan

---

## 🛠 Teknologi dan Tools

### Frontend

* HTML5
* CSS3
* Bootstrap 5
* JavaScript

### Backend

* PHP Native

### Database

* MySQL

### Development Environment

* Laragon

### Library

* Chart.js
* Bootstrap Icons

### Monitoring Method

* Ping Network (IP Address Monitoring)

### Additional Tools

* Advanced IP Scanner
* HeidiSQL

---

## ⚙ Cara Menjalankan Project

### 1. Clone repository

```bash
git clone 
```

### 2. Pindahkan project ke folder:

```bash
laragon/www/
```

### 3. Jalankan Laragon

Pastikan:

* Apache : Running
* MySQL : Running

### 4. Import database

Buka:

HeidiSQL atau phpMyAdmin

Import file:

```bash
database/smartnet.sql
```

### 5. Jalankan project

Buka browser:

```bash
http://smartnet.test
```

atau:

```bash
http://localhost/smartnet
```

---

## 🔄 Cara Kerja Sistem

Perangkat Jaringan
↓
IP Address disimpan ke database
↓
Sistem melakukan ping otomatis setiap 10 detik
↓
Status perangkat diproses
↓
Database diperbarui
↓
Dashboard menampilkan hasil monitoring

---

## 📂 Struktur Database

Tabel utama:

### users

Menyimpan data pengguna/admin

### devices

Menyimpan data perangkat

### monitor_logs

Menyimpan histori perubahan status perangkat

---

## 🚀 Pengembangan Selanjutnya

* Integrasi SolarWinds API
* Notifikasi Email / Telegram / WhatsApp
* Monitoring berbasis cloud
* Monitoring penggunaan CPU dan RAM
* Role user (Admin, Teknisi, Operator)
* Analisis gangguan berbasis AI

---

## 👨‍💻 Author

Al Khawarizmi

Informatics Engineering — UIN Bandung
