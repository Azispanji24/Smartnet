# PROJECT RULES

# PROJECT NAME

SmartNet — Sistem Informasi Monitoring Jaringan Laboratorium Berbasis Web

---

# PROJECT DESCRIPTION

SmartNet adalah sistem informasi berbasis web yang digunakan untuk memonitor perangkat jaringan laboratorium secara real-time menggunakan IP Address dan mekanisme ping otomatis.

Sistem akan:

- Menampilkan status perangkat
- Menampilkan online/offline secara real-time
- Menyimpan riwayat perubahan status
- Menyediakan dashboard statistik
- Membantu administrator memonitor jaringan laboratorium

Tujuan sistem:

1. Mempermudah monitoring jaringan
2. Mengetahui perangkat yang bermasalah
3. Menyimpan histori gangguan
4. Menampilkan analisis monitoring

---

# TECHNOLOGY STACK

Frontend:

- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Bootstrap Icons
- Chart.js

Backend:

- PHP Native

Database:

- MySQL

Environment:

- Laragon

---

# STRICT RULES (WAJIB)

1. Jangan gunakan framework:

DILARANG:

- Laravel
- React
- Vue
- Angular
- NodeJS

2. Jangan mengubah struktur folder

3. Jangan mengubah nama tabel database

4. Jangan menghapus fitur yang sudah dibuat

5. Gunakan struktur modular

6. Gunakan prepared statement

7. Semua query database dipisahkan dari tampilan

8. Semua halaman wajib responsive

9. Gunakan coding yang clean

10. Gunakan komentar seperlunya

11. Jangan menggunakan data dummy permanen

12. Monitoring harus menggunakan data real dari IP Address

13. Jangan mengganti desain UI yang telah dibuat tanpa instruksi

14. Jangan mengubah nama file tanpa instruksi

---

# PROJECT STRUCTURE

smartnet/

│
├── index.php
├── login.php
├── logout.php
├── dashboard.php
├── monitoring.php
├── devices.php
├── add_device.php
├── edit_device.php
├── delete_device.php
├── logs.php
├── reports.php
├── settings.php
├── check_status.php
├── koneksi.php
│
├── assets/
│ ├── css/
│ ├── js/
│ ├── images/
│
├── includes/
│ ├── navbar.php
│ ├── sidebar.php
│ ├── footer.php
│
├── config/
│ └── app.php
│
└── database/
└── smartnet.sql

---

# DATABASE SCHEMA

DATABASE:

smartnet_db

TABLE:

users

Fields:

- id
- username
- password
- role
- created_at

devices

Fields:

- id
- nama_device
- ip_address
- jenis_device
- lokasi
- status
- last_check
- created_at

monitor_logs

Fields:

- id
- device_id
- old_status
- new_status
- waktu

---

# STATUS RULE

Status hanya boleh:

ONLINE
OFFLINE
ERROR

Status color:

ONLINE = Hijau
OFFLINE = Merah
ERROR = Kuning

---

# DEVICE TYPE RULE

Jenis device:

- Router
- Switch
- Server
- Printer
- PC
- Laptop
- Access Point
- Other

---

# REAL MONITORING RULE

IP Address hanya dimasukkan sekali oleh admin.

Sistem otomatis:

1. Mengambil IP dari database

2. Menjalankan:

ping -n 1 [IP]

3. Mengecek respon

Jika terdapat:

TTL=

Status:

ONLINE

Jika timeout:

OFFLINE

Jika gagal:

ERROR

---

# AUTO MONITORING RULE

Monitoring berjalan otomatis setiap:

10 detik

Menggunakan:

JavaScript setInterval()

Flow:

setInterval()
↓
fetch(check_status.php)
↓
PHP ping device
↓
update database
↓
update dashboard

---

# LOG RULE

Log hanya dibuat jika status berubah.

Contoh:

ONLINE → OFFLINE

OFFLINE → ONLINE

ONLINE → ERROR

Jangan menyimpan log jika status tetap sama.

Contoh:

ONLINE → ONLINE

Tidak perlu disimpan.

---

# DASHBOARD FEATURE

Cards:

- Total Devices
- Online Devices
- Offline Devices
- Error Devices

Charts:

- Pie chart status
- Line chart monitoring activity

Tables:

- Recent activity
- Device status

---

# DEVICE MANAGEMENT FEATURE

CRUD:

Create
Read
Update
Delete

Fields:

- Nama device
- IP Address
- Jenis device
- Lokasi

---

# MONITORING PAGE FEATURE

Table:

- Device Name
- IP Address
- Device Type
- Status
- Last Check

Actions:

- Refresh status
- Detail

---

# REPORT FEATURE

- Filter tanggal
- Monitoring history
- Export PDF

---

# LOGIN FEATURE

Admin Login

Session login

Logout

Redirect jika belum login

---

# SECURITY RULE

Gunakan:

password_hash()

password_verify()

Prepared statements

Session validation

---

# UI RULE

Design:

Modern SaaS Dashboard

Style:

- Clean
- Minimal
- Professional
- Rounded corner
- Soft shadow
- Responsive
- Hover animation
- Sidebar
- Top navbar

Color:

Primary:

#2563EB

Secondary:

#FFFFFF

Accent:

#DBEAFE

---

# DEVELOPMENT FLOW

The following steps define internal implementation order only.

DO NOT stop after each step.

DO NOT wait for user confirmation.

Complete the entire workflow continuously.

Implementation sequence:

1. Database
2. Authentication
3. Dashboard
4. CRUD Device
5. Monitoring
6. Logs
7. Reports
8. Testing
9. Final integration

---

# IMPORTANT

Jangan mengubah aturan tanpa instruksi pengguna.
