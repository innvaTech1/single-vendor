<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'admin_type' => 1,
            'name' => "Super Admin",
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt(1234),
            'status' => 1,
        ]);
    }
}
