<div align="center">
  <br />

  <h1>LAPORAN PRAKTIKUM <br>
  APLIKASI BERBASIS PLATFORM
  </h1>

  <br />

  <h3>MODUL - 13<br>
  LARAVEL: DATABASE 2 (AUTH, MIDDLEWARE & RELATIONS)
  </h3>

  <br />

  <img width="250" alt="Logo Tel-U" src="https://github.com/user-attachments/assets/22ae9b17-5e73-48a6-b5dd-281e6c70613e" />

  <br />
  <br />
  <br />

  <h3>Disusun Oleh :</h3>

  <p>
    <strong>Arvan Mrbiyanto</strong><br>
    <strong>2311102074</strong><br>
    <strong>S1 IF-11-04</strong>
  </p>

  <br />

  <h3>Dosen Pengampu :</h3>

  <p>
    <strong>Cahyo Prihantoro, S.Kom., M.Eng.</strong>
  </p>
  
  <br />

  <h3>LABORATORIUM HIGH PERFORMANCE
  <br>FAKULTAS INFORMATIKA <br>UNIVERSITAS TELKOM PURWOKERTO <br>2026</h3>
</div>

<hr>

# Dasar Praktikum

Pada praktikum modul 13 ini, fokus pengembangan bergeser menuju eskalasi keamanan akses dan perancangan arsitektur _database_ yang lebih kompleks. Mahasiswa ditugaskan untuk mengimplementasikan sistem _Authentication_ (Login/Logout), manajemen sesi (_Session_), pembatasan akses (_Middleware_), serta menghubungkan antar-entitas data menggunakan skema relasi _One-to-Many_ melalui Eloquent ORM di _framework_ Laravel.

# Dasar Teori

## 1.1 Manajemen Session

_Session_ adalah mekanisme penyimpanan data sementara di sisi server yang terikat pada interaksi pengguna tertentu. Laravel mendukung dua tipe sesi:

- **Session Reguler:** Bertahan selama sesi peramban aktif atau hingga waktu kedaluwarsa habis (misal: menyimpan status login, nama _user_).
- **Session Flash:** Hanya bertahan untuk satu siklus _HTTP Request_ berikutnya sebelum otomatis terhapus (misal: notifikasi _success/error_ saat _redirect_).

## 1.2 Keamanan Berlapis via Middleware & Auth

_Middleware_ berfungsi sebagai pos pemeriksaan (_checkpoint_) yang menyaring setiap _HTTP Request_ yang masuk. Jika suatu _route_ diproteksi _Middleware Auth_, pengguna yang belum melalui proses otentikasi akan otomatis ditolak dan diarahkan ke halaman _Login_. Proses validasi kredensial sendiri difasilitasi oleh `Auth` _facade_, sebuah pustaka terintegrasi Laravel yang memvalidasi _email_ dan _password_ yang telah terenkripsi (di-_hash_ menggunakan algoritma _Bcrypt_).

## 1.3 Model Relasi (Eloquent Relationships)

Aplikasi tingkat lanjut tidak mungkin berdiri hanya dengan tabel-tabel terisolasi. Laravel Eloquent menyederhanakan _Join_ antar tabel menggunakan _Object-Oriented syntax_. Konsep _One-to-Many_ (Satu-ke-Banyak) diterapkan di sini; di mana satu objek `Product` dapat memiliki banyak objek `Variant` (dikendalikan dengan `hasMany`), sedangkan setiap `Variant` dipastikan hanya merujuk pada satu `Product` secara spesifik (dikendalikan dengan `belongsTo`).

---

# PENGERJAAN & IMPLEMENTASI SISTEM

Penerapan pada modul ini menitikberatkan pada perancangan logika keamanan di sisi server dan interkoneksi entitas data agar tetap solid meski diakses oleh berbagai profil _user_.

## 2.1 Skema Autentikasi

Akses ke menu pengelolaan produk kini dikunci sepenuhnya.

