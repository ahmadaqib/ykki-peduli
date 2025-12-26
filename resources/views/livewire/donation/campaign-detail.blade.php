<div>
    @if ($campaign)
        {{-- Hero Image Section --}}
        <div class="relative h-64 w-full overflow-hidden bg-neutral-100 sm:h-96">
            <img 
                src="{{ $campaign['image'] }}" 
                alt="{{ $campaign['title'] }}"
                class="h-full w-full object-cover"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 300\'%3E%3Crect fill=\'%23ef4444\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23fff\' font-family=\'Arial\' font-size=\'24\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E{{ urlencode($campaign['title']) }}%3C/text%3E%3C/svg%3E'"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            
            {{-- Back Button --}}
            <div class="absolute left-4 top-4">
                <a 
                    href="{{ route('home') }}#campaigns" 
                    class="flex items-center gap-2 rounded-lg bg-white/90 px-4 py-2 text-sm font-semibold text-neutral-900 transition-colors hover:bg-white"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                {{-- Campaign Title & Info --}}
                <div class="mb-8">
                    <h1 class="mb-4 font-display text-3xl font-bold text-neutral-900 sm:text-4xl lg:text-5xl">
                        {{ $campaign['title'] }}
                    </h1>
                    
                    {{-- Progress Section --}}
                    <div class="mb-6 rounded-xl bg-white p-6 shadow-lg">
                        <div class="mb-4">
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-neutral-700">Terkumpul</span>
                                <span class="font-bold text-primary-600">{{ $this->formatCurrency($campaign['collected']) }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-neutral-200">
                                <div 
                                    class="h-full rounded-full bg-primary-600 transition-all duration-1000 ease-out"
                                    style="width: {{ $this->progressPercentage }}%"
                                ></div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-xs text-neutral-600">
                                <span>Target: {{ $this->formatCurrency($campaign['target']) }}</span>
                                <span class="font-semibold text-primary-600">{{ number_format($this->progressPercentage, 1) }}% tercapai</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 border-t border-neutral-200 pt-4 sm:grid-cols-3">
                            <div>
                                <div class="text-2xl font-bold text-primary-600">{{ $campaign['donors_count'] }}</div>
                                <div class="text-sm text-neutral-600">Donatur</div>
                            </div>
                            @if(isset($campaign['deadline']))
                                <div>
                                    <div class="text-lg font-bold text-neutral-900">
                                        {{ \Carbon\Carbon::parse($campaign['deadline'])->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-neutral-600">Deadline</div>
                                </div>
                            @endif
                            <div class="col-span-2 sm:col-span-1">
                                <button
                                    wire:click="openPaymentModal"
                                    class="w-full rounded-lg bg-primary-600 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-300 hover:bg-primary-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                >
                                    Donasi Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description Section --}}
                <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
                    <h2 class="mb-4 font-display text-2xl font-bold text-neutral-900">Tentang Kampanye</h2>
                    <div class="prose max-w-none text-neutral-700">
                        <p class="text-base leading-relaxed whitespace-pre-line">{{ $campaign['description'] }}</p>
                    </div>
                </div>

                {{-- Situation & Condition Section --}}
                @if(isset($campaign['situation']) || isset($campaign['condition']))
                    <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
                        <h2 class="mb-4 font-display text-2xl font-bold text-neutral-900">Situasi & Kondisi</h2>
                        <div class="space-y-4 text-neutral-700">
                            @if(isset($campaign['situation']))
                                <div class="rounded-lg bg-primary-50 p-4">
                                    <h3 class="mb-2 font-semibold text-primary-900 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Situasi Saat Ini
                                    </h3>
                                    <p class="text-sm leading-relaxed whitespace-pre-line">
                                        {{ $campaign['situation'] }}
                                    </p>
                                </div>
                            @endif
                            @if(isset($campaign['condition']))
                                <div class="rounded-lg bg-accent-50 p-4">
                                    <h3 class="mb-2 font-semibold text-accent-900 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Dampak Donasi Anda
                                    </h3>
                                    <p class="text-sm leading-relaxed whitespace-pre-line">
                                        {{ $campaign['condition'] }}
                                    </p>
                                </div>
                            @endif
                            <div class="rounded-lg bg-neutral-50 p-4">
                                <h3 class="mb-2 font-semibold text-neutral-900 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Komitmen Transparansi
                                </h3>
                                <p class="text-sm leading-relaxed">
                                    Kami menjamin bahwa setiap rupiah yang Anda donasikan akan digunakan dengan sebaik-baiknya untuk membantu mereka yang membutuhkan. Transparansi dan akuntabilitas adalah prioritas utama kami. Laporan penggunaan dana akan disampaikan secara berkala.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Donors Section --}}
                <div class="mb-8 rounded-xl bg-white p-6 shadow-lg">
                    <h2 class="mb-4 font-display text-2xl font-bold text-neutral-900">Daftar Donatur</h2>
                    
                    @php
                        $donors = $this->getDonors();
                    @endphp

                    @if (empty($donors))
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="mt-4 text-sm text-neutral-600">
                                Belum ada donatur untuk kampanye ini.
                            </p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($donors as $donor)
                                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="flex-1">
                                            <div class="mb-2 flex items-center gap-3">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600">
                                                    <span class="text-sm font-semibold text-white">
                                                        {{ $donor['is_anonymous'] ? 'A' : strtoupper(substr($donor['donor_name'], 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-neutral-900">
                                                        {{ $donor['is_anonymous'] ? 'Anonim' : $donor['donor_name'] }}
                                                    </div>
                                                    <div class="text-xs text-neutral-500">
                                                        {{ \Carbon\Carbon::parse($donor['donated_at'])->format('d M Y, H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($donor['message'])
                                                <p class="mt-2 text-sm text-neutral-600 italic">
                                                    "{{ $donor['message'] }}"
                                                </p>
                                            @endif
                                        </div>
                                        <div class="sm:ml-4 sm:text-right">
                                            <div class="font-bold text-primary-600">
                                                {{ $this->formatCurrency($donor['amount']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment Modal --}}
        <livewire:donation.payment-modal />
    @else
        <div class="container mx-auto px-4 py-16 text-center">
            <h1 class="mb-4 font-display text-3xl font-bold text-neutral-900">Campaign Tidak Ditemukan</h1>
            <a href="{{ route('home') }}" class="inline-block rounded-lg bg-primary-600 px-6 py-3 font-semibold text-white transition-colors hover:bg-primary-700">
                Kembali ke Beranda
            </a>
        </div>
    @endif
</div>
