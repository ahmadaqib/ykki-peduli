<?php

namespace Tests\Feature\Donation;

use App\Livewire\Donation\CampaignCard;
use App\Services\CampaignDataService;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignCardTest extends TestCase
{
    public function test_campaign_card_renders_with_campaign_data(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        $component = Livewire::test(CampaignCard::class, ['campaign' => $campaign]);

        $component->assertSee($campaign['title'])
            ->assertSee($campaign['description']);

        // Check for button text (with HTML entity)
        $this->assertStringContainsString('Lihat Detail', $component->html());
    }

    public function test_campaign_card_calculates_progress_percentage(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        $component = Livewire::test(CampaignCard::class, ['campaign' => $campaign]);

        $expectedProgress = ($campaign['collected'] / $campaign['target']) * 100;
        $actualProgress = $component->get('progressPercentage');

        $this->assertEqualsWithDelta($expectedProgress, $actualProgress, 0.01);
    }

    public function test_campaign_card_formats_currency_correctly(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        $component = Livewire::test(CampaignCard::class, ['campaign' => $campaign]);

        // Access the method via the component instance
        $formatted = $component->instance()->formatCurrency(500000);
        $this->assertStringContainsString('500.000', $formatted);
        $this->assertStringStartsWith('Rp', $formatted);
    }

    public function test_campaign_card_has_link_to_detail_page(): void
    {
        $campaigns = CampaignDataService::getCampaigns();
        $campaign = $campaigns[0];

        $component = Livewire::test(CampaignCard::class, ['campaign' => $campaign]);

        $this->assertStringContainsString('Lihat Detail', $component->html());
        $this->assertStringContainsString('campaign/'.$campaign['slug'], $component->html());
    }

    public function test_campaign_card_handles_zero_target(): void
    {
        $campaign = [
            'id' => 999,
            'slug' => 'test-campaign',
            'title' => 'Test Campaign',
            'description' => 'Test',
            'image' => '/test.jpg',
            'target' => 0,
            'collected' => 0,
            'donors_count' => 0,
        ];

        $component = Livewire::test(CampaignCard::class, ['campaign' => $campaign]);

        $this->assertEquals(0, $component->get('progressPercentage'));
    }
}
