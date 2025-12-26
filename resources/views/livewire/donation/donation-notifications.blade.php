<div 
    @if (!$isDismissed)
        wire:poll.8s="getNextDonation"
    @endif
    class="donation-toast-container"
>
    @if ($currentDonation && !$isDismissed)
        <div 
            class="donation-toast fixed z-50 w-full max-w-sm"
            wire:key="toast-{{ $currentIndex }}"
            x-data="{ 
                show: true,
                init() {
                    // Check if dismissed in localStorage
                    if (localStorage.getItem('donationToastDismissed') === 'true') {
                        this.show = false;
                        $wire.dismissNotifications();
                        return;
                    }
                    
                    // Auto-hide after 4 seconds on mobile, 5 seconds on desktop, then get next donation
                    const timeout = window.innerWidth < 640 ? 4000 : 5000;
                    setTimeout(() => {
                        if (this.show) {
                            this.show = false;
                            setTimeout(() => {
                                $wire.getNextDonation();
                            }, 300);
                        }
                    }, timeout);
                }
            }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
        >
            <div class="rounded-xl bg-white p-3 sm:p-4 shadow-2xl ring-1 ring-neutral-200">
                <div class="flex items-start gap-2 sm:gap-3">
                    {{-- Icon --}}
                    <div class="flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-full bg-primary-600">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="mb-1 text-xs sm:text-sm font-semibold text-neutral-900 truncate">
                            🎉 {{ $currentDonation['donor_name'] }}
                        </div>
                        <div class="text-xs sm:text-sm text-neutral-600">
                            baru saja berdonasi
                            <span class="font-bold text-primary-600">
                                {{ $this->formatCurrency($currentDonation['amount']) }}
                            </span>
                        </div>
                        <div class="mt-1 text-xs text-neutral-500 line-clamp-1">
                            untuk <span class="font-medium">{{ $currentDonation['campaign_title'] }}</span>
                        </div>
                        <div class="mt-1 text-xs text-neutral-400">
                            {{ $currentDonation['donated_at'] }}
                        </div>
                    </div>

                    {{-- Close Button --}}
                    <button
                        @click="
                            show = false;
                            localStorage.setItem('donationToastDismissed', 'true');
                            $wire.dismissNotifications();
                        "
                        class="shrink-0 rounded-full p-1 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-900"
                        aria-label="Tutup"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
