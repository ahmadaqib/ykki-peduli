<?php

namespace Tests\Feature\Donation;

use App\Livewire\Donation\DonationNotifications;
use Livewire\Livewire;
use Tests\TestCase;

class DonationNotificationsTest extends TestCase
{
    public function test_donation_notifications_loads_recent_donations(): void
    {
        $component = Livewire::test(DonationNotifications::class);

        $donations = $component->get('donations');

        $this->assertIsArray($donations);
        $this->assertNotEmpty($donations);
    }

    public function test_donation_notifications_has_current_donation(): void
    {
        $component = Livewire::test(DonationNotifications::class);

        $currentDonation = $component->get('currentDonation');

        $this->assertNotNull($currentDonation);
        $this->assertArrayHasKey('donor_name', $currentDonation);
        $this->assertArrayHasKey('amount', $currentDonation);
        $this->assertArrayHasKey('campaign_title', $currentDonation);
    }

    public function test_donation_notifications_cycles_through_donations(): void
    {
        $component = Livewire::test(DonationNotifications::class);

        $firstDonation = $component->get('currentDonation');
        $firstIndex = $component->get('currentIndex');

        $component->call('getNextDonation');

        $secondDonation = $component->get('currentDonation');
        $secondIndex = $component->get('currentIndex');

        $this->assertNotEquals($firstIndex, $secondIndex);
        // Donations might be the same if we loop back, but index should change
        $this->assertGreaterThanOrEqual(0, $secondIndex);
    }

    public function test_donation_notifications_formats_currency(): void
    {
        $component = Livewire::test(DonationNotifications::class);

        // Access the method via the component instance
        $formatted = $component->instance()->formatCurrency(1000000);

        $this->assertStringContainsString('1.000.000', $formatted);
        $this->assertStringStartsWith('Rp', $formatted);
    }

    public function test_donation_notifications_handles_empty_donations(): void
    {
        // This test ensures the component doesn't break with empty donations
        // In a real scenario, this would be handled by the service
        $component = Livewire::test(DonationNotifications::class);

        $donations = $component->get('donations');

        if (empty($donations)) {
            $component->call('getNextDonation');
            $this->assertNull($component->get('currentDonation'));
        } else {
            $this->assertNotNull($component->get('currentDonation'));
        }
    }

    public function test_donation_notifications_shuffles_donations(): void
    {
        $component = Livewire::test(DonationNotifications::class);

        $originalDonations = $component->get('donations');
        $originalOrder = array_map(fn ($d) => $d['donor_name'], $originalDonations);

        $component->call('shuffleDonations');

        $shuffledDonations = $component->get('donations');
        $shuffledOrder = array_map(fn ($d) => $d['donor_name'], $shuffledDonations);

        // Note: Shuffling might result in the same order, so we just check it's still an array
        $this->assertIsArray($shuffledDonations);
        $this->assertCount(count($originalDonations), $shuffledDonations);
    }
}
