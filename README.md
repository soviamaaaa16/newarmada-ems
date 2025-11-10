
# New Armada Enterprise Management System (EMS)

New Armada Enterprise Management System (EMS) adalah platform berbasis web yang dibangun menggunakan PHP CodeIgniter 4 (CI4) dan MySQL untuk membantu manajemen bisnis dalam mengelola berbagai fungsi operasional secara efisien. Sistem ini dirancang untuk menangani proses manajemen file, inventory, keuangan, dan analisis data perusahaan.

## Fitur Utama

1. **Manajemen Pengguna (User Management)**
   - Sistem memungkinkan pengelolaan pengguna dengan hak akses yang terkontrol.
   - Role-based access control (RBAC) untuk menentukan hak akses pengguna sesuai dengan peran mereka.

2. **Manajemen File**
   - Mengunggah, menyimpan, dan berbagi file secara online.
   - Sistem memiliki struktur folder untuk pengelolaan file yang lebih rapi.

3. **Manajemen Keuangan**
   - Modul untuk mencatat transaksi keuangan, pembuatan laporan laba/rugi, dan neraca.
   - Fitur pelaporan keuangan yang dapat diakses sesuai kebutuhan.

4. **Manajemen Inventaris**
   - Pemantauan stok barang, pengaturan level persediaan, dan pembaruan inventaris otomatis.
   - Pencatatan pemasukan dan pengeluaran barang.

5. **Analisis Data**
   - Pengguna dapat melakukan analisis data untuk mengambil keputusan berbasis data dengan menggunakan laporan yang tersedia.

6. **Pencarian dan Filter Data**
   - Fitur pencarian untuk menemukan data secara cepat dengan menggunakan filter yang disesuaikan.

7. **Keamanan dan Autentikasi**
   - Sistem keamanan menggunakan enkripsi untuk menjaga data tetap aman.
   - Login dan autentikasi berbasis session dengan fitur logout otomatis.

## Teknologi yang Digunakan

- **Backend**: PHP (CodeIgniter 4)
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript (jQuery, Bootstrap)
- **Keamanan**: CSRF Protection, Enkripsi Password dengan `password-hash`
- **Autentikasi**: Session Management, Role-Based Access Control (RBAC)
- **File Storage**: Sistem penyimpanan file berbasis folder di server.

## Instalasi

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer untuk manajemen dependensi PHP
- Server Web (Apache/Nginx)

### Langkah-langkah Instalasi

1. **Clone Repositori**

   ```
   git clone https://github.com/username/new-armada-ems.git
   ```

2. **Instalasi Dependensi**

   Setelah meng-clone repositori, jalankan Composer untuk menginstal dependensi yang dibutuhkan:

   ```
   cd new-armada-ems
   composer install
   ```

3. **Konfigurasi Database**

   Sesuaikan pengaturan koneksi database di file `.env` dengan informasi MySQL Anda:

   ```
   database.default.hostname = localhost
   database.default.database = ems_db
   database.default.username = root
   database.default.password = password
   ```

4. **Jalankan Migrations**

   Jalankan migrations untuk menyiapkan database:

   ```
   php spark migrate
   ```

5. **Menjalankan Aplikasi**

   Jalankan aplikasi menggunakan built-in server PHP:

   ```
   php spark serve
   ```

   Aplikasi dapat diakses di `http://localhost:8080`.

## Penggunaan

1. **Mendaftar dan Login**
   - Pengguna baru dapat mendaftar menggunakan email dan password.
   - Pengguna yang sudah terdaftar dapat langsung login.

2. **Manajemen Pengguna**
   - Admin dapat menambahkan, mengedit, atau menghapus pengguna.
   - Mengatur peran pengguna dengan hak akses tertentu.

3. **Mengelola File dan Dokumen**
   - Pengguna dapat mengunggah, mengatur, dan berbagi file melalui sistem.
   - Pengguna dapat mengakses file sesuai dengan izin yang diberikan.

4. **Melakukan Transaksi Keuangan**
   - Sistem memungkinkan pencatatan transaksi keuangan harian, bulanan, atau tahunan.

5. **Melakukan Analisis Data**
   - Laporan yang dihasilkan dapat digunakan untuk analisis lebih lanjut mengenai performa perusahaan.

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, Anda dapat:
1. Fork repositori ini.
2. Buat branch baru untuk fitur atau perbaikan yang Anda kerjakan.
3. Lakukan perubahan dan buat pull request.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
