# KlinikCare-Reservasi

"Sebuah sistem reservasi klinik berbasis web yang memungkinkan pasien untuk melakukan booking janji temu dengan dokter secara online, dilengkapi dengan notifikasi WhatsApp otomatis untuk konfirmasi dan pengingat."

## 📋 Deskripsi

KlinikCare-Reservasi adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola sistem reservasi klinik secara efisien. Sistem ini memungkinkan pasien untuk melakukan booking janji temu dengan dokter secara online, sementara admin dapat mengelola data dokter, jadwal praktek, dan reservasi. Fitur notifikasi WhatsApp otomatis memastikan komunikasi yang lancar antara klinik dan pasien.

## ✨ Fitur Utama

### 👤 Untuk Pasien (User)
- **Registrasi dan Login**: Sistem autentikasi yang aman
- **Dashboard**: Melihat ringkasan reservasi aktif dan riwayat
- **Booking Online**: Memilih dokter, tanggal, dan waktu yang tersedia
- **Manajemen Reservasi**: Melihat, membatalkan, atau mengubah reservasi
- **Notifikasi WhatsApp**: Mendapatkan konfirmasi dan pengingat otomatis

### 👨‍💼 Untuk Admin
- **Dashboard Admin**: Ringkasan statistik klinik
- **Manajemen Dokter**: Tambah, edit, hapus data dokter
- **Manajemen Jadwal Praktek**: Atur jadwal kerja dokter
- **Manajemen Reservasi**: Konfirmasi, batalkan, atau selesaikan reservasi
- **Laporan**: Melihat laporan reservasi dan aktivitas klinik

### 🤖 Fitur Otomatis
- **Notifikasi WhatsApp**: Menggunakan Fonnte API untuk notifikasi otomatis
- **Validasi Reservasi**: Mencegah double booking dan konflik jadwal
- **Format Nomor Telepon**: Otomatis memformat nomor Indonesia

## 🛠️ Teknologi yang Digunakan

- **Framework**: Laravel 12
- **Bahasa Pemrograman**: PHP 8.2+
- **Database**: MySQL / SQLite
- **Frontend**: Blade Templates, Bootstrap, Vite
- **API Eksternal**: Fonnte API (WhatsApp)
- **Authentication**: Laravel Sanctum / Built-in Auth

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL atau SQLite
- Web Server (Apache/Nginx)

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/your-username/klinikcare-reservasi.git
cd klinikcare-reservasi
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan pengaturan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klinikcare
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Konfigurasi Fonnte API
Dapatkan token API dari [Fonnte](https://fonnte.com) dan tambahkan ke `.env`:
```env
FONNTE_TOKEN=your_fonnte_token_here
```

### 6. Migrasi Database
```bash
php artisan migrate
php artisan db:seed  # Optional: untuk data dummy
```

### 7. Build Assets
```bash
npm run build
# atau untuk development:
npm run dev
```

### 8. Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📖 Cara Penggunaan

### Setup Awal
1. **Buat Akun Admin**: Setelah migrasi, buat akun admin pertama
2. **Tambah Dokter**: Masuk ke panel admin dan tambahkan data dokter
3. **Atur Jadwal Praktek**: Buat jadwal praktek untuk setiap dokter
4. **Konfigurasi Notifikasi**: Pastikan token Fonnte sudah dikonfigurasi

### Untuk Pasien
1. **Registrasi**: Buat akun baru di halaman register
2. **Login**: Masuk dengan email dan password
3. **Booking**: Pilih dokter dan waktu yang tersedia
4. **Konfirmasi**: Tunggu konfirmasi dari admin via WhatsApp

### Untuk Admin
1. **Login Admin**: Gunakan akun dengan role admin
2. **Kelola Dokter**: Tambah/edit/hapus data dokter
3. **Kelola Jadwal**: Atur jadwal praktek dokter
4. **Kelola Reservasi**: Konfirmasi atau batalkan reservasi pasien

## 📁 Struktur Proyek

```
klinik-reservasi/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller untuk admin
│   │   └── User/           # Controller untuk user
│   ├── Models/             # Model Eloquent
│   ├── Services/           # Service classes (FonnteService)
│   └── Middleware/         # Custom middleware
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   └── js/                # Frontend JavaScript
├── routes/
│   └── web.php            # Route definitions
├── public/                # Public assets
└── config/                # Configuration files
```

## 🔧 Konfigurasi

### Environment Variables
```env
APP_NAME=KlinikCare-Reservasi
APP_ENV=local
APP_KEY=base64_encoded_key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klinikcare
DB_USERNAME=root
DB_PASSWORD=

FONNTE_TOKEN=your_fonnte_api_token
```

### Queue Configuration
Untuk mengirim notifikasi WhatsApp secara asynchronous:
```bash
php artisan queue:work
```

## 🧪 Testing

Jalankan test suite:
```bash
php artisan test
```

## 📊 Database Schema

### Tabel Utama
- **users**: Data pengguna (pasien dan admin)
- **dokters**: Data dokter
- **jadwal_praktek**: Jadwal praktek dokter
- **reservasis**: Data reservasi

### Relasi
- User hasMany Reservasi
- Dokter hasMany JadwalPraktek, hasMany Reservasi
- JadwalPraktek belongsTo Dokter
- Reservasi belongsTo User, belongsTo Dokter

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

Nama Proyek: KlinikCare-Reservasi
Email: your-email@example.com
GitHub: [your-username](https://github.com/your-username)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP Framework
- [Fonnte](https://fonnte.com/) - WhatsApp API Service
- [Bootstrap](https://getbootstrap.com/) - CSS Framework
- [Vite](https://vitejs.dev/) - Build Tool
