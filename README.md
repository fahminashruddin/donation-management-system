# Sistem Manajemen Donasi (Donation Management System)

Sebuah aplikasi web berbasis PHP dan MySQL yang dirancang untuk mengelola keseluruhan alur donasi. Sistem ini memiliki dashboard admin untuk memantau statistik, mengelola data master (petugas, donatur, penerima), dan program donasi yang sedang berjalan. Proyek ini dibuat untuk mendemonstrasikan kemampuan dalam membangun aplikasi web CRUD (Create, Read, Update, Delete) yang fungsional.

## Tampilan Proyek (Screenshots)

Berikut adalah beberapa tampilan utama dari aplikasi ini.

**1. Dashboard Utama**
![Dashboard Utama](Screenshot%%2025-06-28%%234442.png)

**2. Halaman Manajemen Petugas**
![Manajemen Petugas](Screenshot%%2025-06-29%%000333.png)

## Fitur Utama (Features)

* **Dashboard Statistik:** Menampilkan ringkasan data penting seperti Total Donasi, Jumlah Donatur, dan Total Program Aktif.
* **Manajemen Petugas:** Fitur CRUD penuh untuk mengelola data admin/petugas yang dapat mengakses sistem.
* **Manajemen Program Donasi:** Membuat, mengubah, dan menghapus program donasi yang sedang berjalan.
* **Manajemen Donasi:** Melihat dan memverifikasi data donasi yang masuk dari para donatur.
* **Manajemen Donatur:** Mengelola data para donatur yang telah berpartisipasi.
* **Manajemen Penerima:** Mengelola data calon atau penerima manfaat dari program donasi.
* **Sistem Autentikasi:** Terdapat fitur Login dan Logout untuk keamanan akses.

## Teknologi yang Digunakan (Tech Stack)

* **Backend:** PHP (Native)
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL 
* **Framework CSS (jika ada):** Bootstrap 5 *(sesuaikan jika Anda menggunakan yang lain)*

## Cara Setup dan Instalasi Lokal (Local Installation)

Berikut adalah panduan untuk menjalankan proyek ini di komputer lokal Anda menggunakan XAMPP atau sejenisnya.

**1. Prasyarat**
* Pastikan Anda memiliki web server lokal seperti XAMPP atau WAMP yang sudah terpasang.
* Browser web (misalnya, Google Chrome, Firefox).

**2. Langkah-langkah Instalasi**
   1.  **Clone repository ini:**
       ```bash
       git clone [https://github.com/fahminashruddin/donation-management-system.git](https://github.com/fahminashruddin/donation-management-system.git)
       ```
       Atau unduh file ZIP dan ekstrak ke folder `htdocs` di dalam direktori XAMPP Anda.

   2.  **Buat Database:**
       * Buka phpMyAdmin (`http://localhost/phpmyadmin`).
       * Buat database baru dengan nama `db_donasi` (atau nama lain yang sesuai).

   3.  **Import Database:**
       * Pilih database yang baru Anda buat.
       * Klik tab "Import".
       * Pilih file `.sql` yang ada di dalam folder proyek ini untuk mengimpor struktur tabel dan data awal.

   4.  **Konfigurasi Koneksi Database:**
       * Buka file `koneksi.php` (atau file konfigurasi database Anda) dengan teks editor.
       * Sesuaikan nama host (`$host`), username (`$user`), password (`$pass`), dan nama database (`$dbname`) sesuai dengan pengaturan XAMPP Anda.

   5.  **Jalankan Aplikasi:**
       * Buka browser dan akses alamat `http://localhost/nama-folder-proyek-anda`.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
