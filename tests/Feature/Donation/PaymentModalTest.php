<?php

namespace Tests\Feature\Donation;

use App\Livewire\Donation\PaymentModal;
use App\Services\CampaignDataService;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentModalTest extends TestCase
{
    public function test_payment_modal_starts_closed(): void
    {
        Livewire::test(PaymentModal::class)
            ->assertSet('isOpen', false)
            ->assertSet('campaignId', null)
            ->assertSet('campaign', null);
    }

    public function test_payment_modal_opens_with_campaign_data(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        Livewire::test(PaymentModal::class)
            ->call('openModal', $campaign['id'])
            ->assertSet('isOpen', true)
            ->assertSet('campaignId', $campaign['id'])
            ->assertSet('campaign', $campaign);
    }

    public function test_payment_modal_closes_correctly(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        Livewire::test(PaymentModal::class)
            ->call('openModal', $campaign['id'])
            ->assertSet('isOpen', true)
            ->call('closeModal')
            ->assertSet('isOpen', false)
            ->assertSet('campaignId', null)
            ->assertSet('campaign', null);
    }

    public function test_payment_modal_returns_bank_accounts(): void
    {
        $component = Livewire::test(PaymentModal::class);

        // Access the method via the component instance
        $bankAccounts = $component->instance()->getBankAccounts();

        $this->assertIsArray($bankAccounts);
        $this->assertNotEmpty($bankAccounts);

        foreach ($bankAccounts as $bank) {
            $this->assertArrayHasKey('name', $bank);
            $this->assertArrayHasKey('account_number', $bank);
            $this->assertArrayHasKey('account_name', $bank);
        }
    }

    public function test_payment_modal_returns_ewallets(): void
    {
        $component = Livewire::test(PaymentModal::class);

        // Access the method via the component instance
        $ewallets = $component->instance()->getEwallets();

        $this->assertIsArray($ewallets);
        $this->assertNotEmpty($ewallets);

        foreach ($ewallets as $ewallet) {
            $this->assertArrayHasKey('name', $ewallet);
            $this->assertArrayHasKey('phone', $ewallet);
            $this->assertArrayHasKey('account_name', $ewallet);
        }
    }

    public function test_payment_modal_listens_to_open_event(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        Livewire::test(PaymentModal::class)
            ->dispatch('open-payment-modal', campaignId: $campaign['id'])
            ->assertSet('isOpen', true)
            ->assertSet('campaignId', $campaign['id']);
    }
}
