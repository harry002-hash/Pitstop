<div align="center">

![image](public\images\logo.png)

# PitStop — Sistem Informasi Bengkel

Aplikasi web manajemen bengkel: admin/owner mendaftarkan motor (plat + password),
customer klaim motornya, pantau status servis, chat dengan bengkel, dan terima
notifikasi real-time. Dibangun dengan Laravel + Reverb + Tailwind CSS.

</div>

---

## Daftar Isi

- [Deskripsi Aplikasi](#deskripsi-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Kebutuhan Sistem](#kebutuhan-sistem)
- [Instalasi](#instalasi)
- [Penggunaan](#penggunaan)
  - [Akun Bawaan](#akun-bawaan)
  - [Alur Admin / Owner](#alur-admin--owner)
  - [Alur Customer](#alur-customer)
- [Notifikasi Real-Time](#notifikasi-real-time)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

---

## Deskripsi Aplikasi

**PitStop** adalah sistem informasi bengkel berbasis web yang menghubungkan
pemilik bengkel/admin dengan customer dalam satu alur terpadu:

1. **Admin/owner** mendaftarkan motor lewat fitur **Generate Akun**
   (nomor plat + password klaim).
2. **Customer** daftar akun, login, lalu **klaim** motornya di halaman `/claim`
   memakai plat + password dari bengkel.
3. Setelah klaim, customer masuk **dashboard-nya sendiri**: lihat detail motor,
   ubah data motor, pantau **status servis** (Belum Servis → Sedang Servis →
   Selesai), dan **chat** dengan bengkel.
4. Setiap ada chat masuk, klaim baru, pengingat, atau motor selesai,
   admin dapat **notifikasi lonceng + popup + bunyi**; customer dapat
   **popup + bunyi + banner** saat motornya selesai.

Role yang didukung: `customer`, `admin`, `owner`.

---

## Fitur Utama

### Autentikasi & Role

- Register / login / logout (login memakai **username** + password).
- Tiga role: `customer`, `admin`, `owner` (kolom `role` di tabel `users`).
- Middleware `owner` (khusus admin + owner) dan `claimed`
  (customer wajib sudah klaim motor sebelum buka dashboard).

### Generate Akun + Klaim Motor

- Admin/owner mendaftarkan motor lewat **modal Generate Akun**
  (nama motor, plat, password plat, status awal) — tanpa pindah halaman.
- Customer klaim motor di `/claim` dengan plat + password
  (password plat disimpan **hash**).
- Satu motor tidak bisa diklaim dua akun berbeda.

### Dashboard Customer

- Detail kendaraan (plat, pemilik, jenis, nama, status) dengan data real dari DB.
- Tombol **Ubah Data Kendaraan** (customer hanya boleh ubah plat + nama motor;
  status tetap wewenang bengkel).
- Tombol **Chat Bengkel**.
- **Banner hijau besar** otomatis muncul saat motor berstatus **Selesai**.

### Dashboard Admin / Owner

- Tabel semua motor: plat, pemilik (atau "belum diklaim"), status badge,
  pencarian, dan kartu statistik.
- Tombol **Ubah** per baris membuka **modal edit** (prefill data;
  kalau validasi gagal, modal baris itu kebuka lagi + error tampil di dalam).
- Tombol **lonceng per baris** mengirim **pengingat** ke customer pemilik motor.
- Tombol navigasi **Chat** ke inbox customer-service.

### Chat Customer-Service (Real-Time)

- Model **private per customer**: customer hanya chat dengan admin,
  admin punya inbox semua customer (`/chat?u={id}`).
- `receiver_id` diisi otomatis backend (customer → staff terakhir/petama,
  staff → wajib `to_user_id` customer). Tidak bisa dimanipulasi dari frontend.
- Real-time via **Laravel Reverb** di private channel `support.{customerId}`;
  dukung **lampiran gambar** (maks 5 MB, tersimpan di `storage/app/public/chat`).

### Notifikasi

- **Admin**: ikon **lonceng** + badge di dashboard (dropdown 10 notif terakhir:
  chat masuk + klaim baru), **popup toast + bunyi** tiap ada notif baru,
  bunyi via WebAudio (tanpa file MP3).
- **Customer**: **popup toast + bunyi** untuk balasan chat staff dan
  perubahan status motor.
- **Otomatis saat motor selesai**: pesan chat "Kabar gembira..." terkirim ke
  pemilik + banner selesai di dashboard-nya.
- Fallback polling 15 detik kalau server Reverb tidak jalan.

---

## Teknologi

| Lapisan      | Teknologi                                              |
| ------------ | ------------------------------------------------------ |
| Backend      | PHP 8.3+ (teruji di 8.4), Laravel 13                   |
| Database     | MySQL (dev: `sts_kel1`), SQLite in-memory untuk test   |
| Real-time    | Laravel Reverb + Laravel Echo + pusher-js              |
| Frontend     | Blade, Tailwind CSS 4, Vite 8                          |
| Testing      | Pest 5 (PHPUnit di baliknya)                           |
| Environment  | Laragon (Apache/MySQL) atau `php artisan serve`        |

---

## Kebutuhan Sistem

- PHP >= 8.3 dengan ekstensi standar Laravel
- Composer 2
- Node.js 20+ & NPM
- MySQL 8 (atau MariaDB bawaan Laragon)

---

## Instalasi

```bash
# 1. Clone repo lalu masuk ke foldernya
git clone <repo-url> STSkel1_12TKJ2
cd STSkel1_12TKJ2

# 2. Install dependency PHP & JS
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Atur database di .env (contoh untuk Laragon)
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=sts_kel1
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Migrasi + seed akun bawaan
php artisan migrate --seed

# 6. Link storage (wajib biar gambar chat bisa diakses)
php artisan storage:link

# 7. Build frontend
npm run build        # sekali, untuk produksi
# atau:
npm run dev          # saat development (jalan bareng server di bawah)

# 8. Jalankan aplikasi (pilih salah satu)
php artisan serve --port=8000          # http://localhost:8000
# atau via Laragon virtual host

# 9. Jalankan Reverb (WAJIB di terminal terpisah biar chat & notif real-time jalan)
php artisan reverb:start --host=127.0.0.1 --port=8080
```

> Konfigurasi Reverb (`REVERB_*` / `VITE_REVERB_*`, port `8080`, skema `http`)
> sudah tersedia di `.env.example`. Kalau port/host diubah, sesuaikan kedua
> sisinya lalu `npm run build` ulang.

---

## Penggunaan

### Akun Bawaan

 Dibuat otomatis oleh `php artisan db:seed`:

| Username | Password    | Role  | Kegunaan                        |
| -------- | ----------- | ----- | ------------------------------- |
| `admin`  | `admin`     | admin | Notif, chat, generate akun      |
| `ajung`  | `bengkel123`| owner | Semua hak admin + area owner    |

> Ganti password bawaan setelah login pertama di production.

### Alur Admin / Owner

1. Login sebagai `admin`/`ajung` → otomatis ke `/owner/dashboard`.
2. Klik **+ Generate Akun** → isi nama motor, plat, password plat, status →
   **Simpan**. Berikan plat + password ke customer (offline).
3. **Ubah** di baris tabel untuk edit (modal). **Ikon lonceng** di baris
   untuk kirim pengingat ke customer pemilik motor (tombol jadi centang hijau
   kalau terkirim).
4. Buka **Chat** di sidebar → pilih customer di inbox → balas chat.
5. Klik **lonceng** kanan atas untuk riwayat notifikasi; notif baru muncul
   sebagai popup + bunyi.
6. Ubah status motor jadi **Selesai** → customer otomatis dikabari
   (pesan chat + banner di dashboard-nya).

### Alur Customer

1. **Daftar** akun → **login** → otomatis diarahkan ke `/claim`.
2. Masukkan **plat + password** dari bengkel → **Masuk** → landing di
   `/customer` (dashboard sendiri).
3. Di dashboard: lihat status servis, **Ubah Data Kendaraan** (plat + nama),
   **Chat Bengkel**.
4. Kalau motor **Selesai**: banner hijau besar muncul + popup + bunyi.

### Route Penting

| Method | URL                        | Nama                  | Akses          |
| ------ | -------------------------- | --------------------- | -------------- |
| GET    | `/`                        | —                     | redirect login |
| GET/POST | `/login`, `/register`    | `login`, `register`   | guest          |
| GET    | `/dashboard`               | `dashboard`           | auth (redirect per role) |
| GET/POST | `/claim`                 | `vehicle.claim*`      | auth           |
| GET    | `/customer`                | `customer.dashboard`  | claimed        |
| GET/PUT | `/customer/vehicle/edit`  | `customer.vehicle.*`  | claimed        |
| GET/POST | `/chat`, `/chat/send`    | `chat`, `chat.send`   | auth           |
| GET    | `/owner/dashboard`         | `owner.dashboard`     | owner          |
| GET    | `/owner/notifications`     | `owner.notifications` | owner          |
| CRUD   | `/owner/vehicles*`         | `owner.vehicles.*`    | owner          |

---

## Notifikasi Real-Time

Arsitektur event:

| Event                  | Channel (private/publik) | Dipicu saat                          | Didengar oleh        |
| ---------------------- | ------------------------ | ------------------------------------ | -------------------- |
| `MessageSent`          | `support.{customerId}`   | pesan chat terkirim                  | halaman chat         |
| `StaffAlert`           | `staff.alerts`           | chat dari customer / klaim baru      | dashboard admin      |
| `VehicleStatusUpdated` | `vehicle.{id}` (publik)  | status motor berubah                 | dashboard customer   |

- Broadcast driver: `reverb` (`BROADCAST_CONNECTION`), queue `sync` saat dev
  supaya event langsung terkirim.
- Channel `support.*` boleh dimasuki pemilik thread + staff; `staff.alerts`
  khusus staff (`routes/channels.php`).
- Tanpa Reverb, halaman tetap dapat notif via polling JSON
  (`/owner/notifications`, `/customer/notifications`) tiap 15 detik.

---

## Testing

```bash
# Seluruh suite (Pest, SQLite in-memory)
php artisan test --compact

# Satu file / satu test
php artisan test --compact --filter="bisa klaim"
```

Cakupan saat ini (26 test): auth (register/login/validasi),
alur workshop (generate, klaim, status, edit customer),
chat CS (isolasi thread, auto-receiver), notifikasi (history, broadcast,
reminder, pesan otomatis saat selesai), dan proteksi role.

Format kode PHP mengikuti Laravel Pint:

```bash
vendor/bin/pint --dirty --format agent
```

---

## Troubleshooting

| Gejala | Penyebab umum & solusi |
| ------ | ---------------------- |
| Gambar chat tampil teks "Lampiran" | Symlink storage belum ada → `php artisan storage:link` |
| Chat/notif tidak real-time | Reverb belum jalan → `php artisan reverb:start --host=127.0.0.1 --port=8080`; pastikan `npm run build` setelah ubah `VITE_REVERB_*` |
| `403 Hanya pemilik bengkel` | Login sebagai customer di browser itu → logout, login `admin`/`ajung` |
| `Unknown column 'username'` | Migrasi kepotong → `php artisan migrate` (jangan skip file `*_add_role_*` / `*_rename_name_to_username_*`) |
| Login gagal setelah fresh migrate | Data kehapus → `php artisan db:seed` (atau `migrate:fresh --seed`) |
| `No application key` | `.env` hilang → copy dari `.env.example` + `php artisan key:generate` |

---

## Lisensi

Proyek tugas kuliah — bebas dipakai untuk keperluan pembelajaran.
Komponen framework Laravel berlisensi [MIT](https://opensource.org/licenses/MIT).