| Komponen                       | Implementasi Logika                                                                                                                                 |
| ------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Routing**                    | URL `/product` disematkan `->middleware('auth')`. Pemanggilan _route_ login diberi nama alias `name('login')` sebagai rujukan standar _Middleware_. |
| **Pengecekan (Auth::check)**   | Jika _user_ sudah masuk, URL `/login` akan langsung memantulkannya ke _dashboard_ produk untuk mencegah _bypass_ logika.                            |
| **Otentikasi (Auth::attempt)** | Membandingkan secara aman _input_ _form_ dengan _hash Bcrypt_ yang tersimpan di basis data tanpa perlu mendeskripsi _password_ secara paksa.        |

## 2.2 Relasi Entitas Database (One-to-Many)

Tabel pendukung `variants` dibuat dengan menjaga integritas data menggunakan `foreignId` yang dirangkai dengan `constrained()`. Parameter ini memastikan pada level RDBMS bahwa _ID_ produk yang disematkan ke dalam varian benar-benar ada di tabel referensi. Pemanggilan data varian ke antarmuka juga dieksekusi secara efisien menggunakan pendekatan hierarki objek di Blade.

---

## 3. Source Code Praktikum

> **Catatan Engineer:** Desain sistem relasional dan autentikasi wajib mematuhi standar _Clean Architecture_. Kode di bawah memastikan proteksi ketat pada _route_, penggunaan koneksi _database_ secara bijak, dan limitasi penulisan sintaks agar ramah pada monitor portabel (14 inci).

### 3.1 Perlindungan Rute (Routing - `routes/web.php`)

Pengaturan alur lalu lintas _request_, mendaftarkan fungsi otentikasi, serta memberikan tameng _middleware_ pada rute esensial.

```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProductController;

// Rute Tampilan Login dengan pengecekan sesi aktif
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/product');
    }
    return view('login');
})->name('login');

// Rute Eksekusi Login (Submit Form)
Route::post('/login', [SiteController::class, 'auth'])->name('login.post');

// Rute Pemusnahan Sesi (Logout)
Route::get('/logout', function () {
    Auth::logout();

    // Invalidate sesi secara total guna memitigasi Session Fixation Attack
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// Rute CRUD Product diproteksi penuh oleh Middleware Auth
Route::resource('product', ProductController::class)->middleware('auth');
```

### 3.2 Lapisan Pengendali Keamanan (app/Http/Controllers/SiteController.php)

Memvalidasi masukan form login dengan standar eksekusi sistem bawaan (Auth Attempt).

```PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SiteController extends Controller
{
    public function auth(Request $request)
    {
        // Limitasi input awal agar request tidak membebani memori
        $credentials = $request->validate([
            'email'    => 'required|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Auth::attempt melakukan pencocokan hash Bcrypt di latar belakang
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                $request->session()->regenerate();

                // Menyimpan nama user ke session statis sebagai fallback/display
                session()->put('name', Auth::user()->name);

                return redirect()->intended('/product');
            }

            // Fallback apabila kredensial salah (tidak spesifik memberitahu mana yang salah)
            return redirect('/login')
                ->with('msg', 'Otentikasi gagal: Email atau Password tidak valid.');

        } catch (\Exception $e) {
            Log::error('Kesalahan Otentikasi Lintas Sistem: ' . $e->getMessage());
            return redirect('/login')->with('msg', 'Terjadi kesalahan internal server.');
        }
    }
}
```

### 3.3 Skema Migrasi Relasional (database/migrations/...\_create_variants_table.php)

Membuat tabel detail produk yang dikunci secara struktural ke tabel induk.

```PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();

            // Atribut Variabel
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('processor', 100);
            $table->string('memory', 50);
            $table->string('storage', 50);

            // Relasi Foreign Key dengan referensi tabel `products`
            // onDelete('cascade') opsional: jika produk dihapus, variannya terhapus otomatis
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
```

### 3.4 Representasi Model Relasional ORM

Model Product.php (Posisi Induk):

```PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    // Menandakan 1 Produk berhak memiliki banyak Varian
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
```

Model Variant.php (Posisi Anak):

```PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    // Melindungi Mass-Assignment
    protected $fillable = [
        'name', 'description', 'processor',
        'memory', 'storage', 'product_id'
    ];

    // Menandakan spesifikasi Varian ini merupakan milik 1 Produk mutlak
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

### 3.5 Pembaruan Layout Template Induk (resources/views/template.blade.php)

Menggunakan directive Blade @auth untuk mendeteksi visibilitas menu berdasarkan sesi.

```HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link
        href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)"
        rel="stylesheet"
    >
