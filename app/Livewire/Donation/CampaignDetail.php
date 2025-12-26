<?php

namespace App\Livewire\Donation;

use App\Services\CampaignDataService;
use Livewire\Component;

class CampaignDetail extends Component
{
    /**
     * Campaign ID or slug.
     */
    public int|string $identifier;

    /**
     * Campaign data.
     *
     * TODO DB: Replace with Campaign model instance
     */
    public ?array $campaign = null;

    /**
     * Mount the component.
     */
    public function mount(int|string $identifier): void
    {
        $this->identifier = $identifier;
        $this->loadCampaign();
    }

    /**
     * Load campaign data.
     */
    public function loadCampaign(): void
    {
        if (is_numeric($this->identifier)) {
            $this->campaign = CampaignDataService::getCampaignById((int) $this->identifier);
        } else {
            // If using slug, find by slug
            $campaigns = CampaignDataService::getCampaigns();
            foreach ($campaigns as $campaign) {
                if ($campaign['slug'] === $this->identifier) {
                    $this->campaign = $campaign;
                    break;
                }
            }
        }

        if (! $this->campaign) {
            abort(404, 'Campaign not found');
        }
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageProperty(): float
    {
        if (! $this->campaign || $this->campaign['target'] <= 0) {
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
     * Get donors for this campaign.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getDonors(): array
    {
        if (! $this->campaign) {
            return [];
        }

        return CampaignDataService::getDonorsByCampaignId($this->campaign['id']);
    }

    /**
     * Open payment modal.
     */
    public function openPaymentModal(): void
    {
        $this->dispatch('open-payment-modal', campaignId: $this->campaign['id']);
    }

    public function render()
    {
        return view('livewire.donation.campaign-detail')
            ->layout('layouts.campaign-detail');
    }
}
