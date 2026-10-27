# 🚗 Showroom Mobil RESTful API - Tugas Akhir Backend BTNG

Layanan RESTful API mandiri untuk manajemen katalog unit mobil showroom, dibangun menggunakan framework **Laravel 12** dan basis data **MySQL**. API ini dirancang mengikuti kaidah arsitektur RESTful yang rapi, pembungkusan respon standar JSON (API Resource), validasi request semantik, serta dokumentasi interaktif **Scramble** di `/docs/api`.

---

## 📌 Tautan Penting & Live Deployment

- **Dokumentasi API Interaktif (Live /docs/api):** `https://[app-name].cleverapps.io/docs/api`
- **URL Base API Live:** `https://[app-name].cleverapps.io/api`
- **Spesifikasi OpenAPI JSON:** `https://[app-name].cleverapps.io/docs/api.json`
- **Repository GitHub:** `https://github.com/[username]/BTNG-Backend-[NamaLengkap]`

*(Ganti `[app-name]`, `[username]`, dan `[NamaLengkap]` sesuai akun dan aplikasi Clever Cloud & GitHub Anda)*

---

## 🛠️ Teknologi & Stack yang Digunakan

- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL (Lokal via Laragon/XAMPP, Cloud via Aiven / TiDB / Clever Cloud)
- **API Documentation:** Scramble (`dedoc/scramble`) OpenAPI 3.1
- **API Resource:** Laravel `JsonResource` dengan amplop konsisten (`message` & `data`)
- **Testing:** PHPUnit / Laravel Test Suite (15 Test Cases, 100% Pass)
- **Deployment Platform:** Clever Cloud / PaaS

---

## 📊 Struktur Entitas & Atribut Data (Mobil)

Tabel basis data: `mobils`

| Kolom | Tipe Data | Keterangan | Contoh |
| :--- | :--- | :--- | :--- |
| `id` | BigInt (PK, Auto-inc) | ID unik data unit mobil | `1` |
| `nama` | String (255) | Model dan varian mobil | `"Avanza 1.5 G CVT"` |
| `brand` | String (100) | Merk / pabrikan mobil | `"Toyota"` |
| `harga` | Unsigned BigInt | Harga unit mobil (Rupiah) | `275000000` |
| `tahun` | Integer | Tahun perakitan mobil | `2024` |
| `transmisi` | String | Jenis transmisi (`Manual`, `Matic`, `CVT`) | `"CVT"` |
| `bahan_bakar`| String | Jenis BBM (`Bensin`, `Diesel`, `Hybrid`, `Listrik`) | `"Bensin"` |
| `status` | Enum | Ketersediaan unit (`tersedia`, `sold`) | `"tersedia"` |
| `foto` | Text | URL foto unit mobil | `"https://images.unsplash.com/..."` |
| `created_at` | Timestamp | Waktu data dibuat | `2026-10-27T04:45:27Z` |
| `updated_at` | Timestamp | Waktu data diperbarui | `2026-10-27T04:45:27Z` |

---

## 🚀 Panduan Setup & Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Clone Repository
```bash
git clone https://github.com/[username]/BTNG-Backend-[NamaLengkap].git
cd BTNG-Backend-[NamaLengkap]
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database MySQL lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=showroom_mobil
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database `showroom_mobil` sudah dibuat di MySQL/phpMyAdmin)*

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Seeder Database
Menjalankan migrasi tabel dan mengisi minimal 10 data dummy mobil awal:
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi backend aktif di: `http://127.0.0.1:8000`

### 7. Akses Dokumentasi API Interaktif (Scramble)
Buka peramban (browser) dan kunjungi:
👉 `http://127.0.0.1:8000/docs/api`
Anda dapat mencoba langsung endpoint API (*Try it out*) secara interaktif.

### 8. Menjalankan Automated Test
```bash
php artisan test
```

---

## 📑 Daftar Endpoint RESTful API

