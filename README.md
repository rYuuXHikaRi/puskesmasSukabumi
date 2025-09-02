
# Proyek Magang : Back-end Aplikasi Pengambilan Obat Puskesmas Sukabumi (PT. Microdata Indonesia)

Nama : Nurroni
NIM  : 120140016

Repository ini merupakan Repository untuk Backend dari Aplikasi Pengambilan Obat Puskesmas Sukabumi. Dimana proyek ini merupakan Proyek Magang saya dengan PT. Microdata Indonesia.




## Cara menjalankan project ini

Clone the project

```bash
  git clone https://github.com/rYuuXHikaRi/puskesmasSukabumi.git
```

Go to the project directory

```bash
  cd puskesmasSukabumi
```

Install dependencies

```bash
  composer require tymon/jwt-auth
```

Generate JWT Secret Key
```bash
   php artisan jwt:secret
```

Copy kode token yang muncul, lalu tambahkan pada .env :

```bash
  JWT_SECRET=kodeToken
  JWT_SHOW_BLACKLIST_EXCEPTION=1
```

Migrate database

```bash
php artisan migrate
```

Jalankan project
```bash
   php artisan serve
```

Untuk melihat daftar route api :
```bash
   php artisan route:list
```

# Dokumentasi API Puskesmas

Dokumentasi ini menyediakan referensi lengkap untuk API Puskesmas. API ini dirancang untuk mengelola data pasien, obat-obatan, stok gudang, dan emergency kit.

