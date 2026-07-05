# Dokumentasi Proyek Asuransi

Selamat datang di repositori proyek Asuransi. Dokumen ini berisi panduan instalasi, penjelasan arsitektur, dan akun demo untuk kebutuhan testing.
### 📋 Prasyarat Sistem (Prerequisites)
Pastikan perangkat Anda sudah terinstal:
* **PHP** (versi 7.4 - 8.2)
* **Composer** (Package Manager PHP)
* **Node.js & NPM** (versi terbaru yang kompatibel)
* **XAMPP / MySQL Server**

Persiapan
Sebelum memulai tutorial, pastikan sudah memiliki web server local seperti XAMPP. Pastikan juga sudah memiliki Composer sebagai package manager PHP dan juga npm (Node Package Manager) yaitu package manager berbasis JavaScript. 

Pastikan XAMPP dapat berjalan, terutama pada modul Apache dan MySQL. Silahkan masuk ke dashboard phpmyadmin dan buat database baru. Pada tutorial ini akan menggunakan nama database toko.

Perintah ini akan membuat app key baru sekaligus membuat tabel claims dan claims_log secara otomatis:

- ![#f03c15](php artisan key:generate) `#f03c15`
- ![#f03c15](php artisan migrate) `#f03c15`

Install & Jalankan Compiler Frontend (Vue & Vite)

- ![#c5f015](npm install) `#c5f015`
- ![#c5f015](npm run dev) `#c5f015`

Biarkan jendela terminal yang menjalankan perintah npm run dev ini tetap terbuka selama aplikasi diakses.
Buka jendela CMD/Terminal baru, masuk ke folder proyek lagi, lalu ketik:

- ![#1589F0](php artisan serve) `#1589F0`

Aplikasi sekarang 100% siap digunakan! Buka browser dan akses:

🔗 http://127.0.0.1:8000
=======

Arsitektur Sistem dan Workflow
Arsitektur Sistem
Proyek ini dibangun menggunakan Laravel Framework (Backend) dan compiler frontend Vite dengan pola arsitektur MVC (Model-View-Controller).

Model: Mengelola struktur data dan hubungan antar-tabel (seperti customers, transactions, dan data pengguna).

View: Menggunakan Blade templating (atau framework JS jika ada) untuk menampilkan antarmuka pengguna secara dinamis.

Controller: Menangani logika bisnis, memproses permintaan dari pengguna, dan menjembatani Model dengan View.

Workflow Aplikasi (Alur Kerja)
Sistem asuransi ini melibatkan proses pengajuan hingga persetujuan dengan alur sebagai berikut:

User (Nasabah): Melakukan registrasi, login, dan membuat pengajuan asuransi atau transaksi baru.

Verifier (Pemeriksa): Menerima notifikasi pengajuan dari User, memeriksa kelengkapan berkas/data, dan memberikan rekomendasi (verifikasi).

Approver (Penentu/Manajer): Menerima data yang telah diverifikasi untuk kemudian mengambil keputusan akhir (menyetujui atau menolak pengajuan).
#########
Akun Demo untuk TestingUntuk memudahkan pengujian fitur berdasarkan hak akses (role) masing-masing, Anda dapat menggunakan akun dummy yang sudah disediakan di bawah ini:
Role Email Password User
Nasabah : user@mail.com 
password
Verifier: verifier@mail.com
password
Approver : approver@mail.com
password


# asuransi
