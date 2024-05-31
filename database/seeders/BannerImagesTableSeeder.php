<?php

namespace Database\Seeders;

use App\Models\BannerImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BannerImagesTableSeeder extends Seeder
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
                'title' => 'Up To - 35% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/Mega-menu-2022-02-13-07-53-14-1062.png',
                'button_text' => 'Shop Now',
                'banner_location' => 'Mega Menu Banner',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -20% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner--2022-02-10-10-24-47-2663.jpg',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page One Column Banner',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -35% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-03-42-16-1335.png',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page First Two Column Banner One',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -40% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-03-42-16-1434.png',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page First Two Column Banner Two',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -28% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-18-01-2862.jpg',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page Second Two Column Banner one',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -22% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-18-01-6995.jpg',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page Second Two Column Banner two',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -35% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-13-04-57-46-4114.jpg',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page Third Two Column Banner one',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Up To -15% Off',
                'description' => 'Hot Deals',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-13-04-58-43-7437.jpg',
                'button_text' => 'Shop Now',
                'banner_location' => 'Home Page Third Two Column Banner Two',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'This is Tittle',
                'description' => 'This is Description',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-24-44-6895.jpg',
                'button_text' => 'dd',
                'banner_location' => 'Shopping cart bottom',
                'status' => 1,
                'header' => '',
            ],
            [
                'title' => 'This is Title',
                'description' => 'This is Description',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-25-59-9719.jpg',
                'button_text' => null,
                'banner_location' => 'Shopping cart bottom',
                'status' => 0,
                'header' => null,
            ],
            [
                'title' => 'This is Tittle',
                'description' => 'This is Description',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-26-46-8505.jpg',
                'button_text' => 'dd',
                'banner_location' => 'Campaign page',
                'status' => 1,
                'header' => '',
            ],
            [
                'title' => 'This is Tittle',
                'description' => 'This is Description',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-01-30-06-21-06-4562.png',
                'button_text' => 'dd',
                'banner_location' => 'Campaign page',
                'status' => 0,
                'header' => '',
            ],
            [
                'title' => 'This is Tittle',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
                'link' => 'Shop Now',
                'image' => 'uploads/website-images/banner-2022-02-07-10-48-37-9226.jpg',
                'button_text' => 'dd',
                'banner_location' => 'Login page',
                'status' => 0,
                'header' => 'Our Achievement',
            ],
            [
                'title' => 'Black Friday Sale',
                'description' => 'Up To -70% Off',
                'link' => 'product',
                'image' => 'uploads/website-images/banner-2022-02-06-04-24-02-9777.jpg',
                'button_text' => null,
                'banner_location' => 'Product Detail',
                'status' => 1,
                'header' => null,
            ],
            [
                'title' => 'Default Profile Image',
                'description' => null,
                'link' => null,
                'image' => 'uploads/website-images/default-avatar-2022-02-07-10-10-46-1477.jpg',
                'button_text' => null,
                'banner_location' => 'Default Profile Image',
                'status' => 0,
                'header' => null,
            ],
        ];

        foreach ($list as $item) {
            BannerImage::create($item);
        }
    }
}
