<?php

namespace App\Services;

/**
 * STATIC DATA SERVICE - READY FOR DATABASE MIGRATION
 *
 * This service provides static campaign and donation data.
 * When ready to migrate to database:
 *
 * Migration Steps:
 * 1. Create migration: php artisan make:migration create_campaigns_table
 * 2. Define schema:
 *    - id (bigInteger, primary)
 *    - title (string)
 *    - slug (string, unique)
 *    - description (text)
 *    - image (string, nullable)
 *    - target (decimal, 15, 2)
 *    - collected (decimal, 15, 2, default 0)
 *    - donors_count (integer, default 0)
 *    - deadline (date, nullable)
 *    - is_active (boolean, default true)
 *    - timestamps
 *
 * 3. Create migration: php artisan make:migration create_donations_table
 * 4. Define schema:
 *    - id (bigInteger, primary)
 *    - campaign_id (foreignId, references campaigns)
 *    - donor_name (string)
 *    - donor_email (string, nullable)
 *    - amount (decimal, 15, 2)
 *    - payment_method_id (foreignId, nullable, references payment_methods)
 *    - status (string, default 'pending') - pending, verified, failed
 *    - timestamps
 *
 * 5. Replace getCampaigns() with: Campaign::where('is_active', true)->get()
 * 6. Replace getCampaignById() with: Campaign::findOrFail($id)
 * 7. Replace getRecentDonations() with:
 *    Donation::with('campaign')
 *        ->where('status', 'verified')
 *        ->where('created_at', '>=', now()->subDay())
 *        ->latest()
 *        ->take(20)
 *        ->get()
 *
 * Related Models: Campaign, Donation
 * Related Tables: campaigns, donations
 */
class CampaignDataService
{
    /**
     * Get all active campaigns.
     *
     * TODO DB: Replace with Campaign::where('is_active', true)->get()
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getCampaigns(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'bantu-pendidikan-anak',
                'title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'description' => 'Kampanye ini bertujuan untuk membantu anak-anak dari keluarga kurang mampu agar dapat melanjutkan pendidikan mereka. Donasi akan digunakan untuk membiayai biaya sekolah, buku, seragam, dan kebutuhan pendidikan lainnya.',
                'situation' => 'Banyak anak-anak dari keluarga kurang mampu yang terpaksa putus sekolah karena tidak mampu membayar biaya pendidikan. Mereka memiliki semangat belajar yang tinggi namun terhalang oleh keterbatasan ekonomi keluarga.',
                'condition' => 'Dengan donasi Anda, kami dapat membantu membiayai pendidikan mereka mulai dari biaya SPP, buku pelajaran, seragam sekolah, hingga kebutuhan alat tulis. Setiap donasi akan memberikan kesempatan bagi mereka untuk meraih cita-cita melalui pendidikan yang layak.',
                'image' => '/images/campaigns/education.jpg',
                'target' => 50000000, // Rp 50 juta
                'collected' => 32500000, // Rp 32.5 juta (65%)
                'donors_count' => 127,
                'deadline' => '2025-03-31',
            ],
            [
                'id' => 2,
                'slug' => 'bantuan-kesehatan-lansia',
                'title' => 'Bantuan Kesehatan untuk Lansia',
                'description' => 'Program kesehatan khusus untuk lansia yang membutuhkan perawatan medis. Donasi akan digunakan untuk biaya pengobatan, pemeriksaan kesehatan rutin, dan pembelian obat-obatan.',
                'situation' => 'Banyak lansia yang membutuhkan perawatan kesehatan namun tidak memiliki kemampuan finansial untuk membiayai pengobatan. Kondisi kesehatan mereka semakin memburuk karena tidak mendapat penanganan medis yang memadai.',
                'condition' => 'Donasi Anda akan membantu lansia mendapatkan akses layanan kesehatan yang layak, mulai dari pemeriksaan rutin, pengobatan, hingga pembelian obat-obatan yang diperlukan. Mari kita jaga kesehatan mereka di masa senja.',
                'image' => '/images/campaigns/healthcare.jpg',
                'target' => 75000000, // Rp 75 juta
                'collected' => 48250000, // Rp 48.25 juta (64.3%)
                'donors_count' => 203,
                'deadline' => '2025-04-15',
            ],
            [
                'id' => 3,
                'slug' => 'bantuan-korban-bencana',
                'title' => 'Bantuan Korban Bencana Alam',
                'description' => 'Bantuan darurat untuk korban bencana alam berupa makanan, pakaian, tempat tinggal sementara, dan kebutuhan pokok lainnya. Mari kita bantu saudara-saudara kita yang sedang membutuhkan.',
                'situation' => 'Bencana alam telah merenggut rumah, harta benda, dan mata pencaharian mereka. Banyak korban yang kehilangan tempat tinggal dan kesulitan memenuhi kebutuhan dasar sehari-hari.',
                'condition' => 'Donasi Anda akan digunakan untuk menyediakan bantuan darurat seperti makanan, pakaian, tempat tinggal sementara, air bersih, dan kebutuhan pokok lainnya. Mari kita bantu mereka bangkit dari keterpurukan.',
                'image' => '/images/campaigns/disaster.jpg',
                'target' => 100000000, // Rp 100 juta
                'collected' => 87500000, // Rp 87.5 juta (87.5%)
                'donors_count' => 456,
                'deadline' => '2025-05-01',
            ],
            [
                'id' => 4,
                'slug' => 'program-pemberdayaan-perempuan',
                'title' => 'Program Pemberdayaan Perempuan',
                'description' => 'Program pelatihan dan pemberdayaan ekonomi untuk perempuan agar dapat mandiri secara finansial. Meliputi pelatihan keterampilan, modal usaha, dan pendampingan bisnis.',
                'situation' => 'Banyak perempuan yang memiliki potensi besar namun terbatas aksesnya untuk mengembangkan keterampilan dan mendapatkan modal usaha. Mereka ingin mandiri secara finansial namun tidak memiliki kesempatan.',
                'condition' => 'Dengan donasi Anda, kami dapat memberikan pelatihan keterampilan, modal usaha, dan pendampingan bisnis kepada perempuan. Program ini akan membantu mereka menjadi mandiri secara finansial dan meningkatkan kesejahteraan keluarga.',
                'image' => '/images/campaigns/women.jpg',
                'target' => 60000000, // Rp 60 juta
                'collected' => 28500000, // Rp 28.5 juta (47.5%)
                'donors_count' => 89,
                'deadline' => '2025-04-30',
            ],
        ];
    }

    /**
     * Get campaign by ID.
     *
     * TODO DB: Replace with Campaign::findOrFail($id)
     *
     * @return array<string, mixed>|null
     */
    public static function getCampaignById(int $id): ?array
    {
        $campaigns = self::getCampaigns();

        foreach ($campaigns as $campaign) {
            if ($campaign['id'] === $id) {
                return $campaign;
            }
        }

        return null;
    }

