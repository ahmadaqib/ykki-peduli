# Yayasan Kesehatan Kanker Indonesia - Website Donasi

Website donasi untuk Yayasan Kesehatan Kanker Indonesia dengan sistem statis yang mudah diupgrade ke database.

## 📋 Daftar Isi

- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Perbedaan dengan Laravel 10](#perbedaan-dengan-laravel-10)
- [Apa itu Livewire?](#apa-itu-livewire)
- [Setup Development](#setup-development)
- [Command Penting](#command-penting)
- [Struktur Project](#struktur-project)
- [Cara Kerja Livewire](#cara-kerja-livewire)
- [Migration ke Database](#migration-ke-database)
- [Tips Development](#tips-development)

---

## 🚀 Teknologi yang Digunakan

### Laravel 12
Framework PHP terbaru dengan struktur yang lebih sederhana dari Laravel 10.

### Livewire 3
**Livewire itu seperti Blade biasa, tapi bisa interaktif tanpa nulis JavaScript!**

Contoh sederhana:
```php
// File biasa di Blade (Laravel 10) - kalau mau interaktif butuh JavaScript
<button onclick="alert('Hello')">Klik</button>

// Dengan Livewire - bisa langsung pakai PHP
<button wire:click="sayHello">Klik</button>

// Di file PHP component
public function sayHello() {
    session()->flash('message', 'Hello!');
}
```

### Tailwind CSS 4
Framework CSS untuk styling. Langsung pakai class di HTML tanpa nulis CSS manual.

### Laravel Pint
Auto-formatter kode PHP. Otomatis rapikan kode sesuai standar Laravel.

### Alpine.js
JavaScript kecil untuk animasi dan interaksi simple. Sudah include di Livewire.

---

## 🆕 Perbedaan dengan Laravel 10

### 1. Struktur File Lebih Sederhana

**Laravel 10:**
```
app/
  Http/
    Kernel.php          ← Ada file ini
    Middleware/         ← Middleware di sini
  Console/
    Kernel.php          ← Ada file ini juga
```

**Laravel 12:**
```
bootstrap/
  app.php             ← Semua config di sini
  providers.php       ← Provider di sini
routes/
  web.php             ← Route langsung di sini
  console.php         ← Console route di sini
```

### 2. Tidak Ada Kernel.php
Semua middleware dan config sekarang di `bootstrap/app.php`.

### 3. Route Otomatis
Livewire component bisa jadi route langsung:
```php
Route::get('/campaign/{id}', App\Livewire\CampaignDetail::class);
```

### 4. Command Auto-Register
File di `app/Console/Commands/` otomatis ke-detect, tidak perlu register manual.

---

## 🎯 Apa itu Livewire?

**Livewire = Blade + Interaktivitas tanpa JavaScript manual**

### Blade Biasa (Laravel 10)
```blade
<!-- Harus reload halaman atau pakai AJAX manual -->
<form action="/submit" method="POST">
    @csrf
    <input type="text" name="name">
    <button>Submit</button>
</form>
```

### Dengan Livewire
```blade
<!-- Tidak perlu reload! Otomatis update -->
<form wire:submit="save">
    <input type="text" wire:model="name">
    <button>Submit</button>
</form>
```

```php
// File component PHP
class ContactForm extends Component {
    public $name = '';
    
    public function save() {
        // Langsung jalan tanpa reload halaman
        $this->validate(['name' => 'required']);
        Contact::create(['name' => $this->name]);
        session()->flash('message', 'Berhasil!');
    }
}
```

### Komponen Livewire Seperti Blade Include

**Blade Include (cara lama):**
```blade
@include('components.alert', ['message' => 'Hello'])
```

**Livewire Component:**
```blade
<livewire:alert :message="'Hello'" />
<!-- ATAU -->
@livewire('alert', ['message' => 'Hello'])
```

Bedanya: Livewire component bisa **reaktif** (update sendiri tanpa reload).

---

## 🎯 Kenapa Tidak Ada Controller? Semua di Livewire?

### Controller vs Livewire Component

**Ini pertanyaan penting untuk developer yang terbiasa dengan Laravel 10!**

### Cara Tradisional (Dengan Controller)

```php
// routes/web.php
Route::get('/campaigns', [CampaignController::class, 'index']);
Route::post('/campaigns', [CampaignController::class, 'store']);

// app/Http/Controllers/CampaignController.php
class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaigns.index', compact('campaigns'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([...]);
        Campaign::create($validated);
        return redirect()->back();
    }
}

// resources/views/campaigns/index.blade.php
<form action="/campaigns" method="POST">
    @csrf
    <input type="text" name="title">
    <button>Submit</button>
</form>
```

**Masalah:**
- Butuh reload halaman
- Harus atur route untuk setiap action
- Form submit dengan POST request
- Validasi error hilang saat reload

### Cara Livewire (Tanpa Controller)

```php
// routes/web.php
Route::get('/campaigns', App\Livewire\CampaignList::class);

// app/Livewire/CampaignList.php
class CampaignList extends Component
{
    public $title = '';
    
    public function save()
    {
        $this->validate(['title' => 'required']);
        Campaign::create(['title' => $this->title]);
        $this->title = ''; // Reset form
        session()->flash('message', 'Berhasil!');
    }
    
    public function render()
    {
        return view('livewire.campaign-list', [
            'campaigns' => Campaign::all()
        ]);
    }
}

// resources/views/livewire/campaign-list.blade.php
<div>
    <form wire:submit="save">
        <input type="text" wire:model="title">
        <button>Submit</button>
    </form>
    
    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif
</div>
```

**Keuntungan:**
- ✅ Tidak reload halaman
- ✅ Controller + View jadi satu
- ✅ Logic dan tampilan dalam 1 component
- ✅ Validasi error tetap ada
- ✅ Form auto-reset
- ✅ State management otomatis

### Kapan Pakai Controller?

**✅ Pakai Controller untuk:**
- API endpoint
- Redirect sederhana
- Download file
- Webhook dari pihak ketiga
- Background job yang tidak perlu tampilan

**Contoh yang masih pakai Controller:**
```php
// Masih OK pakai controller
Route::post('/webhook/payment', [WebhookController::class, 'handle']);
Route::get('/download/report', [ReportController::class, 'download']);
Route::post('/api/data', [ApiController::class, 'getData']);
```

### Kapan Pakai Livewire?

**✅ Pakai Livewire untuk:**
- Halaman dengan form interaktif
- CRUD operations
- Filter/search realtime
- Modal dengan data dinamis
- Dashboard dengan auto-refresh
- Anything yang butuh interaktivity

### Struktur Project Ini

```
app/
  ├── Http/
  │   └── Controllers/
  │       └── Controller.php           ← Base controller (minimal)
  │
  └── Livewire/                        ← SEMUA LOGIC DI SINI
      └── Donation/
          ├── CampaignCard.php         ← Component untuk card
          ├── CampaignDetail.php       ← Component untuk detail page
          ├── PaymentModal.php         ← Component untuk modal
          └── DonationNotifications.php ← Component untuk notifikasi
```

### Perbandingan File Count

**Dengan Controller (Laravel 10):**
```
Controller (1 file)
├── routes/web.php (banyak route)
├── app/Http/Controllers/CampaignController.php
├── resources/views/campaigns/index.blade.php
├── resources/views/campaigns/create.blade.php
├── resources/views/campaigns/edit.blade.php
└── public/js/campaign.js (untuk interaktivity)

Total: 6 file untuk 1 fitur
```

**Dengan Livewire:**
```
Livewire (1 component)
├── routes/web.php (1 route saja)
├── app/Livewire/Campaign.php (semua logic)
└── resources/views/livewire/campaign.blade.php

Total: 3 file untuk 1 fitur (50% lebih sedikit!)
```

### Kesimpulan

**Livewire Component = Controller + View + JavaScript dalam 1 file!**

- **Controller**: Logic (validation, save, delete, dll) ✅
- **View**: Tampilan (HTML, Blade) ✅
- **JavaScript**: Interactivity (tanpa nulis manual) ✅

**Jadi tidak butuh controller terpisah karena Livewire component sudah handle semua!**

---

## ⚙️ Setup Development

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (Opsional - Saat Ini Masih Statis)
```bash
# Kalau mau pakai database
php artisan migrate
```

### 4. Run Development
```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Vite untuk CSS/JS
npm run dev
```

Website jalan di: `http://localhost:8000`

---

## 🔧 Command Penting

### Development Sehari-hari

```bash
# Buat Livewire Component Baru
php artisan make:livewire NamaComponent

# Format Kode Otomatis (WAJIB sebelum commit!)
vendor/bin/pint

# Check format tanpa ubah file
vendor/bin/pint --test

# Format file yang baru diubah saja
vendor/bin/pint --dirty

# Run Test
php artisan test

# Run test spesifik
php artisan test --filter=NamaTest
```

### Build untuk Production

```bash
# Build CSS/JS final
npm run build

# Clear semua cache
php artisan optimize:clear
```

---

## 📁 Struktur Project

### Data Statis (Sementara)
```
app/Services/
  ├── CampaignDataService.php      ← Data campaign (array)
  └── PaymentDataService.php       ← Data payment (array)
```

**Catatan:** Ada TODO comment di setiap function yang harus diubah saat migrasi ke database.

### Livewire Components
```
app/Livewire/Donation/
  ├── CampaignCard.php              ← Card campaign di homepage
  ├── CampaignDetail.php            ← Halaman detail campaign
  ├── PaymentModal.php              ← Modal untuk donasi
  └── DonationNotifications.php    ← Toast notification

resources/views/livewire/donation/
  ├── campaign-card.blade.php
  ├── campaign-detail.blade.php
  ├── payment-modal.blade.php
  └── donation-notifications.blade.php
```

### CSS & JavaScript
```
resources/css/
  ├── app.css                       ← Main CSS
  ├── components/                   ← CSS per component
  └── pages/                        ← CSS per page

resources/js/
  ├── app.js                        ← Main JS
  └── components/                   ← JS per component
```

### Routes
```php
// routes/web.php
Route::get('/', fn() => view('home'))->name('home');
Route::get('/campaign/{slug}', CampaignDetail::class)->name('campaign.detail');
```

---

## 💡 Cara Kerja Livewire

### 1. Membuat Component Baru

```bash
php artisan make:livewire Donation/MyComponent
```

Akan membuat:
- `app/Livewire/Donation/MyComponent.php` (Logic)
- `resources/views/livewire/donation/my-component.blade.php` (View)

### 2. Struktur Component PHP

```php
<?php

namespace App\Livewire\Donation;

use Livewire\Component;

class MyComponent extends Component
{
    // Property = data yang bisa diakses di view
    public $count = 0;
    public $name = '';
    
    // Method = function yang bisa dipanggil dari view
    public function increment()
    {
        $this->count++;
    }
    
    // Mount = seperti __construct, jalan pertama kali
    public function mount($initialCount = 0)
    {
        $this->count = $initialCount;
    }
    
    // Render = wajib ada, return view
    public function render()
    {
        return view('livewire.donation.my-component');
    }
}
```

### 3. View Component Blade

```blade
{{-- WAJIB: Harus ada 1 root element --}}
<div>
    {{-- Akses property langsung --}}
    <p>Count: {{ $count }}</p>
    <p>Name: {{ $name }}</p>
    
    {{-- Panggil method dengan wire:click --}}
    <button wire:click="increment">
        Tambah
    </button>
    
    {{-- Two-way binding dengan wire:model --}}
    <input type="text" wire:model="name">
    
    {{-- Live update (realtime) --}}
    <input type="text" wire:model.live="name">
</div>
```

### 4. Pakai Component

```blade
{{-- Di file blade lain --}}
<livewire:donation.my-component :initialCount="10" />

{{-- Atau --}}
@livewire('donation.my-component', ['initialCount' => 10])
```

### 5. Wire Directives Penting

| Directive | Fungsi | Contoh |
|-----------|--------|--------|
| `wire:click` | Event click | `<button wire:click="save">` |
| `wire:submit` | Submit form | `<form wire:submit="save">` |
| `wire:model` | Two-way binding | `<input wire:model="name">` |
| `wire:model.live` | Update realtime | `<input wire:model.live="search">` |
| `wire:loading` | Show saat loading | `<div wire:loading>Loading...</div>` |
| `wire:poll` | Auto refresh | `<div wire:poll.5s>` (tiap 5 detik) |

### 6. Dispatch Event (Komunikasi Antar Component)

```php
// Component A - kirim event
$this->dispatch('user-created', userId: $user->id);

// Component B - terima event
use Livewire\Attributes\On;

#[On('user-created')]
public function handleUserCreated($userId)
{
    // Do something
}
```

---

## 🗄️ Migration ke Database

Saat ini data masih statis di `CampaignDataService.php`. Untuk migrasi ke database:

### 1. Buat Migration

```bash
php artisan make:migration create_campaigns_table
php artisan make:migration create_donations_table
```

### 2. Define Schema di Migration

Lihat TODO comment di `app/Services/CampaignDataService.php` baris 13-48 untuk struktur tabel yang dibutuhkan.

### 3. Buat Model

```bash
php artisan make:model Campaign
php artisan make:model Donation
```

### 4. Replace Service dengan Model

**Sebelum (Statis):**
```php
$campaigns = CampaignDataService::getCampaigns();
```

**Sesudah (Database):**
```php
$campaigns = Campaign::where('is_active', true)->get();
```

Semua TODO comment sudah ada di file service untuk panduan detail.

---

## 🎓 Tips Development

### 1. Testing Livewire

```php
use Livewire\Livewire;

public function test_component_works()
{
    Livewire::test(MyComponent::class)
        ->set('name', 'John')              // Set property
        ->call('save')                     // Panggil method
        ->assertSee('Success')             // Cek output
        ->assertDispatched('saved');       // Cek event
}
```

### 2. Debugging Livewire

```php
// Di component
public function mount()
{
    dd($this->campaign);           // Debug di server
    ray($this->campaign);          // Debug dengan Ray (optional)
}
```

```blade
{{-- Di view --}}
@dump($campaign)                  {{-- Tampilkan di browser --}}
```

### 3. Format Code Otomatis

**Sebelum commit, SELALU jalankan:**
```bash
vendor/bin/pint --dirty
```

Ini akan otomatis rapikan code sesuai standar Laravel.

### 4. Watch untuk Auto-reload

```bash
# Terminal 1
php artisan serve

# Terminal 2 - auto compile CSS/JS saat file berubah
npm run dev
```

Browser akan auto-reload saat ada perubahan.

### 5. Livewire Devtools

Install browser extension "Livewire Devtools" untuk debug lebih mudah.

---

## 🔒 Code Style

Project ini menggunakan **Laravel Pint** untuk menjaga konsistensi code.

### Aturan Penting:

1. ✅ **Selalu pakai type hints**
   ```php
   // ✅ Good
   public function formatCurrency(int|float $amount): string
   
   // ❌ Bad
   public function formatCurrency($amount)
   ```

2. ✅ **Property promotion di constructor**
   ```php
   // ✅ Good
   public function __construct(
       public Campaign $campaign,
       public User $user,
   ) {}
   
   // ❌ Bad
   private $campaign;
   public function __construct(Campaign $campaign) {
       $this->campaign = $campaign;
   }
   ```

3. ✅ **Selalu pakai curly braces**
   ```php
   // ✅ Good
   if ($condition) {
       doSomething();
   }
   
   // ❌ Bad
   if ($condition) doSomething();
   ```

4. ✅ **PHPDoc untuk array shapes**
   ```php
   /**
    * @return array<int, array<string, mixed>>
    */
   public function getCampaigns(): array
   ```

---

## 📚 Resource Belajar

### Livewire
- [Dokumentasi Livewire 3](https://livewire.laravel.com/docs) - **WAJIB BACA**
- [Livewire Screencasts](https://laracasts.com/series/livewire-3) - Video tutorial

### Laravel 12
- [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade)
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)

### Tailwind CSS
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Tailwind Cheat Sheet](https://nerdcave.com/tailwind-cheat-sheet)

---

## 🤝 Kontribusi

### Workflow Development

1. Pull latest dari main
   ```bash
   git pull origin main
   ```

2. Buat branch baru
   ```bash
   git checkout -b feature/nama-fitur
   ```

3. Development (edit code)

4. Format code
   ```bash
   vendor/bin/pint --dirty
   ```

5. Run test
   ```bash
   php artisan test
   ```

6. Commit & Push
   ```bash
   git add .
   git commit -m "feat: tambah fitur xyz"
   git push origin feature/nama-fitur
   ```

### Commit Message Format

```
feat: tambah fitur baru
fix: perbaiki bug
docs: update dokumentasi
style: format code
refactor: refactor code
test: tambah test
```

---

## ⚠️ Troubleshooting

### 1. Livewire Error: "Multiple root elements detected"
**Solusi:** View component Livewire HARUS punya 1 root element.

```blade
{{-- ❌ Bad - 2 root elements --}}
<div>Content 1</div>
<div>Content 2</div>

{{-- ✅ Good - 1 root element --}}
<div>
    <div>Content 1</div>
    <div>Content 2</div>
</div>
```

### 2. CSS/JS tidak update
**Solusi:** Clear cache dan rebuild

```bash
npm run build
php artisan optimize:clear
```

### 3. Error "Class not found"
**Solusi:** Dump autoload

```bash
composer dump-autoload
```

### 4. Vite Error: "Unable to locate file"
**Solusi:** Run Vite development server

```bash
npm run dev
# Atau untuk production
npm run build
```

---

## 📞 Kontak

Untuk pertanyaan atau bantuan:
- Email: [masukkan email]
- Phone: 0813-4000-0565

---

## 📝 Catatan Penting untuk Developer Baru

### Perbedaan Utama Livewire vs Blade Biasa:

1. **Blade** = Static template, butuh reload atau AJAX manual
2. **Livewire** = Dynamic component, auto update tanpa reload

### Kapan Pakai Livewire?
- ✅ Form yang butuh validasi realtime
- ✅ Modal yang perlu data dari server
- ✅ List yang bisa filter/sort tanpa reload
- ✅ Dashboard dengan auto-refresh data

### Kapan Pakai Blade Biasa?
- ✅ Halaman static (About, Contact)
- ✅ Email template
- ✅ Component yang tidak butuh interaksi

### Key Takeaways:
1. **Livewire component = Controller + View + JavaScript**
2. **Tidak butuh controller terpisah** untuk halaman interaktif
3. Livewire component = PHP class + Blade view
4. Property public = accessible di view
5. Method public = callable dari view
6. Gunakan `wire:` directive untuk interaksi
7. Selalu 1 root element di view
8. Format code dengan Pint sebelum commit
9. Jalankan test sebelum push

### Mental Model untuk Developer Laravel 10:

```
Laravel 10:
Route → Controller → View → (Manual JavaScript)

Laravel 12 + Livewire:
Route → Livewire Component (Controller + View + JavaScript built-in)
```

**Intinya: Livewire menyederhanakan workflow, bukan mengganti controller sepenuhnya.**

Controller masih ada dan dipakai untuk hal-hal tertentu (API, webhook, dll), tapi untuk UI interaktif, Livewire lebih efisien.

**Good luck! 🚀**