| Method | Endpoint | Deskripsi | Status Sukses |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/mobil` | Mengambil seluruh daftar data mobil dari MySQL | `200 OK` |
| **GET** | `/api/mobil/{id}` | Mengambil detail 1 data mobil berdasarkan ID | `200 OK` |
| **POST** | `/api/mobil` | Menambahkan unit mobil baru (wajib divalidasi) | `201 Created` |
| **PUT/PATCH** | `/api/mobil/{id}` | Memperbarui data mobil yang tersimpan | `200 OK` |
| **DELETE**| `/api/mobil/{id}` | Menghapus unit mobil dari basis data | `200 OK` |

---

## 💡 Fitur Nilai Tambah (Bonus)

1. **Pencarian Data (`search`)**:
   - `GET /api/mobil?search=Avanza` (Mencari berdasarkan nama model atau brand mobil).
2. **Penyaringan Data (`filter`)**:
   - `GET /api/mobil?brand=Toyota`
   - `GET /api/mobil?status=tersedia`
   - `GET /api/mobil?transmisi=CVT`
   - `GET /api/mobil?bahan_bakar=Hybrid`
   - `GET /api/mobil?min_harga=200000000&max_harga=500000000`
   - Gabungan: `GET /api/mobil?brand=Toyota&status=tersedia&bahan_bakar=Bensin`
3. **Paginasi Data (`paginate`)**:
   - `GET /api/mobil?paginate=true&per_page=5&page=1`
4. **Pengurutan Data (`sort`)**:
   - `GET /api/mobil?sort_by=harga&sort_order=desc`

---

## 📨 Contoh Format Respons JSON Standar

### 1. GET `/api/mobil` (200 OK)
```json
{
  "message": "Daftar mobil berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Avanza 1.5 G CVT",
      "brand": "Toyota",
      "harga": 275000000,
      "tahun": 2024,
      "transmisi": "CVT",
      "bahan_bakar": "Bensin",
      "status": "tersedia",
      "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341",
      "created_at": "2026-10-27T04:45:27.000000Z",
      "updated_at": "2026-10-27T04:45:27.000000Z"
    }
  ]
}
```

### 2. GET `/api/mobil/{id}` (200 OK)
```json
{
  "message": "Detail mobil berhasil diambil",
  "data": {
    "id": 1,
    "nama": "Avanza 1.5 G CVT",
    "brand": "Toyota",
    "harga": 275000000,
    "tahun": 2024,
    "transmisi": "CVT",
    "bahan_bakar": "Bensin",
    "status": "tersedia",
    "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341",
    "created_at": "2026-10-27T04:45:27.000000Z",
    "updated_at": "2026-10-27T04:45:27.000000Z"
  }
}
```

### 3. POST `/api/mobil` (201 Created)
**Request Body:**
```json
{
  "nama": "Civic RS Turbo",
  "brand": "Honda",
  "harga": 615000000,
  "tahun": 2024,
  "transmisi": "CVT",
  "bahan_bakar": "Bensin",
  "status": "tersedia",
  "foto": "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2"
}
```
**Response (201 Created):**
```json
{
  "message": "Data mobil berhasil ditambahkan",
  "data": {
    "id": 11,
    "nama": "Civic RS Turbo",
    "brand": "Honda",
    "harga": 615000000,
    "tahun": 2024,
    "transmisi": "CVT",
    "bahan_bakar": "Bensin",
    "status": "tersedia",
    "foto": "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2",
    "created_at": "2026-10-27T05:00:00.000000Z",
    "updated_at": "2026-10-27T05:00:00.000000Z"
  }
}
```

### 4. PUT/PATCH `/api/mobil/{id}` (200 OK)
**Response:**
```json
{
  "message": "Data mobil berhasil diperbarui",
  "data": {
    "id": 1,
    "nama": "Avanza 1.5 G CVT Facelift",
    "brand": "Toyota",
    "harga": 285000000,
    "tahun": 2024,
    "transmisi": "CVT",
    "bahan_bakar": "Bensin",
    "status": "tersedia",
    "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341",
    "created_at": "2026-10-27T04:45:27.000000Z",
    "updated_at": "2026-10-27T05:05:00.000000Z"
  }
}
```

### 5. DELETE `/api/mobil/{id}` (200 OK)
**Response:**
```json
{
  "message": "Data mobil berhasil dihapus"
}
```

### 6. Respon Gagal Validasi (422 Unprocessable Content)
```json
{
  "message": "The nama field is required. (and 1 more error)",
  "errors": {
    "nama": [
      "Kolom nama mobil wajib diisi."
    ],
    "harga": [
      "Kolom harga harus berupa angka."
    ]
  }
}
```

### 7. Respon Data Tidak Ditemukan (404 Not Found)
```json
{
  "message": "Data mobil tidak ditemukan"
}
```

---

## 📮 Postman Collection

File export Postman Collection sudah disediakan di dalam repositori:
📁 `postman/Showroom_Mobil_API.postman_collection.json`

Cara import ke Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas.
3. Pilih file `postman/Showroom_Mobil_API.postman_collection.json`.
4. Atur variabel environment `base_url` (contoh: `http://127.0.0.1:8000` untuk lokal atau URL live Clever Cloud Anda).
5. Seluruh endpoint siap diuji secara instan.

---

## ☁️ Panduan Deployment ke Clever Cloud

1. Buat aplikasi PHP baru di dashboard **Clever Cloud**.
2. Pastikan file `.clever.cloud/php.json` sudah ada di repository dengan konfigurasi:
   ```json
   {
     "deploy": {
       "webroot": "/public"
     }
   }
   ```
3. Buat database MySQL (bisa melalui addon Clever Cloud MySQL atau database cloud eksternal gratis seperti Aiven / TiDB Cloud).
4. Konfigurasikan **Environment Variables** di Clever Cloud:
   - `APP_NAME`="Showroom Mobil API"
   - `APP_ENV`=production
   - `APP_KEY`=(generate dari `php artisan key:generate --show`)
   - `APP_DEBUG`=false
   - `APP_URL`=https://[app-name].cleverapps.io
   - `DB_CONNECTION`=mysql
   - `DB_HOST`=[Host DB Cloud]
   - `DB_PORT`=[Port DB Cloud]
   - `DB_DATABASE`=[Nama DB Cloud]
   - `DB_USERNAME`=[User DB Cloud]
   - `DB_PASSWORD`=[Password DB Cloud]
5. Konfigurasi build & run migration di Clever Cloud melalui Console / SSH:
   ```bash
   php artisan migrate --force --seed
   ```
6. Buka URL: `https://[app-name].cleverapps.io/docs/api` untuk memastikan dokumentasi interaktif Scramble tampil sempurna tanpa hambatan.