</head>
<body class="bg-light" style="width: 95%; margin: 0 auto;">

    @auth
        <div class="row justify-content-end mt-4 mb-2">
            <div class="col-md-4 text-end">
                <span class="fw-bold me-3 text-secondary">
                    Selamat datang, {{ Auth::user()->name }}
                </span>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-danger shadow-sm">
                    Logout Keamanan
                </a>
            </div>
        </div>
    @endauth

    <div class="row justify-content-center mt-3">
        @yield('content')
    </div>

    <script
        src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js)">
    </script>
</body>
</html>
```

### 3.6 Modifikasi Tampilan Tabel dengan Nested Data (resources/views/products/index.blade.php)

Menarik data relasional secara dinamis dari Model ke layar antarmuka pengguna.

```HTML
<table class="table table-hover table-bordered m-0 bg-white">
    <thead class="table-dark">
        <tr>
            <th>Nama Produk Utama</th>
            <th>Harga (Rp)</th>
            <th>Spesifikasi Varian Terkait</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $d)
        <tr>
            <td class="align-middle fw-bold">{{ $d->name }}</td>
            <td class="align-middle">{{ number_format($d->price, 0, ',', '.') }}</td>

            <td class="align-middle">
                <ul class="mb-0 text-muted" style="font-size: 0.9em;">
                    @foreach ($d->variants as $var)
                        <li class="mb-2">
                            <strong class="text-dark">{{ $var->name }}</strong><br>
                            Processor: {{ $var->processor }} <br>
                            RAM: {{ $var->memory }} | Storage: {{ $var->storage }} <br>
                            <span class="fst-italic">{{ $var->description }}</span>
                        </li>
                    @endforeach
                </ul>
            </td>

            <td class="align-middle text-center">
                </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

HASIL TAMPILAN WEB (OUTPUT)
Berikut adalah dokumentasi tangkapan layar (screenshot) implementasi operasi keamanan logikal dan pemanggilan kerangka data berelasi (Database Relational Mapping):

1. Tampilan Halaman Login (Proteksi Awal)
   Deskripsi: Menampilkan form masuk yang wajib diisi. Apabila URL /product dipaksa diakses tanpa sesi, pengguna akan selalu terpantul ke halaman ini oleh Middleware.
   <img width="1330" height="602" alt="Screenshot Halaman Login" src="tampilanlogin.png" />

2. Tampilan Header Auth di Layout Global
   Deskripsi: Visualisasi directive @auth yang berhasil mengidentifikasi nama user yang sedang login beserta ketersediaan tombol eksekusi "Logout Keamanan".
   <img width="606" height="160" alt="Screenshot Header Auth" src="tampilanafterlogin.png" />

3. Tampilan Halaman Daftar Produk & Varian (One-to-Many Output)
   Deskripsi: Visualisasi dari arsitektur Object-Relational Mapping yang merender kumpulan atribut turunan variants langsung berdampingan dengan entitas induk products secara struktural.
   <img width="1836" height="770" alt="Screenshot Halaman Produk" src="tampilanhalamanproduk.png" />

# TUGAS PERTEMUAN 8

1. jelaskan tentang git branch

