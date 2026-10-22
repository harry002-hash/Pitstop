<div align="center">

<img src="public/images/logo.png" alt="PitStop" width="160">

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
  - [Route Penting](#route-penting)
- [Notifikasi Real-Time (Reverb)](#notifikasi-real-time-reverb)
  - [Cara mengetes Reverb](#cara-mengetes-reverb)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

---

## Deskripsi Aplikasi

**PitStop** adalah sistem informasi bengkel berbasis web yang menghubungkan
pemilik bengkel/admin dengan customer dalam satu alur terpadu:

1. **Admin/owner** mendaftarkan motor lewat halaman **Generate Akun**
   (nomor plat + password klaim).
2. **Customer** daftar akun, login, lalu **klaim** motornya di halaman `/claim`
   memakai plat + password dari bengkel.
3. Setelah klaim, customer masuk **dashboard-nya sendiri**: lihat detail motor,
   ubah data motor, pantau **status servis** (Belum Servis → Sedang Servis →
   Selesai), dan **chat** dengan bengkel.
4. Saat admin mengubah status atau mengirim pengingat, customer langsung dapat
   **toast + bunyi** lewat WebSocket (Reverb). Kalau Reverb mati, status tetap
   ter-update lewat polling 15 detik.

Role yang didukung: `customer`, `admin`, `owner`.

---

## Fitur Utama

### Autentikasi & Role

- Register / login / logout (login memakai **username** + password).
- Tiga role: `customer`, `admin`, `owner` (kolom `role` di tabel `users`).
- `User::isStaff()` (admin + owner) dipakai bersama oleh middleware, form
  request, policy, dan controller — tidak ada cek role yang ditulis ulang.
- Middleware `owner` (khusus admin + owner) dan `claimed`
  (customer wajib sudah klaim motor sebelum buka dashboard).

### Generate Akun + Klaim Motor

- Admin/owner mendaftarkan motor di `/owner/vehicles/create`
  (nama motor, jenis, plat, password plat, status awal).
- Customer klaim motor di `/claim` dengan plat + password
  (password plat disimpan **hash**).
- Satu motor tidak bisa diklaim dua akun berbeda.

### Dashboard Customer

- Detail kendaraan (plat, pemilik, jenis, nama, status) — data real dari DB.
- Tombol **Ubah Data Kendaraan** membuka halaman edit yang sama dengan admin;
  customer hanya boleh mengubah plat + nama motor, sedangkan jenis + status
  tetap wewenang bengkel (dijaga `VehiclePolicy` + `UpdateVehicleRequest`).
- Tombol **Chat Bengkel** dan **Keluar** berfungsi.
- Status motor ter-update **real-time** (channel `vehicle.{id}`) dengan toast +
  bunyi saat motor **Selesai**, plus fallback polling `/customer/status`.

### Dashboard Admin / Owner

- Tabel semua motor: plat, pemilik (atau "belum diklaim"), status badge,
  dan kartu statistik (dihitung sekali jalan dari koleksi yang sama).
- Tombol **Ubah** per baris membuka halaman edit (prefill data asli,
  error validasi tampil di halaman yang sama).
- Tombol **lonceng per baris** mengirim **pengingat** ke customer pemilik motor;
  pengingat masuk sebagai pesan chat sehingga customer melihatnya sebagai toast
  dan tetap tersimpan walau Reverb sedang mati.
- Mengubah status jadi **Selesai** otomatis mengirim pesan chat ke pemilik.

### Chat (Real-Time, Privat)

- `Message::deliver()` adalah satu-satunya jalur pembuatan + broadcast pesan,
  dipakai oleh `ChatController` dan fitur pengingat/auto-selesai.
- `Message::scopeVisibleTo()` membatasi isi halaman chat: customer hanya melihat
  percakapan yang melibatkan dirinya, staff melihat semua thread.
- Broadcast ke **private channel milik penerima** (`App.Models.User.{id}`),
  jadi user lain tidak bisa menguping. Otorisasi channel memakai
  `routes/channels.php` (`/broadcasting/auth`).
- Halaman chat tetap memakai form yang ada (`receiver_id` + isi pesan),
  sekarang sudah punya CSRF token, `action`, dan listener `Echo.private`.

---

## Teknologi

| Lapisan      | Teknologi                                              |
| ------------ | ------------------------------------------------------ |
| Backend      | PHP 8.3+ (teruji di 8.5), Laravel 13                   |
| Database     | MySQL (dev: `sts_kel1`), SQLite in-memory untuk test   |
| Real-time    | Laravel Reverb + Laravel Echo + pusher-js              |
| Frontend     | Blade, Tailwind CSS (CDN di halaman customer), Vite    |
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
git clone <repo-url> PitStop
cd PitStop

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

# 6. Build frontend (wajib, halaman chat & dashboard memakai @vite)
npm run build        # sekali, untuk produksi
# atau:
npm run dev          # saat development

# 7. Jalankan aplikasi (pilih salah satu)
php artisan serve --port=8000          # http://localhost:8000
# atau via Laragon virtual host

# 8. Jalankan Reverb di terminal terpisah (biar real-time jalan)
php artisan reverb:start --host=127.0.0.1 --port=8080
```

> **Penting:** jalankan Reverb dengan `--host=127.0.0.1` (bukan `localhost`).
> Kalau memakai `localhost`, Reverb gagal start dengan pesan
> `Given URI "tcp://localhost:8080" does not contain a valid host IP`.
>
> Konfigurasi `REVERB_*` / `VITE_REVERB_*` sudah ada di `.env.example`
> (key `local`, host `127.0.0.1`, port `8080`, skema `http`). Kalau diubah,
> sesuaikan kedua sisi lalu `npm run build` ulang.

---

## Penggunaan

### Akun Bawaan

Dibuat otomatis oleh `php artisan db:seed`:

| Username | Password    | Role  | Kegunaan                        |
| -------- | ----------- | ----- | ------------------------------- |
| `admin`  | `admin`     | admin | Kelola motor, reminder, chat    |
| `ajung`  | `bengkel123`| owner | Sama seperti admin              |

> Ganti password bawaan setelah login pertama di production.

### Alur Admin / Owner

1. Login sebagai `admin`/`ajung` → otomatis ke `/owner/dashboard`.
2. Klik **Generate Akun** (`/owner/vehicles/create`) → isi nama motor, jenis,
   plat, password plat, status → **Simpan**. Berikan plat + password ke
   customer (offline).
3. **Ubah** di baris tabel untuk membuka halaman edit.
4. **Ikon lonceng** di baris tabel untuk kirim pengingat ke customer pemilik
   motor (ikon berubah hijau sebentar kalau terkirim).
5. Buka **Chat** untuk membalas customer (isi `receiver_id` dengan ID customer).
6. Ubah status motor jadi **Selesai** → customer otomatis dikabari lewat chat
   dan toast di dashboard-nya.

### Alur Customer

1. **Daftar** akun → **login** → otomatis diarahkan ke `/claim`.
2. Masukkan **plat + password** dari bengkel → **Masuk** → landing di
   `/customer` (dashboard sendiri).
3. Di dashboard: lihat status servis, **Ubah Data Kendaraan** (plat + nama),
   **Chat Bengkel**.
4. Kalau motor **Selesai** atau ada pesan baru: toast + bunyi muncul tanpa reload.

### Route Penting

| Method   | URL                          | Nama                    | Akses                     |
| -------- | ---------------------------- | ----------------------- | ------------------------- |
| GET      | `/`                          | —                       | publik                    |
| GET/POST | `/login`, `/register`        | `login`, `register`     | guest                     |
| GET      | `/dashboard`                 | `dashboard`             | auth (redirect per role)  |
| GET/POST | `/claim`                     | `vehicle.claim*`        | auth                      |
| GET      | `/customer`                  | `customer.dashboard`    | auth + claimed            |
| GET      | `/customer/status`           | `customer.status`       | auth + claimed            |
| GET/PUT  | `/vehicles/{vehicle}/edit`   | `vehicles.edit/update`  | staff / pemilik motor     |
| GET/POST | `/chat`, `/chat/send`        | `chat`, `chat.send`     | auth                      |
| GET      | `/owner/dashboard`           | `owner.dashboard`       | owner (admin + owner)     |
| GET      | `/owner/vehicles`            | `owner.vehicles.index`  | owner                     |
| GET/POST | `/owner/vehicles/create`     | `owner.vehicles.*`      | owner                     |
| POST     | `/owner/vehicles/{id}/remind`| `owner.vehicles.remind` | owner                     |

> Tidak ada lagi route kendaraan ganda: dulu ada `KendaraanController`
> (`/vehicles/{id}/edit`) dan `/owner/vehicles/{id}/edit` sekaligus. Sekarang
> keduanya memakai satu halaman edit + satu endpoint update.

---

## Notifikasi Real-Time (Reverb)

Arsitektur event:

| Event                  | Channel                     | Dipicu saat                       | Didengar oleh      |
| ---------------------- | --------------------------- | --------------------------------- | ------------------ |
| `MessageSent`          | `App.Models.User.{id}` (private) | pesan chat terkirim          | dashboard + chat customer |
| `VehicleStatusUpdated` | `vehicle.{id}` (publik)     | status motor berubah              | dashboard customer |

- Broadcast driver: `reverb` (`BROADCAST_CONNECTION=reverb`).
- Kedua event memakai `ShouldBroadcastNow`, jadi **tidak butuh queue worker**
  (`QUEUE_CONNECTION` boleh tetap `database`).
- Channel privat memakai `routes/channels.php` yang didaftarkan lewat
  `withBroadcasting(..., ['middleware' => ['web']])` di `bootstrap/app.php`.
  Tanpa itu, `/broadcasting/auth` tidak ada dan `Echo.private` akan gagal.
- Tanpa Reverb, status dashboard tetap ter-update lewat polling
  `GET /customer/status` setiap 15 detik.

### Cara mengetes Reverb

**A. Lewat dua browser (paling gampang, membuktikan end-to-end)**

1. Pastikan ketiga proses jalan:
   ```bash
   php artisan serve --port=8000
   php artisan reverb:start --host=127.0.0.1 --port=8080
   npm run dev            # atau sudah `npm run build`
   ```
2. Buka browser biasa: login `admin`/`admin` → `/owner/dashboard`.
3. Buka **incognito/jendela lain**: login sebagai customer yang sudah klaim
   motor (mis. `qwra`) → `/customer`.
4. Di jendela admin, klik **ikon lonceng** pada baris motor customer tersebut.
5. Dalam < 1 detik jendela customer harus muncul **toast + bunyi**. Kalau tidak
   muncul, cek Console browser (F12) dan pastikan tidak ada error WebSocket.

**B. Lewat Tinker (membuktikan event keluar dari Laravel)**

```bash
php artisan tinker
```

```php
// Kirim event status ke dashboard customer (ganti id kalau perlu)
broadcast(new App\Events\VehicleStatusUpdated(App\Models\Vehicle::find(1)));

// Kirim pesan chat ke user id 1
$to = App\Models\User::find(1);
$from = App\Models\User::where('role', 'owner')->first();
App\Models\Message::deliver($from, $to, 'Tes realtime dari tinker');
```

Kalau dashboard customer langsung berubah / toast muncul, berarti
**Laravel → Reverb → browser** sudah tersambung.

**C. Cek cepat lewat WebSocket (opsional, tanpa browser)**

Kalau punya Node.js, buka `ws://127.0.0.1:8080/app/local?protocol=7&client=js&version=8.4.0&flash=false`,
kirim `{"event":"pusher:subscribe","data":{"channel":"vehicle.1"}}`, lalu jalankan
perintah Tinker di atas. Payload `status.updated` harus masuk ke socket tersebut.

> Debug Reverb: log server ada di terminal `reverb:start`. Kalau muncul
> `Failed to connect`, biasanya `VITE_REVERB_*` tidak cocok dengan `REVERB_*`
> atau frontend belum di-build ulang.

---

## Testing

```bash
# Seluruh suite (Pest, SQLite in-memory)
php artisan test --compact

# Satu file / satu test
php artisan test --compact --filter="private user channel"
```

Cakupan saat ini (**38 test**, 118 assertion):

- auth (register, login, validasi, proteksi role),
- alur workshop (generate akun, klaim, ubah status, tabel owner),
- edit kendaraan (staff vs customer, policy, status tidak bisa diubah customer),
- chat (isolasi percakapan, kirim pesan, validasi, channel privat + auth),
- reminder (staff/admin boleh, customer ditolak, motor belum diklaim),
- pesan otomatis saat motor selesai,
- event broadcast (nama channel + payload `MessageSent`),
- ketahanan saat Reverb mati (chat & update status tetap tersimpan).

Format kode PHP mengikuti Laravel Pint:

```bash
vendor/bin/pint --dirty --format agent
```

---

## Troubleshooting

| Gejala | Penyebab umum & solusi |
| ------ | ---------------------- |
| Halaman blank / `ViteException` | Frontend belum di-build → `npm run build` (atau `npm run dev`) |
| Chat/notif tidak real-time | Reverb belum jalan → `php artisan reverb:start --host=127.0.0.1 --port=8080`; pastikan `npm run build` setelah ubah `VITE_REVERB_*` |
| `Echo` undefined di Console | Halaman tidak memuat `@vite(['resources/js/app.js'])` atau build belum jalan |
| `403` saat `Echo.private` | `/broadcasting/auth` belum terdaftar → pastikan `withBroadcasting(...)` ada di `bootstrap/app.php`; cek juga user sedang login |
| `Given URI "tcp://localhost:8080" does not contain a valid host IP` | Jalankan Reverb dengan `--host=127.0.0.1`, bukan `localhost` |
| Event tidak terkirim padahal Reverb jalan | `BROADCAST_CONNECTION=null`/`log` di `.env`, atau event tidak `ShouldBroadcast` |
| `403 Hanya pemilik bengkel` | Login sebagai customer di browser itu → logout, login `admin`/`ajung` |
| `Unknown column 'username'` / `vehicle_type` | Migrasi kepotong → `php artisan migrate` |
| Login gagal setelah fresh migrate | Data kehapus → `php artisan migrate --seed` |
| Perubahan view tidak muncul | `php artisan view:clear` |
| `No application key` | `.env` hilang → copy dari `.env.example` + `php artisan key:generate` |

---

## Lisensi

MIT, nothing to do, also can contri, etc.
