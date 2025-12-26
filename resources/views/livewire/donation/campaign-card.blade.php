@php
    $progressPercentage = $this->progressPercentage;
    $formattedCollected = $this->formatCurrency($this->campaign['collected']);
    $formattedTarget = $this->formatCurrency($this->campaign['target']);
@endphp

<div class="campaign-card group relative overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl">
    {{-- Campaign Image --}}
    <div class="relative h-48 w-full overflow-hidden bg-neutral-100">
        <a href="{{ route('campaign.detail', $campaign['slug']) }}" class="block h-full w-full">
            <img 
                src="{{ $campaign['image'] }}" 
                alt="{{ $campaign['title'] }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 300\'%3E%3Crect fill=\'%23ef4444\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23fff\' font-family=\'Arial\' font-size=\'24\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dominant-baseline=\'middle\'%3E{{ urlencode($campaign['title']) }}%3C/text%3E%3C/svg%3E'"
            >
        </a>
        
        {{-- Progress Badge --}}
        <div class="absolute top-4 right-4 rounded-full bg-white/90 px-3 py-1 text-sm font-semibold text-primary-600">
            {{ number_format($progressPercentage, 1) }}%
        </div>
    </div>

    {{-- Campaign Content --}}
    <div class="p-6">
        <a href="{{ route('campaign.detail', $campaign['slug']) }}" class="block">
            {{-- Title --}}
            <h3 class="mb-2 font-display text-xl font-bold text-neutral-900 line-clamp-2 hover:text-primary-600 transition-colors">
                {{ $campaign['title'] }}
            </h3>

            {{-- Description --}}
            <p class="mb-4 text-sm text-neutral-600 line-clamp-2">
                {{ $campaign['description'] }}
            </p>
        </a>

        {{-- Progress Bar --}}
        <div class="mb-4">
            <div class="mb-2 flex items-center justify-between text-xs">
                <span class="font-medium text-neutral-700">Terkumpul</span>
                <span class="font-semibold text-primary-600">{{ $formattedCollected }}</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-neutral-200">
                <div 
                    class="campaign-progress h-full rounded-full bg-primary-600 transition-all duration-1000 ease-out"
                    style="width: {{ $progressPercentage }}%"
                    data-progress="{{ $progressPercentage }}"
                ></div>
            </div>
            <div class="mt-1 flex items-center justify-between text-xs text-neutral-500">
                <span>Target: {{ $formattedTarget }}</span>
                <span>{{ $campaign['donors_count'] }} donatur</span>
            </div>
        </div>

        {{-- Deadline --}}
        @if(isset($campaign['deadline']))
            <div class="mb-4 flex items-center gap-2 text-xs text-neutral-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Deadline: {{ \Carbon\Carbon::parse($campaign['deadline'])->format('d M Y') }}</span>
            </div>
        @endif

        {{-- Donate Button --}}
        <a
            href="{{ route('campaign.detail', $campaign['slug']) }}"
            class="block w-full rounded-lg bg-primary-600 px-6 py-3 text-center font-semibold text-white shadow-lg transition-all duration-300 hover:bg-primary-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            aria-label="Lihat detail dan donasi untuk {{ $campaign['title'] }}"
        >
            Lihat Detail & Donasi
        </a>
    </div>
</div>