- apa itu git branch
- buatlah git branch dengan 2 akun berbeda dan hubungkan dengan project yang di buat di tugas 2 ( bisa dengan antar teman kelas
- kalian jelaskan apa saja fungsi nya dan apa keuntungan git branch
- buat juga output dan input apa saja yang dapat kalian lakukan mengunakan git branch

2. buatlah website ( bisa mengunakan website yang di gunnakan dalam tubes ) , lalu tambahkan database yang terhubung dengan local house

## JAWAB

### 1. git branch

- Git branch adalah fitur dalam Git yang berfungsi menciptakan ruang kerja terpisah (cabang) dari repositori utama (main/master). Ini memungkinkan pengembang bereksperimen, memperbaiki bug, atau menambahkan fitur baru tanpa memengaruhi kode utama yang stabil. Branch bertindak sebagai pointer ringan yang bergerak otomatis setiap ada commit.
-
- Fungsi dan Keuntungan Git Branch
  - Fungsi Utama:
    Isolasi Kode: Memisahkan pekerjaan yang sedang berjalan dari kode utama yang sudah stabil (production-ready).
    Kolaborasi Tim: Memungkinkan banyak developer mengerjakan fitur yang berbeda-beda di dalam satu proyek yang sama pada waktu yang bersamaan.
    Manajemen Rilis: Memisahkan versi aplikasi (misalnya: branch untuk development, testing, dan production).

  - Keuntungan Menggunakan Git Branch:
    Aman dari Error Fatal: Jika kodingan di branch baru ternyata error atau berantakan, kode di branch utama (main) tidak akan terpengaruh sama sekali.
    Pengembangan Paralel: Kamu dan temanmu bisa bekerja di detik yang sama, mengedit file yang sama, tanpa harus saling tunggu.
    Code Review Lebih Rapi: Memudahkan proses pengecekan kode sebelum digabungkan (biasanya melalui proses Pull Request / Merge Request).
    Mudah Berpindah Konteks: Kamu bisa lompat dari mengerjakan "Fitur A" ke "Perbaikan Bug B" hanya dengan berganti branch, tanpa perlu membuat folder project baru di laptop.

-

### 2. Website

# Sistem Manajemen Showroom & Inventaris Honda (Tugas 8)

Proyek ini dibangun menggunakan Laravel 11 dan ditujukan untuk mengimplementasikan manajemen basis data relasional (MySQL) dengan skema autentikasi komprehensif dari bawaan Laravel Breeze.

Aplikasi ini dikhususkan untuk toko retail/gudang dan telah di-desain menggunakan Tailwind CSS untuk menawarkan User Experience (UX) premium melalui desain Glassmorphism, palet gradien profesional, dan visualisasi Dashboard interaktif.

## Fitur Unggulan

1. Gatekeeper Security: Seluruh data unit dan pelanggan terlindungi; akses hanya diberikan kepada staf terverifikasi.
2. Performance Dashboard: Panel kendali utama yang menampilkan Total Unit Ready, Estimasi Nilai Aset (OTR), dan Status Indent.
3. Inventory Alert: Indikator otomatis untuk suku cadang fast-moving yang menipis atau unit kendaraan dengan stok terbatas (Low Stock Alert).
4. Aesthetic Branding: Penggunaan aset visual honda1.png hingga honda6.png dalam grid asimetris, memberikan impresi katalog digital premium dengan animasi halus.

---

## 💻 Source Code Inti Sistem

_Berikut adalah representasi kode esensial (MVC) yang digunakan di dalam `modul-13/tugas-8`._

### 1. File Konfigurasi Lintas Server (`.env`)

Diatur pada modul ini agar merujuk ke layanan **MySQL Laragon** dengan basis data `sembako_db`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=honda_db
DB_USERNAME=root
DB_PASSWORD=tegal
```

### 2. Algoritme Pengendali Rute (routes/web.php)

Mengarahkan tamu aplikasi langsung ke landing page, sementara kontrol manajemen dilindungi berlapis oleh alias validasi auth.

```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('product.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('product', ProductController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
```

### 3. Migrasi DDL Database (database/migrations/...\_create_products_table.php)

Mendefinisikan skema kolom pendataan barang sembako langsung ke MariaDB/MySQL.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('part_number')->unique();
            $table->string('name');
            $table->string('category'); // e.g., Mesin, Transmisi, Kelistrikan, Body
            $table->decimal('price', 15, 2);
            $table->integer('stock');
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

```

### 4. Pelindung Mass-Assignment (app/Models/Product.php)

Entitas objek model yang bertanggung jawab memvalidasi field mana saja yang diizinkan mendapat perintah Create massal.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'name',
        'category',
        'price',
        'stock',
        'image_url',
        'description',
    ];
}

