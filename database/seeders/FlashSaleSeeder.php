<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FlashSale::create([
            'name' => 'Flash Sale',
            'image' => 'uploads/website-images/flash_sale-2022-06-13-053409-9999.jpg',
            'homepage_image' => 'uploads/website-images/flash_sale-2022-06-13-053409-9999.jpg',
            'date' => '2022-06-13',
            'offer' => 10,
            'status' => 1,
        ]);
    }
}
