<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(
            [
                'maintenance_mode'=> 1,
                'logo' => 'uploads/website-images/logo-2022-02-12-09-58-40-3614.png',
                'favicon' => 'uploads/website-images/favicon-2022-02-16-08-13-40-5292.png',
                'contact_email' => 'contact@gmail.com',
                'enable_user_register' =>1,
                'enable_multivendor' =>  1,
                'enable_subscription_notify' => 1,
                'enable_save_contact_message' => 0,
                'text_direction' => 'ltr',
                'timezone' => 'Asia/Dhaka',
                'sidebar_lg_header' => 'InnvaCart',
                'sidebar_sm_header' => 'IC',
                'topbar_phone' =>
                '125-874-9658',
                'topbar_email' =>'contact@gmail.com',
                'menu_phone' =>'562-745-8659',
                'currency_name' =>'BDT',
                'currency_icon' => '৳',
                'currency_rate' => 1,
                'show_product_qty' => 1,
                'theme_one' => '#ff5200',
                'theme_two' => '#18587a',
            ]
        );
    }
}
