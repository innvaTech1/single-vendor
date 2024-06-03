<?php

namespace Database\Seeders;

use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;

class EmailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailConfiguration::create([
            'mail_type' => 1,
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => '2525',
            'email' => 'your-email',
            'smtp_username' => 'your-username',
            'smtp_password' => 'your-password',
            'mail_encryption' => 'tls',
        ]);
    }
}