## Daftar Isi
- [Autentikasi](#autentikasi)
- [Manajemen Pengguna](#manajemen-pengguna)
- [Manajemen Pasien](#manajemen-pasien)
- [Manajemen Obat](#manajemen-obat)
- [Manajemen Satuan Obat](#manajemen-satuan-obat)
- [Manajemen Gudang Obat](#manajemen-gudang-obat)
- [Manajemen Emergency Kit](#manajemen-emergency-kit)

---

### Autentikasi

#### Autentikasi Pengguna
Melakukan autentikasi pengguna dan mengembalikan token JWT.
- **Endpoint:** `POST /api/auth`
- **Request Body:**
    - `email` (string, **required**): Email pengguna.
    - `password` (string, **required**): Kata sandi pengguna.

#### De-autentikasi Pengguna
Melakukan logout dan membatalkan token JWT.
- **Endpoint:** `POST /api/user/deauth`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Periksa Token
Memeriksa validitas token JWT.
- **Endpoint:** `POST /api/user/check`
- **Headers:**
    - `Authorization`: `Bearer {token}`

---

### Manajemen Pengguna

#### Daftar Pengguna Baru
Mendaftarkan pengguna baru ke dalam sistem.
- **Endpoint:** `POST /api/user/register`
- **Request Body:**
    - `nik` (string, **required**): NIK pengguna.
    - `name` (string, **required**): Nama lengkap.
    - `username` (string, **required**): Nama pengguna.
    - `email` (string, **required**, unique): Alamat email.
    - `password` (string, **required**, min:8, confirmed): Kata sandi, minimal 8 karakter dan harus dikonfirmasi.
    - `age` (string, **required**): Umur pengguna.
    - `gender` (string, **required**): Jenis kelamin.
    - `address` (string, **required**): Alamat.
    - `foto` (file, **required**, image, max:2048): Foto profil, maks 2 MB.
    - `unit_layanan_id` (integer): ID unit layanan.

#### Tampilkan Semua Pengguna
Menampilkan daftar semua pengguna.
- **Endpoint:** `GET /api/user/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

---

### Manajemen Pasien

#### Tampilkan Semua Pasien
Menampilkan daftar semua pasien.
- **Endpoint:** `GET /api/patient/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Daftarkan Pasien Baru
Mendaftarkan data pasien baru.
- **Endpoint:** `POST /api/patient/register`
- **Request Body:**
    - `nomor_rm` (string, **required**, unique): Nomor rekam medis.
    - `nama_lengkap` (string, **required**, max:100): Nama lengkap pasien.
    - `nik` (string, **required**, max:20): NIK pasien.
    - `jenis_kelamin` (string, **required**): Jenis kelamin ('P' atau 'L').
    - `tempat_lahir` (string, **required**): Tempat lahir.
    - `tanggal_lahir` (date, **required**): Tanggal lahir.
    - `umur` (string, **required**): Umur.
    - `alamat` (string, **required**): Alamat.
    - `no_hp` (string, nullable): Nomor HP.
    - `email` (string, nullable, max:100): Email.
    - `gol_darah` (string, nullable): Golongan darah.
    - `status_nikah` (string, nullable): Status pernikahan.
    - `pekerjaan` (string, **required**, max:50): Pekerjaan.
    - `nama_kk` (string, **required**, max:100): Nama kepala keluarga.
    - `hubungan_kk` (string, nullable): Hubungan dengan kepala keluarga.
    - `keluhan` (string, **required**): Keluhan pasien.
    - `diagnosa` (string, **required**): Diagnosa.

#### Perbarui Data Pasien
Memperbarui data pasien.
- **Endpoint:** `PUT /api/patient/edit/{id}`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Path Parameter:**
    - `id` (string, **required**): ID pasien.
- **Request Body:**
    - `nomor_rm` (string, **required**, unique): Nomor rekam medis.
    - `nama_lengkap` (string, **required**, max:100): Nama lengkap pasien.
    - `nik` (string, **required**, max:20): NIK pasien.
    - `jenis_kelamin` (string, **required**): Jenis kelamin ('Perempuan' atau 'Laki-laki').
    - `tempat_lahir` (string, **required**): Tempat lahir.
    - `tanggal_lahir` (date, **required**): Tanggal lahir.
    - `umur` (string, **required**): Umur.
    - `alamat` (string, **required**): Alamat.
    - `no_hp` (string, nullable): Nomor HP.
    - `email` (string, nullable, max:100): Email.
    - `gol_darah` (string, nullable): Golongan darah.
    - `status_nikah` (string, nullable): Status pernikahan.
    - `pekerjaan` (string, **required**, max:50): Pekerjaan.
    - `nama_kk` (string, **required**, max:100): Nama kepala keluarga.
    - `hubungan_kk` (string, nullable): Hubungan dengan kepala keluarga.
    - `keluhan` (string, **required**): Keluhan pasien.
    - `diagnosa` (string, **required**): Diagnosa.

#### Lihat Riwayat Pasien
Mendapatkan riwayat medis dan pengambilan obat pasien.
- **Endpoint:** `GET /api/patient/getHistory`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameter:**
    - `patientId` (string, **required**): ID pasien.

---

### Manajemen Obat

#### Tampilkan Semua Obat
Menampilkan daftar semua obat.
- **Endpoint:** `GET /api/medicine/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Daftarkan Obat Baru
Mendaftarkan data obat baru.
- **Endpoint:** `POST /api/medicine/register`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `nama_obat` (string, **required**, unique, max:100): Nama obat.
    - `kode_obat` (string, **required**, unique, max:100): Kode obat.
    - `satuan_id` (integer, **required**): ID satuan obat.
    - `jenis_obat` (string, **required**): Jenis obat.
    - `tanggal_kadaluarsa` (date, **required**): Tanggal kedaluwarsa.
    - `bpom` (string, **required**, max:255): Nomor BPOM.
    - `gambar_obat` (file, nullable, image, max:2048): Gambar obat.
    - `keterangan` (string, nullable): Keterangan tambahan.

#### Perbarui Data Obat
Memperbarui data obat.
- **Endpoint:** `PUT /api/medicine/edit/{id}`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Path Parameter:**
    - `id` (string, **required**): ID obat.
- **Request Body:**
    - `nama_obat` (string, **required**, unique, max:100): Nama obat.
    - `kode_obat` (string, **required**, unique, max:100): Kode obat.
    - `satuan_id` (integer, **required**): ID satuan obat.
    - `jenis_obat` (string, **required**): Jenis obat.
    - `tanggal_kadaluarsa` (date, **required**): Tanggal kedaluwarsa.
    - `bpom` (string, **required**, max:255): Nomor BPOM.
    - `gambar_obat` (file, nullable, image, max:2048): Gambar obat.
    - `keterangan` (string, nullable): Keterangan tambahan.

#### Buat Pengambilan Obat
Membuat entri pengambilan obat.
- **Endpoint:** `POST /api/medicine/createRetrieval`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `emergency_kit_id` (integer, nullable): ID emergency kit.
    - `gudang_id` (integer, nullable): ID gudang.
    - `pasien_id` (integer, **required**): ID pasien.
    - `keterangan` (string, nullable): Keterangan pengambilan.

---

### Manajemen Satuan Obat

#### Tampilkan Semua Satuan Obat
Menampilkan daftar semua satuan obat.
- **Endpoint:** `GET /api/medicine/unitmeasurement/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Daftarkan Satuan Obat Baru
Mendaftarkan satuan obat baru.
- **Endpoint:** `POST /api/medicine/unitmeasurement/register`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `nama_satuan` (string, **required**, unique, max:255): Nama satuan.
    - `keterangan` (string, nullable, max:255): Keterangan.

#### Perbarui Data Satuan Obat
Memperbarui data satuan obat.
- **Endpoint:** `PUT /api/medicine/unitmeasurement/edit/{id}`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Path Parameter:**
    - `id` (string, **required**): ID satuan obat.
- **Request Body:**
    - `nama_satuan` (string, **required**, unique): Nama satuan.
    - `keterangan` (string, nullable, max:255): Keterangan.

---

### Manajemen Gudang Obat

#### Tampilkan Semua Gudang
Menampilkan daftar semua gudang obat.
- **Endpoint:** `GET /api/medicine/storage/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Daftarkan Gudang Baru
Mendaftarkan gudang obat baru.
- **Endpoint:** `POST /api/medicine/storage/register`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `kode_gudang` (string, **required**, unique): Kode gudang.
    - `nama_gudang` (string, **required**): Nama gudang.
    - `tipe` (string, **required**): Tipe gudang.
    - `lokasi` (string, **required**): Lokasi gudang.
    - `keterangan` (string, nullable): Keterangan.

#### Perbarui Data Gudang
Memperbarui data gudang obat.
- **Endpoint:** `PUT /api/medicine/storage/edit/{id}`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Path Parameter:**
    - `id` (string, **required**): ID gudang.
- **Request Body:**
    - `kode_gudang` (string, **required**, unique): Kode gudang.
    - `nama_gudang` (string, **required**): Nama gudang.
    - `tipe` (string, **required**): Tipe gudang.
    - `lokasi` (string, **required**): Lokasi gudang.
    - `keterangan` (string, nullable): Keterangan.

#### Lihat Stok Obat di Gudang
Menampilkan stok obat di gudang.
- **Endpoint:** `GET /api/medicine/storage/stockView`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameters (optional):**
    - `gudangId` (string): Filter berdasarkan ID gudang.
    - `obatId` (string): Filter berdasarkan ID obat.

#### Tambahkan Stok Obat ke Gudang
Menambahkan stok obat baru atau memperbarui stok obat yang sudah ada di gudang.
- **Endpoint:** `POST /api/medicine/storage/stockAdd`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `obat_id` (integer, **required**): ID obat.
    - `gudang_id` (integer, **required**): ID gudang.
    - `stok` (integer, **required**, min:0): Jumlah stok.

#### Perbarui Stok Obat di Gudang
Memperbarui jumlah stok obat yang sudah ada di gudang.
- **Endpoint:** `PUT /api/medicine/storage/stockUpdate`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameters:**
    - `gudangId` (string, **required**): ID gudang.
    - `obatId` (string, **required**): ID obat.
- **Request Body:**
    - `stok` (integer, **required**, min:0): Jumlah stok yang diperbarui.

---

### Manajemen Emergency Kit

#### Tampilkan Semua Emergency Kit
Menampilkan daftar semua emergency kit.
- **Endpoint:** `GET /api/medkit/show`
- **Headers:**
    - `Authorization`: `Bearer {token}`

#### Daftarkan Emergency Kit Baru
Mendaftarkan emergency kit baru.
- **Endpoint:** `POST /api/medkit/register`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Request Body:**
    - `nama` (string, **required**): Nama emergency kit.
    - `keterangan` (string, nullable): Keterangan.
    - `qr_code` (file, nullable, image, max:2048): Gambar QR code.
    - `unit_layanan_id` (integer, nullable): ID unit layanan.
    - `emergencyKit_id` (integer, nullable): ID emergency kit induk.

#### Perbarui Data Emergency Kit
Memperbarui data emergency kit.
- **Endpoint:** `PUT /api/medkit/edit/{id}`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Path Parameter:**
    - `id` (string, **required**): ID emergency kit.
- **Request Body:**
    - `nama` (string, **required**): Nama emergency kit.
    - `keterangan` (string, nullable): Keterangan.
    - `qr_code` (file, nullable, image, max:2048): Gambar QR code.
    - `unit_layanan_id` (integer, nullable): ID unit layanan.
    - `emergencyKit_id` (integer, nullable): ID emergency kit induk.

#### Lihat Stok Obat di Emergency Kit
Menampilkan stok obat di emergency kit.
- **Endpoint:** `GET /api/medkit/stockView`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameters (optional):**
    - `medkitId` (string): Filter berdasarkan ID emergency kit.
    - `obatId` (string): Filter berdasarkan ID obat.
    - `unitLayananId` (string): Filter berdasarkan ID unit layanan.

#### Tambahkan Stok Obat ke Emergency Kit
Menambahkan stok obat dari gudang ke emergency kit.
- **Endpoint:** `POST /api/medkit/stockAdd`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameter:**
    - `fromGudangId` (string): ID gudang asal.
- **Request Body:**
    - `obat_id` (integer, **required**): ID obat.
    - `emergency_kit_id` (integer, **required**): ID emergency kit.
    - `stok` (integer, **required**, min:0): Jumlah stok yang ditambahkan.

#### Perbarui Stok Obat di Emergency Kit
Memperbarui jumlah stok obat yang sudah ada di emergency kit.
- **Endpoint:** `PUT /api/medkit/stockUpdate`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameters:**
    - `medkitId` (string, **required**): ID emergency kit.
    - `obatId` (string, **required**): ID obat.
- **Request Body:**
    - `stok` (integer, **required**, min:0): Jumlah stok yang diperbarui.

#### Ambil Obat dari Emergency Kit
Mengambil obat dari emergency kit atau gudang.
- **Endpoint:** `PUT /api/medkit/getMedicine`
- **Headers:**
    - `Authorization`: `Bearer {token}`
- **Query Parameter:**
    - `obatId` (string): ID obat.
- **Request Body:**
    - `pengambilan_id` (integer, **required**): ID pengambilan.
    - `jumlah` (integer, **required**, min:1): Jumlah obat yang diambil.
    - `keterangan` (string, nullable): Keterangan.

