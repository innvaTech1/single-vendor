<?php

namespace Database\Seeders;

use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminTableSeeder::class,
            BannerImagesTableSeeder::class,
            SettingsSeeder::class,
            FlashSaleSeeder::class,
            EmailConfigSeeder::class,
            SeoSettingsSeeder::class,
            CustomPaginationSeeder::class,
        ]);
    }
}
