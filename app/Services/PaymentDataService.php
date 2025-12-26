<?php

namespace App\Services;

/**
 * STATIC DATA SERVICE - READY FOR DATABASE MIGRATION
 *
 * This service provides static payment method data (bank accounts and e-wallets).
 * When ready to migrate to database:
 *
 * Migration Steps:
 * 1. Create migration: php artisan make:migration create_payment_methods_table
 * 2. Define schema:
 *    - id (bigInteger, primary)
 *    - type (string) - 'bank' or 'ewallet'
 *    - name (string) - e.g., 'BCA', 'GoPay', 'OVO'
 *    - account_number (string) - bank account number or e-wallet phone number
 *    - account_name (string) - account holder name
 *    - is_active (boolean, default true)
 *    - display_order (integer, default 0) - for sorting
 *    - timestamps
 *
 * 3. Replace getBankAccounts() with:
 *    PaymentMethod::where('type', 'bank')
 *        ->where('is_active', true)
 *        ->orderBy('display_order')
 *        ->get()
 *
 * 4. Replace getEwallets() with:
 *    PaymentMethod::where('type', 'ewallet')
 *        ->where('is_active', true)
 *        ->orderBy('display_order')
 *        ->get()
 *
 * Related Models: PaymentMethod
 * Related Tables: payment_methods
 */
class PaymentDataService
{
    /**
     * Get all active bank accounts.
     *
     * TODO DB: Replace with:
     * PaymentMethod::where('type', 'bank')
     *     ->where('is_active', true)
     *     ->orderBy('display_order')
     *     ->get()
     *
     * @return array<int, array<string, string>>
     */
    public static function getBankAccounts(): array
    {
        return [
            [
                'name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => 'Yayasan Ykki Peduli',
            ],
            [
                'name' => 'Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'Yayasan Ykki Peduli',
            ],
            [
                'name' => 'BNI',
                'account_number' => '1122334455',
                'account_name' => 'Yayasan Ykki Peduli',
            ],
        ];
    }

    /**
     * Get all active e-wallets.
     *
     * TODO DB: Replace with:
     * PaymentMethod::where('type', 'ewallet')
     *     ->where('is_active', true)
     *     ->orderBy('display_order')
     *     ->get()
     *
     * @return array<int, array<string, string>>
     */
    public static function getEwallets(): array
    {
        return [
            [
                'name' => 'GoPay',
                'phone' => '081234567890',
                'account_name' => 'Ykki Peduli',
            ],
            [
                'name' => 'OVO',
                'phone' => '081234567890',
                'account_name' => 'Ykki Peduli',
            ],
            [
                'name' => 'DANA',
                'phone' => '081234567890',
                'account_name' => 'Ykki Peduli',
            ],
        ];
    }
}
