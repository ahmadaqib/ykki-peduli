<?php

namespace App\Livewire\Donation;

use App\Services\CampaignDataService;
use Livewire\Component;

class DonationNotifications extends Component
{
    /**
     * Current donation to display.
     *
     * TODO DB: Replace with Donation model instance
     * When migrating to DB, fetch from:
     * Donation::with('campaign')
     *     ->where('status', 'verified')
     *     ->where('created_at', '>=', now()->subDay())
     *     ->latest()
     *     ->take(20)
     *     ->get()
     */
    public ?array $currentDonation = null;

    /**
     * All recent donations.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $donations = [];

    /**
     * Index of current donation in the loop.
     */
    public int $currentIndex = 0;

    /**
     * Whether notifications are dismissed by user.
     */
    public bool $isDismissed = false;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->donations = CampaignDataService::getRecentDonations();
        $this->shuffleDonations();

        // Check if user has dismissed notifications (will be checked via JavaScript)
        // We'll start with showing notifications, and let JavaScript handle the localStorage check
        if (! empty($this->donations)) {
            $this->currentDonation = $this->donations[0];
        }
    }

    /**
     * Shuffle donations array for variety.
     */
    public function shuffleDonations(): void
    {
        shuffle($this->donations);
    }

    /**
     * Get next donation to display.
     * This method is called via Livewire polling.
     */
    public function getNextDonation(): void
    {
        // Don't show next donation if user has dismissed notifications
        if ($this->isDismissed) {
            $this->currentDonation = null;

            return;
        }

        if (empty($this->donations)) {
            $this->currentDonation = null;

            return;
        }

        $this->currentIndex = ($this->currentIndex + 1) % count($this->donations);
        $this->currentDonation = $this->donations[$this->currentIndex];

        // Reshuffle occasionally for variety
        if ($this->currentIndex === 0) {
            $this->shuffleDonations();
        }
    }

    /**
     * Dismiss notifications permanently.
     */
    public function dismissNotifications(): void
    {
        $this->isDismissed = true;
        $this->currentDonation = null;
    }

    /**
     * Format currency to Indonesian Rupiah.
     */
    public function formatCurrency(int|float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.donation.donation-notifications');
    }
}
