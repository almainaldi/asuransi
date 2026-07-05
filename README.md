📋 Prasyarat Sistem (Prerequisites)
Pastikan perangkat Anda sudah terinstal:

PHP (versi 7.4 - 8.2)

Composer (Package Manager PHP)

Node.js & NPM (versi terbaru kompatibel)

XAMPP / MySQL Server

Persiapan
Sebelum memulai tutorial, pastikan sudah memiliki web server local seperti XAMPP. Pastikan juga sudah memiliki Composer sebagai package manager PHP dan juga npm (Node Package Manager) yaitu package manager berbasis JavaScript. 

Pastikan XAMPP dapat berjalan, terutama pada modul Apache dan MySQL. Silahkan masuk ke dashboard phpmyadmin dan buat database baru. Pada tutorial ini akan menggunakan nama database toko.

Perintah ini akan membuat app key baru sekaligus membuat tabel customers dan transactions secara otomatis:

- ![#f03c15](php artisan key:generate) `#f03c15`
- ![#f03c15](php artisan migrate) `#f03c15`

Install & Jalankan Compiler Frontend (Vite)

- ![#c5f015](npm install) `#c5f015`
- ![#c5f015](npm run dev) `#c5f015`

Biarkan jendela terminal yang menjalankan perintah npm run dev ini tetap terbuka selama aplikasi diakses.
Buka jendela CMD/Terminal baru, masuk ke folder proyek lagi, lalu ketik:

- ![#1589F0](php artisan serve) `#1589F0`

Aplikasi sekarang 100% siap digunakan! Buka browser dan akses:

🔗 http://127.0.0.1:8000
