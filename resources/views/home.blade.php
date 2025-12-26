<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-white min-h-screen">
    {{-- Navigation --}}
    <nav class="border-b border-neutral-200 bg-white/95 backdrop-blur-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img 
                        src="{{ asset('img/ykki.png') }}" 
                        alt="Yayasan Kesehatan Kanker Indonesia Logo" 
                        class="h-8 w-auto sm:h-10"
                    >
                    <div class="flex flex-col">
                        <span class="font-display text-sm sm:text-lg font-bold text-neutral-900 leading-tight">Yayasan Kesehatan Kanker Indonesia</span>
                        <span class="text-xs text-neutral-600 font-medium hidden sm:block">Mewujudkan Indonesia Bebas Kanker</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-neutral-700 hover:text-neutral-900">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-700 hover:text-neutral-900">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-primary-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-primary-600">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-section relative overflow-hidden py-20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ef4444\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
        <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h1 class="mb-6 font-display text-5xl font-bold leading-tight text-neutral-900 sm:text-6xl lg:text-7xl">
                Bersama Membangun
                <span class="text-primary-600">
                    Masa Depan
                </span>
                yang Lebih Baik
            </h1>
            <p class="mx-auto mb-8 max-w-2xl text-lg text-neutral-600 sm:text-xl">
                Setiap donasi Anda memberikan harapan dan perubahan nyata bagi mereka yang membutuhkan. 
                Mari kita wujudkan kebaikan bersama.
            </p>
            <a 
                href="#campaigns" 
                class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-8 py-4 font-semibold text-white shadow-lg transition-all hover:bg-primary-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                aria-label="Scroll ke bagian kampanye donasi"
            >
                Lihat Kampanye
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </a>
        </div>
    </section>

    {{-- Campaigns Section --}}
    <section id="campaigns" class="campaigns-section py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="mb-4 font-display text-4xl font-bold text-neutral-900">
                    Kampanye Donasi
                </h2>
                <p class="mx-auto max-w-2xl text-neutral-600">
                    Pilih kampanye yang ingin Anda dukung dan berikan kontribusi untuk perubahan yang berarti
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach (\App\Services\CampaignDataService::getCampaigns() as $campaign)
                    <livewire:donation.campaign-card :campaign="$campaign" :key="'campaign-'.$campaign['id']" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-neutral-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-3">
                <div>
                    <div class="mb-4 flex items-center gap-2 sm:gap-3">
                        <img 
                            src="{{ asset('img/ykki.png') }}" 
                            alt="Yayasan Kesehatan Kanker Indonesia Logo" 
                            class="h-8 w-auto sm:h-10"
                        >
                        <div class="flex flex-col">
                            <span class="font-display text-sm sm:text-lg font-bold text-neutral-900 leading-tight">Yayasan Kesehatan Kanker Indonesia</span>
                            <span class="text-xs text-neutral-600 font-medium">Mewujudkan Indonesia Bebas Kanker</span>
                        </div>
                    </div>
                    <p class="text-sm text-neutral-600">
                        Membangun masa depan yang lebih baik melalui aksi nyata dan kepedulian bersama.
                    </p>
                </div>
                <div>
                    <h3 class="mb-4 font-semibold text-neutral-900">Kontak</h3>
                    <ul class="space-y-2 text-sm text-neutral-600">
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 shrink-0 text-primary-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Jl. Bumi 21 Jl. Sultan Alauddin No.7 Blok D4, Gn. Sari, Kec. Rappocini, Kota Makassar, Sulawesi Selatan 90221</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>0813-4000-0565</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Tutup · Buka Sab pukul 08.00</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-5 w-5 shrink-0 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Provinsi: Sulawesi Selatan</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 font-semibold text-neutral-900">Ikuti Kami</h3>
                    <div class="flex gap-4">
                        <a href="#" class="text-neutral-600 transition-colors hover:text-primary-600">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-neutral-600 transition-colors hover:text-primary-600">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.467.398.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-neutral-600 transition-colors hover:text-primary-600">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-neutral-200 pt-8 text-center text-sm text-neutral-600">
                <p>&copy; {{ date('Y') }} Ykki Peduli. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Livewire Components --}}
    <livewire:donation.payment-modal />
    <livewire:donation.donation-notifications />

    @vite(['resources/js/app.js', 'resources/js/pages/home.js', 'resources/js/components/payment-modal.js', 'resources/js/components/donation-toast.js'])
</body>
</html>

