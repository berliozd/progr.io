<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory(3)->sequence(
            [
                'name' => 'Basic',
                'stripe_product_id' => config('cashier.basic_price'),
                'price' => '9.99'
            ],
            [
                'name' => 'Premium',
                'stripe_product_id' => config('cashier.premium_price'),
                'price' => '19.99'
            ],
            [
                'name' => 'One time',
                'stripe_product_id' => config('cashier.one_time_price'),
                'price' => '199.99'
            ]
        )->create();
    }
}
