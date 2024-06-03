<?php

namespace Database\Seeders;

use App\Models\CustomPagination;
use Illuminate\Database\Seeder;

class CustomPaginationSeeder extends Seeder
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
                'page_name' => 'Product Page',
                'qty' => 12,
            ],
            [
                'page_name' => 'Brand Page',
                'qty' => 10,
            ],
            [
                'page_name' => 'Product Review',
                'qty' => 8,
            ],
        ];

        foreach ($list as $value) {
            CustomPagination::create($value);
        }
    }
}
