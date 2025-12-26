<?php

namespace App\Livewire\Donation;

use Livewire\Component;

class CampaignCard extends Component
{
    /**
     * Campaign data.
     *
     * TODO DB: Replace with Campaign model instance
     * When migrating to DB, accept Campaign $campaign in mount()
     */
    public array $campaign;

    /**
     * Mount the component.
     *
     * @param  array<string, mixed>  $campaign
     */
    public function mount(array $campaign): void
    {
        $this->campaign = $campaign;
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageProperty(): float
    {
        if ($this->campaign['target'] <= 0) {
            return 0;
        }

        return min(100, ($this->campaign['collected'] / $this->campaign['target']) * 100);
    }

    /**
     * Format currency to Indonesian Rupiah.
     */
    public function formatCurrency(int|float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    /**
     * Get campaign detail URL.
     */
    public function getCampaignUrl(): string
    {
        return route('campaign.detail', $this->campaign['slug']);
    }

    public function render()
    {
        return view('livewire.donation.campaign-card');
    }
}
