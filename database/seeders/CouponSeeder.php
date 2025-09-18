<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10.00,
                'description' => 'Welcome discount for new customers',
                'max_usage' => 100,
                'used_count' => 25,
                'min_order_amount' => 500.00,
                'expiry_date' => Carbon::now()->addMonths(3),
                'status' => 'active'
            ],
            [
                'code' => 'SAVE50',
                'type' => 'fixed',
                'value' => 50.00,
                'description' => 'Save ₱50 on orders above ₱1000',
                'max_usage' => 50,
                'used_count' => 12,
                'min_order_amount' => 1000.00,
                'expiry_date' => Carbon::now()->addMonths(2),
                'status' => 'active'
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'free-shipping',
                'value' => 0.00,
                'description' => 'Free shipping on orders above ₱2000',
                'max_usage' => 75,
                'used_count' => 30,
                'min_order_amount' => 2000.00,
                'expiry_date' => Carbon::now()->addMonths(1),
                'status' => 'active'
            ],
            [
                'code' => 'SUMMER20',
                'type' => 'percentage',
                'value' => 20.00,
                'description' => 'Summer sale discount',
                'max_usage' => 200,
                'used_count' => 150,
                'min_order_amount' => 300.00,
                'expiry_date' => Carbon::now()->subDays(5),
                'status' => 'expired'
            ],
            [
                'code' => 'LOYALTY15',
                'type' => 'percentage',
                'value' => 15.00,
                'description' => 'Loyalty discount for returning customers',
                'max_usage' => null,
                'used_count' => 45,
                'min_order_amount' => 750.00,
                'expiry_date' => null,
                'status' => 'active'
            ],
            [
                'code' => 'FLASH25',
                'type' => 'percentage',
                'value' => 25.00,
                'description' => 'Flash sale - limited time only',
                'max_usage' => 30,
                'used_count' => 30,
                'min_order_amount' => 400.00,
                'expiry_date' => Carbon::now()->addDays(7),
                'status' => 'inactive'
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
