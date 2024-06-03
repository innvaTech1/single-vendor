<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            [
                'id' => 1,
                'page_name' => 'Home Page',
                'seo_title' => 'Home - Welcome to ShopO',
                'seo_description' => 'A best Ecommerce script',
            ],
            [
                'id' => 2,
                'page_name' => 'About Us',
                'seo_title' => 'About Us - Ecommerce',
                'seo_description' => 'About Us',
            ],
            [
                'id' => 3,
                'page_name' => 'Contact Us',
                'seo_title' => 'Contact Us - Ecommerce',
                'seo_description' => 'Contact Us',
            ],
            [
                'id' => 5,
                'page_name' => 'Seller Page',
                'seo_title' => 'Our Seller - Ecommerce',
                'seo_description' => 'Seller Page',
            ],
            [
                'id' => 8,
                'page_name' => 'Flash Deal',
                'seo_title' => 'Flash Deal - Ecommerce',
                'seo_description' => 'Flash Deal',
            ],
            [
                'id' => 9,
                'page_name' => 'Shop Page',
                'seo_title' => 'Shop Page - Ecommerce',
                'seo_description' => 'Shop Page',
            ]
        ];
        
        foreach ($list as $value) {
            SeoSetting::create($value);
        }
    }
}
