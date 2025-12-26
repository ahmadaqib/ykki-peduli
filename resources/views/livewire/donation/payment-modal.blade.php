<div>
@if ($isOpen && $campaign)
    <div 
        class="payment-modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        wire:click="closeModal"
        wire:keydown.escape="closeModal"
    >
        <div 
            class="payment-modal-content relative w-full max-w-3xl rounded-2xl bg-white shadow-2xl"
            wire:click.stop
        >
            {{-- Close Button --}}
            <button
                wire:click="closeModal"
                class="absolute right-4 top-4 rounded-full p-2 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-900"
                aria-label="Tutup"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Modal Header --}}
            <div class="border-b border-neutral-200 p-4 sm:p-6">
                <h2 class="font-display text-xl sm:text-2xl font-bold text-neutral-900">
                    Donasi untuk: {{ $campaign['title'] }}
                </h2>
            </div>

            {{-- Tabs Navigation --}}
            <div class="border-b border-neutral-200">
                <div class="flex">
                    <button
                        wire:click="switchTab('donate')"
                        class="flex-1 px-4 py-3 sm:px-6 sm:py-4 text-center text-sm sm:text-base font-semibold transition-colors {{ $activeTab === 'donate' ? 'border-b-2 border-primary-600 text-primary-600' : 'text-neutral-600 hover:text-neutral-900' }}"
                    >
                        Donasi
                    </button>
                    <button
                        wire:click="switchTab('donors')"
                        class="flex-1 px-4 py-3 sm:px-6 sm:py-4 text-center text-sm sm:text-base font-semibold transition-colors {{ $activeTab === 'donors' ? 'border-b-2 border-primary-600 text-primary-600' : 'text-neutral-600 hover:text-neutral-900' }}"
                    >
                        Donatur
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="max-h-[70vh] overflow-y-auto p-4 sm:p-6">
                {{-- Tab: Donasi --}}
                @if ($activeTab === 'donate')
                    {{-- Donation Form --}}
                    <div class="mb-8" x-data="{ 
                        isAnonymous: @entangle('isAnonymous'),
                        formatCurrency(value) {
                            if (!value) return '';
                            const num = parseInt(value.replace(/\D/g, ''));
                            if (isNaN(num)) return '';
                            return new Intl.NumberFormat('id-ID').format(num);
                        },
                        handleAmountInput(event) {
                            const value = event.target.value.replace(/\D/g, '');
                            $wire.set('donationAmount', value);
                        }
                    }">
                        <h3 class="mb-4 font-display text-lg font-semibold text-neutral-900">
                            Form Donasi
                        </h3>
                        
                        <div class="space-y-4">
                            {{-- Jumlah Donasi --}}
                            <div>
                                <label for="donation-amount" class="mb-2 block text-sm font-medium text-neutral-700">
                                    Jumlah Donasi <span class="text-primary-600">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">Rp</span>
                                    <input
                                        type="text"
                                        id="donation-amount"
                                        wire:model.live="donationAmount"
                                        x-on:input="handleAmountInput($event)"
                                        placeholder="Masukkan jumlah donasi"
                                        class="w-full rounded-lg border border-neutral-300 bg-white py-3 pl-12 pr-4 text-neutral-900 placeholder-neutral-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                    />
                                    <div class="mt-1 text-xs font-medium text-primary-600" x-show="$wire.donationAmount && $wire.donationAmount !== ''">
                                        <span x-text="'Rp ' + formatCurrency($wire.donationAmount)"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Checkbox Anonim --}}
                            <div class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="is-anonymous"
                                    wire:model.live="isAnonymous"
                                    class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
                                />
                                <label for="is-anonymous" class="text-sm font-medium text-neutral-700">
                                    Donasi sebagai Anonim
                                </label>
                            </div>

                            {{-- Nama Pendonasi (hidden jika anonim) --}}
                            <div x-show="!isAnonymous" x-transition>
                                <label for="donor-name" class="mb-2 block text-sm font-medium text-neutral-700">
                                    Nama Pendonasi <span class="text-primary-600">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="donor-name"
                                    wire:model="donorName"
                                    placeholder="Masukkan nama Anda"
                                    class="w-full rounded-lg border border-neutral-300 bg-white py-3 px-4 text-neutral-900 placeholder-neutral-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                />
                            </div>

                            {{-- No Telp --}}
                            <div>
                                <label for="donor-phone" class="mb-2 block text-sm font-medium text-neutral-700">
                                    No. Telepon <span class="text-primary-600">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="donor-phone"
                                    wire:model="donorPhone"
                                    placeholder="081234567890"
                                    class="w-full rounded-lg border border-neutral-300 bg-white py-3 px-4 text-neutral-900 placeholder-neutral-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                />
                            </div>

                            {{-- Doa/Pesan --}}
                            <div>
                                <label for="donor-message" class="mb-2 block text-sm font-medium text-neutral-700">
                                    Doa/Pesan (Opsional)
                                </label>
                                <textarea
                                    id="donor-message"
                                    wire:model="donorMessage"
                                    rows="3"
                                    placeholder="Tuliskan doa atau pesan Anda..."
                                    class="w-full rounded-lg border border-neutral-300 bg-white py-3 px-4 text-neutral-900 placeholder-neutral-400 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods Section --}}
                    <div class="border-t border-neutral-200 pt-8">
                        {{-- Bank Transfer Section --}}
                        <div class="mb-8">
                            <h3 class="mb-4 font-display text-lg font-semibold text-neutral-900">
                                Transfer Bank
                            </h3>
                            <div class="space-y-4">
                                @foreach ($this->getBankAccounts() as $bank)
                                    <div class="payment-method-item rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="mb-1 font-semibold text-neutral-900">
                                                    {{ $bank['name'] }}
                                                </div>
                                                <div class="text-sm text-neutral-600">
                                                    {{ $bank['account_name'] }}
                                                </div>
                                                <div class="mt-2 font-mono text-base sm:text-lg font-bold text-primary-600">
                                                    {{ $bank['account_number'] }}
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                class="copy-account-btn w-full sm:w-auto rounded-lg bg-primary-500 px-4 py-2 text-sm sm:text-base font-semibold text-white transition-colors hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                                data-account-number="{{ $bank['account_number'] }}"
                                                data-bank-name="{{ $bank['name'] }}"
                                            >
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- E-Wallet Section --}}
                        <div>
                            <h3 class="mb-4 font-display text-lg font-semibold text-neutral-900">
                                E-Wallet
                            </h3>
                            <div class="space-y-4">
                                @foreach ($this->getEwallets() as $ewallet)
                                    <div class="payment-method-item rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="mb-1 font-semibold text-neutral-900">
                                                    {{ $ewallet['name'] }}
                                                </div>
                                                <div class="text-sm text-neutral-600">
                                                    {{ $ewallet['account_name'] }}
                                                </div>
                                                <div class="mt-2 font-mono text-base sm:text-lg font-bold text-accent-600">
                                                    {{ $ewallet['phone'] }}
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                class="copy-account-btn w-full sm:w-auto rounded-lg bg-accent-500 px-4 py-2 text-sm sm:text-base font-semibold text-white transition-colors hover:bg-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                                                data-account-number="{{ $ewallet['phone'] }}"
                                                data-bank-name="{{ $ewallet['name'] }}"
                                            >
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Instructions --}}
                        <div class="mt-8 rounded-lg bg-primary-50 p-4">
                            <h4 class="mb-2 font-semibold text-neutral-900">
                                Cara Donasi:
                            </h4>
                            <ol class="list-decimal list-inside space-y-1 text-sm text-neutral-700">
                                <li>Isi form donasi di atas</li>
                                <li>Salin nomor rekening atau nomor e-wallet</li>
                                <li>Transfer sesuai nominal yang Anda inginkan</li>
                                <li>Simpan bukti transfer untuk konfirmasi</li>
                                <li>Konfirmasi donasi Anda melalui WhatsApp atau email kami</li>
                            </ol>
                        </div>
                    </div>
                @endif

                {{-- Tab: Donatur --}}
                @if ($activeTab === 'donors')
                    <div>
                        <h3 class="mb-4 font-display text-lg font-semibold text-neutral-900">
                            Daftar Donatur
                        </h3>
                        
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
                                    <div class="rounded-lg border border-neutral-200 bg-white p-4">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="mb-1 flex items-center gap-2">
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
                @endif
            </div>
        </div>
    </div>
@endif
</div>