```

### 5. Controller Logika Bisnis (app/Http/Controllers/ProductController.php)

Menghubungkan Interface (Views) dengan basis data melalui penguraian input form yang kokoh (validated request).

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_number' => 'required|unique:products,part_number',
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image_url' => 'nullable|url',
            'description' => 'nullable',
        ]);

        Product::create($request->all());

        return redirect()->route('product.index')->with('success', 'Suku cadang berhasil ditambahkan ke stok.');
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'part_number' => 'required|unique:products,part_number,' . $product->id,
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image_url' => 'nullable|url',
            'description' => 'nullable',
        ]);

        $product->update($request->all());

        return redirect()->route('product.index')->with('success', 'Data suku cadang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Suku cadang berhasil dihapus dari sistem.');
    }
}

```

### 6. Tampilan Tabel Dasbor Premium (resources/views/product/index.blade.php)

Visualisasi terpadu perihal statistik gudang lengkap dengan badge list unik Tailwind CSS.

```html
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Stok Suku Cadang Honda') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">Daftar Suku Cadang</h3>
            <a
              href="{{ route('product.create') }}"
              class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition"
            >
              + Tambah Suku Cadang
            </a>
          </div>

          @if(session('success'))
          <div
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6"
            role="alert"
          >
            <p>{{ session('success') }}</p>
          </div>
          @endif

          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
              <thead>
                <tr class="bg-gray-100 border-b">
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Gambar
                  </th>
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Part Number
                  </th>
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Nama Barang
                  </th>
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Kategori
                  </th>
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Harga
                  </th>
                  <th class="py-3 px-4 text-left font-semibold text-gray-700">
                    Stok
                  </th>
                  <th class="py-3 px-4 text-center font-semibold text-gray-700">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody>
                @forelse($products as $product)
                <tr class="border-b hover:bg-gray-50 transition">
                  <td class="py-3 px-4">
                    <div
                      class="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500"
                    >
                      @if($product->category == 'Mesin')
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-7 h-7"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41l1.409-1.409m11.192 0 1.409 1.409M9.172 9.172 7.763 7.764m11.191 1.408 1.41-1.41M12 12v3.75m-3.75-3.75v.008H12"
                        />
                      </svg>
                      @elseif($product->category == 'Oli & Pelumas')
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-7 h-7 text-blue-600"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                        />
                      </svg>
                      @elseif($product->category == 'Pengereman')
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-7 h-7 text-red-600"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"
                        />
                      </svg>
                      @elseif($product->category == 'Kelistrikan')
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-7 h-7 text-yellow-500"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"
                        />
                      </svg>
                      @else
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-7 h-7"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-5.25v9"
                        />
                      </svg>
                      @endif
                    </div>
                  </td>
                  <td class="py-3 px-4 font-mono text-sm">
                    {{ $product->part_number }}
                  </td>
                  <td class="py-3 px-4 font-bold">{{ $product->name }}</td>
                  <td class="py-3 px-4">
                    <span
                      class="px-2 py-1 bg-gray-200 rounded-full text-xs font-semibold"
                      >{{ $product->category }}</span
                    >
                  </td>
                  <td class="py-3 px-4 text-red-600 font-bold">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                  </td>
                  <td class="py-3 px-4">
                    <span
                      class="{{ $product->stock <= 5 ? 'text-red-500 font-bold' : 'text-gray-700' }}"
                    >
                      {{ $product->stock }} pcs
                    </span>
                  </td>
                  <td class="py-3 px-4 text-center">
                    <div class="flex justify-center space-x-2">
                      <a
                        href="{{ route('product.edit', $product->id) }}"
                        class="text-blue-600 hover:text-blue-900 font-semibold text-sm"
                        >Edit</a
                      >
                      <form
                        action="{{ route('product.destroy', $product->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus?')"
                      >
                        @csrf @method('DELETE')
                        <button
                          type="submit"
                          class="text-red-600 hover:text-red-900 font-semibold text-sm"
                        >
                          Hapus
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td
                    colspan="7"
                    class="py-10 text-center text-gray-500 italic"
                  >
                    Belum ada data suku cadang.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```

## OUTPUT WEBSITE (SS)

### 1. landing page

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda1.png" />

### 2. Register

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda2.png" />

### 3. Login

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda3.png" />

### 4. Dashboard admin

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda4.png" />

### 5. Tambah Data

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda5.png" />

### 6. Edit Data

<img width="1836" height="770" alt="Screenshot Halaman Produk" src="honda6.png" />