    /**
     * Get recent donations from the last 24 hours.
     *
     * TODO DB: Replace with:
     * Donation::with('campaign')
     *     ->where('status', 'verified')
     *     ->where('created_at', '>=', now()->subDay())
     *     ->latest()
     *     ->take(20)
     *     ->get()
     *     ->map(fn($donation) => [
     *         'donor_name' => $donation->donor_name,
     *         'amount' => $donation->amount,
     *         'campaign_title' => $donation->campaign->title,
     *         'donated_at' => $donation->created_at->diffForHumans(),
     *     ])
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getRecentDonations(): array
    {
        return [
            [
                'donor_name' => 'Budi Santoso',
                'amount' => 500000,
                'campaign_title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'donated_at' => '2 menit yang lalu',
            ],
            [
                'donor_name' => 'Siti Nurhaliza',
                'amount' => 1000000,
                'campaign_title' => 'Bantuan Korban Bencana Alam',
                'donated_at' => '5 menit yang lalu',
            ],
            [
                'donor_name' => 'Ahmad Fauzi',
                'amount' => 250000,
                'campaign_title' => 'Bantuan Kesehatan untuk Lansia',
                'donated_at' => '8 menit yang lalu',
            ],
            [
                'donor_name' => 'Dewi Sartika',
                'amount' => 750000,
                'campaign_title' => 'Program Pemberdayaan Perempuan',
                'donated_at' => '12 menit yang lalu',
            ],
            [
                'donor_name' => 'Rudi Hartono',
                'amount' => 2000000,
                'campaign_title' => 'Bantuan Korban Bencana Alam',
                'donated_at' => '15 menit yang lalu',
            ],
            [
                'donor_name' => 'Maya Indira',
                'amount' => 300000,
                'campaign_title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'donated_at' => '18 menit yang lalu',
            ],
            [
                'donor_name' => 'Fajar Pratama',
                'amount' => 1500000,
                'campaign_title' => 'Bantuan Kesehatan untuk Lansia',
                'donated_at' => '22 menit yang lalu',
            ],
            [
                'donor_name' => 'Lina Marlina',
                'amount' => 400000,
                'campaign_title' => 'Program Pemberdayaan Perempuan',
                'donated_at' => '25 menit yang lalu',
            ],
            [
                'donor_name' => 'Hendra Wijaya',
                'amount' => 800000,
                'campaign_title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'donated_at' => '30 menit yang lalu',
            ],
            [
                'donor_name' => 'Rina Wati',
                'amount' => 600000,
                'campaign_title' => 'Bantuan Korban Bencana Alam',
                'donated_at' => '35 menit yang lalu',
            ],
            [
                'donor_name' => 'Agus Setiawan',
                'amount' => 1200000,
                'campaign_title' => 'Bantuan Kesehatan untuk Lansia',
                'donated_at' => '40 menit yang lalu',
            ],
            [
                'donor_name' => 'Sari Dewi',
                'amount' => 350000,
                'campaign_title' => 'Program Pemberdayaan Perempuan',
                'donated_at' => '45 menit yang lalu',
            ],
            [
                'donor_name' => 'Bambang Suryadi',
                'amount' => 900000,
                'campaign_title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'donated_at' => '50 menit yang lalu',
            ],
            [
                'donor_name' => 'Indah Permata',
                'amount' => 550000,
                'campaign_title' => 'Bantuan Korban Bencana Alam',
                'donated_at' => '1 jam yang lalu',
            ],
            [
                'donor_name' => 'Dedi Kurniawan',
                'amount' => 1100000,
                'campaign_title' => 'Bantuan Kesehatan untuk Lansia',
                'donated_at' => '1 jam yang lalu',
            ],
            [
                'donor_name' => 'Nurul Hidayati',
                'amount' => 450000,
                'campaign_title' => 'Program Pemberdayaan Perempuan',
                'donated_at' => '1 jam yang lalu',
            ],
            [
                'donor_name' => 'Rizki Maulana',
                'amount' => 700000,
                'campaign_title' => 'Bantu Pendidikan Anak Kurang Mampu',
                'donated_at' => '2 jam yang lalu',
            ],
            [
                'donor_name' => 'Fitri Handayani',
                'amount' => 1300000,
                'campaign_title' => 'Bantuan Korban Bencana Alam',
                'donated_at' => '2 jam yang lalu',
            ],
            [
                'donor_name' => 'Yoga Pratama',
                'amount' => 380000,
                'campaign_title' => 'Bantuan Kesehatan untuk Lansia',
                'donated_at' => '3 jam yang lalu',
            ],
            [
                'donor_name' => 'Wulan Sari',
                'amount' => 950000,
                'campaign_title' => 'Program Pemberdayaan Perempuan',
                'donated_at' => '4 jam yang lalu',
            ],
        ];
    }

    /**
     * Get donors by campaign ID.
     *
     * TODO DB: Replace with:
     * Donation::where('campaign_id', $campaignId)
     *     ->where('status', 'verified')
     *     ->latest()
     *     ->get()
     *     ->map(fn($donation) => [
     *         'id' => $donation->id,
     *         'donor_name' => $donation->is_anonymous ? 'Anonim' : $donation->donor_name,
     *         'amount' => $donation->amount,
     *         'is_anonymous' => $donation->is_anonymous,
     *         'message' => $donation->message,
     *         'donated_at' => $donation->created_at,
     *     ])
     *
     * Related Models: Donation
     * Related Tables: donations
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getDonorsByCampaignId(int $campaignId): array
    {
        // Dummy data untuk setiap campaign
        $donors = [
            1 => [ // Bantu Pendidikan Anak
                [
                    'id' => 1,
                    'donor_name' => 'Budi Santoso',
                    'amount' => 500000,
                    'is_anonymous' => false,
                    'message' => 'Semoga bermanfaat untuk pendidikan anak-anak',
                    'donated_at' => '2025-12-26 10:30:00',
                ],
                [
                    'id' => 2,
                    'donor_name' => 'Anonim',
                    'amount' => 1000000,
                    'is_anonymous' => true,
                    'message' => null,
                    'donated_at' => '2025-12-25 15:20:00',
                ],
                [
                    'id' => 3,
                    'donor_name' => 'Siti Nurhaliza',
                    'amount' => 750000,
                    'is_anonymous' => false,
                    'message' => 'Mari kita dukung pendidikan mereka',
                    'donated_at' => '2025-12-25 14:10:00',
                ],
                [
                    'id' => 4,
                    'donor_name' => 'Anonim',
                    'amount' => 250000,
                    'is_anonymous' => true,
                    'message' => 'Semoga membantu',
                    'donated_at' => '2025-12-24 09:45:00',
                ],
                [
                    'id' => 5,
                    'donor_name' => 'Ahmad Fauzi',
                    'amount' => 2000000,
                    'is_anonymous' => false,
                    'message' => 'Pendidikan adalah investasi terbaik',
                    'donated_at' => '2025-12-23 16:30:00',
                ],
            ],
            2 => [ // Bantuan Kesehatan Lansia
                [
                    'id' => 6,
                    'donor_name' => 'Dewi Sartika',
                    'amount' => 1000000,
                    'is_anonymous' => false,
                    'message' => 'Semoga lansia sehat selalu',
                    'donated_at' => '2025-12-26 11:20:00',
                ],
                [
                    'id' => 7,
                    'donor_name' => 'Anonim',
                    'amount' => 500000,
                    'is_anonymous' => true,
                    'message' => null,
                    'donated_at' => '2025-12-25 13:15:00',
                ],
                [
                    'id' => 8,
                    'donor_name' => 'Rudi Hartono',
                    'amount' => 1500000,
                    'is_anonymous' => false,
                    'message' => 'Kesehatan adalah harta yang tak ternilai',
                    'donated_at' => '2025-12-24 10:00:00',
                ],
                [
                    'id' => 9,
                    'donor_name' => 'Maya Indira',
                    'amount' => 800000,
                    'is_anonymous' => false,
                    'message' => 'Mari peduli pada lansia',
                    'donated_at' => '2025-12-23 14:45:00',
                ],
            ],
            3 => [ // Bantuan Korban Bencana
                [
                    'id' => 10,
                    'donor_name' => 'Fajar Pratama',
                    'amount' => 2000000,
                    'is_anonymous' => false,
                    'message' => 'Semoga cepat pulih dan bangkit kembali',
                    'donated_at' => '2025-12-26 09:00:00',
                ],
                [
                    'id' => 11,
                    'donor_name' => 'Anonim',
                    'amount' => 5000000,
                    'is_anonymous' => true,
                    'message' => null,
                    'donated_at' => '2025-12-25 12:30:00',
                ],
                [
                    'id' => 12,
                    'donor_name' => 'Lina Marlina',
                    'amount' => 1000000,
                    'is_anonymous' => false,
                    'message' => 'Kita harus saling membantu',
                    'donated_at' => '2025-12-24 15:20:00',
                ],
                [
                    'id' => 13,
                    'donor_name' => 'Hendra Wijaya',
                    'amount' => 3000000,
                    'is_anonymous' => false,
                    'message' => 'Semoga bantuan ini meringankan beban',
                    'donated_at' => '2025-12-23 11:10:00',
                ],
                [
                    'id' => 14,
                    'donor_name' => 'Anonim',
                    'amount' => 1500000,
                    'is_anonymous' => true,
                    'message' => 'Semoga membantu',
                    'donated_at' => '2025-12-22 16:00:00',
                ],
            ],
            4 => [ // Program Pemberdayaan Perempuan
                [
                    'id' => 15,
                    'donor_name' => 'Rina Wati',
                    'amount' => 1200000,
                    'is_anonymous' => false,
                    'message' => 'Perempuan kuat, keluarga kuat',
                    'donated_at' => '2025-12-26 08:30:00',
                ],
                [
                    'id' => 16,
                    'donor_name' => 'Agus Setiawan',
                    'amount' => 800000,
                    'is_anonymous' => false,
                    'message' => 'Dukung pemberdayaan perempuan',
                    'donated_at' => '2025-12-25 10:15:00',
                ],
                [
                    'id' => 17,
                    'donor_name' => 'Anonim',
                    'amount' => 2000000,
                    'is_anonymous' => true,
                    'message' => null,
                    'donated_at' => '2025-12-24 13:40:00',
                ],
                [
                    'id' => 18,
                    'donor_name' => 'Sari Dewi',
                    'amount' => 600000,
                    'is_anonymous' => false,
                    'message' => 'Semoga program ini sukses',
                    'donated_at' => '2025-12-23 09:20:00',
                ],
            ],
        ];

        return $donors[$campaignId] ?? [];
    }
}
