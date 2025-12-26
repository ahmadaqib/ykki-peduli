<?php

namespace App\Livewire\Donation;

use App\Services\CampaignDataService;
use App\Services\PaymentDataService;
use Livewire\Component;

class PaymentModal extends Component
{
    /**
     * Whether the modal is open.
     */
    public bool $isOpen = false;

    /**
     * Selected campaign ID.
     */
    public ?int $campaignId = null;

    /**
     * Campaign data.
     *
     * TODO DB: Replace with Campaign model instance
     */
    public ?array $campaign = null;

    /**
     * Active tab: 'donate' or 'donors'.
     */
    public string $activeTab = 'donate';

    /**
     * Form data for donation.
     */
    public string $donationAmount = '';

    public string $donorName = '';

    public string $donorPhone = '';

    public string $donorMessage = '';

    public bool $isAnonymous = false;

    /**
     * Listen for open payment modal event.
     */
    public function openModal(int $campaignId): void
    {
        $this->campaignId = $campaignId;
        $this->campaign = CampaignDataService::getCampaignById($campaignId);
        $this->isOpen = true;
        $this->activeTab = 'donate';
        $this->resetForm();
    }

    /**
     * Close the modal.
     */
    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->campaignId = null;
        $this->campaign = null;
        $this->activeTab = 'donate';
        $this->resetForm();
    }

    /**
     * Reset form data.
     */
    public function resetForm(): void
    {
        $this->donationAmount = '';
        $this->donorName = '';
        $this->donorPhone = '';
        $this->donorMessage = '';
        $this->isAnonymous = false;
    }

    /**
     * Switch tab.
     */
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Get donors for current campaign.
     *
     * TODO DB: Replace with CampaignDataService::getDonorsByCampaignId($this->campaignId)
     * or directly query: Donation::where('campaign_id', $this->campaignId)->latest()->get()
     *
     * @return array<int, array<string, mixed>>
     */
    public function getDonors(): array
    {
        if (! $this->campaignId) {
            return [];
        }

        return CampaignDataService::getDonorsByCampaignId($this->campaignId);
    }

    /**
     * Format currency to Indonesian Rupiah.
     */
    public function formatCurrency(int|float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    /**
     * Get bank accounts.
     *
     * TODO DB: Replace with PaymentMethod::where('type', 'bank')->where('is_active', true)->get()
     *
     * @return array<int, array<string, string>>
     */
    public function getBankAccounts(): array
    {
        return PaymentDataService::getBankAccounts();
    }

    /**
     * Get e-wallets.
     *
     * TODO DB: Replace with PaymentMethod::where('type', 'ewallet')->where('is_active', true)->get()
     *
     * @return array<int, array<string, string>>
     */
    public function getEwallets(): array
    {
        return PaymentDataService::getEwallets();
    }

    /**
     * Listen for browser events.
     *
     * @var array<string>
     */
    protected $listeners = ['open-payment-modal' => 'openModal'];

    public function render()
    {
        return view('livewire.donation.payment-modal');
    }
}
