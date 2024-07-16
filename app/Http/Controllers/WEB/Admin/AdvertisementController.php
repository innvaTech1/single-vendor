<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\Product;
use App\Models\Category;
use Image;
use File;

class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

        $sliderSidebarBannerOne = BannerImage::where('type', 'sliderSidebarBannerOne')->select('product_slug', 'image', 'id', 'banner_location', 'title_one', 'title_two', 'badge', 'status')->first();

        $sliderSidebarBannerTwo = BannerImage::where('type', 'sliderSidebarBannerTwo')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two', 'badge')->first();

        $popularCategorySidebarBanner = BannerImage::where('type', 'popularCategorySidebarBanner')->select('product_slug', 'image', 'id', 'banner_location', 'status')->first();

        $homepageTwoColumnBannerOne = BannerImage::where('type', 'homepageTwoColumnBannerOne')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two', 'badge')->first();

        $homepageTwoColumnBannerTwo = BannerImage::where('type', 'homepageTwoColumnBannerTwo')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two', 'badge')->first();

        $homepageSingleBannerOne = BannerImage::where('type', 'homepageSingleBannerOne')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two')->first();

        $homepageSingleBannerTwo = BannerImage::where('type', 'homepageSingleBannerTwo')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one')->first();

        $megaMenuBanner = BannerImage::where('type', 'megaMenuBanner')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two')->first();

        $homepageFlashSaleSidebarBanner = BannerImage::where('type', 'homepageFlashSaleSidebarBanner')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title')->first();

        $shopPageCenterBanner = BannerImage::where('type', 'shopPageCenterBanner')->select('product_slug', 'image', 'id', 'banner_location', 'after_product_qty', 'status', 'title_one')->first();

        $shopPageSidebarBanner = BannerImage::where('type', 'shopPageSidebarBanner')->select('product_slug', 'image', 'id', 'banner_location', 'status', 'title_one', 'title_two')->first();

        $products = Category::where(['status' => 1])->select('id', 'name', 'slug')->get();

        return view('admin.advertisement')->with([
            'products' => $products,
            'sliderSidebarBannerOne' => $sliderSidebarBannerOne,
            'sliderSidebarBannerTwo' => $sliderSidebarBannerTwo,
            'popularCategorySidebarBanner' => $popularCategorySidebarBanner,
            'homepageTwoColumnBannerOne' => $homepageTwoColumnBannerOne,
            'homepageTwoColumnBannerTwo' => $homepageTwoColumnBannerTwo,
            'homepageSingleBannerOne' => $homepageSingleBannerOne,
            'homepageSingleBannerTwo' => $homepageSingleBannerTwo,
            'megaMenuBanner' => $megaMenuBanner,
            'homepageFlashSaleSidebarBanner' => $homepageFlashSaleSidebarBanner,
            'shopPageCenterBanner' => $shopPageCenterBanner,
            'shopPageSidebarBanner' => $shopPageSidebarBanner,
        ]);
    }

    public function megaMenuBannerUpdate(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $megaMenuBanner = BannerImage::where('type', 'megaMenuBanner')->select('type', 'link', 'image', 'id', 'banner_location')->first();

        if (!$megaMenuBanner) {
            $megaMenuBanner = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $megaMenuBanner->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $megaMenuBanner->image = $banner_name;
            $megaMenuBanner->save();
        }
        $megaMenuBanner->product_slug = $request->product_slug;
        $megaMenuBanner->status = $request->status;
        $megaMenuBanner->type = 'megaMenuBanner';
        $megaMenuBanner->title_one = $request->title_one;
        $megaMenuBanner->title_two = $request->title_two;
        $megaMenuBanner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function updateSliderBannerOne(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
            'badge' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $sliderSidebarBannerOne = BannerImage::where('type', 'sliderSidebarBannerOne')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$sliderSidebarBannerOne) {
            $sliderSidebarBannerOne = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $sliderSidebarBannerOne->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $sliderSidebarBannerOne->image = $banner_name;
            $sliderSidebarBannerOne->save();
        }

        $sliderSidebarBannerOne->product_slug = $request->product_slug;
        $sliderSidebarBannerOne->status = $request->status;
        $sliderSidebarBannerOne->title_one = $request->title_one;
        $sliderSidebarBannerOne->title_two = $request->title_two;
        $sliderSidebarBannerOne->badge = $request->badge;
        $sliderSidebarBannerOne->type = 'sliderSidebarBannerOne';
        $sliderSidebarBannerOne->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function updateSliderBannerTwo(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
            'badge' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $sliderSidebarBannerTwo = BannerImage::where('type', 'sliderSidebarBannerTwo')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$sliderSidebarBannerTwo) {
            $sliderSidebarBannerTwo = new BannerImage();
        }
        if ($request->banner_image) {
            $existing_banner = $sliderSidebarBannerTwo->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $sliderSidebarBannerTwo->image = $banner_name;
            $sliderSidebarBannerTwo->save();
        }
        $sliderSidebarBannerTwo->product_slug = $request->product_slug;
        $sliderSidebarBannerTwo->status = $request->status;
        $sliderSidebarBannerTwo->title_one = $request->title_one;
        $sliderSidebarBannerTwo->title_two = $request->title_two;
        $sliderSidebarBannerTwo->badge = $request->badge;
        $sliderSidebarBannerTwo->type = 'sliderSidebarBannerTwo';
        $sliderSidebarBannerTwo->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function updatePopularCategorySidebar(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $popularCategorySidebarBanner = BannerImage::where('type', 'popularCategorySidebarBanner')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$popularCategorySidebarBanner) {
            $popularCategorySidebarBanner = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $popularCategorySidebarBanner->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $popularCategorySidebarBanner->image = $banner_name;
            $popularCategorySidebarBanner->save();
        }
        $popularCategorySidebarBanner->product_slug = $request->product_slug;
        $popularCategorySidebarBanner->status = 1;
        $popularCategorySidebarBanner->type = 'popularCategorySidebarBanner';
        $popularCategorySidebarBanner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function homepageTwoColFirstBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
            'badge' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $homepageTwoColumnBannerOne = BannerImage::where('type', 'homepageTwoColumnBannerOne')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$homepageTwoColumnBannerOne) {
            $homepageTwoColumnBannerOne = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $homepageTwoColumnBannerOne->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $homepageTwoColumnBannerOne->image = $banner_name;
            $homepageTwoColumnBannerOne->save();
        }
        $homepageTwoColumnBannerOne->product_slug = $request->product_slug;
        $homepageTwoColumnBannerOne->status = $request->status;
        $homepageTwoColumnBannerOne->title_one = $request->title_one;
        $homepageTwoColumnBannerOne->title_two = $request->title_two;
        $homepageTwoColumnBannerOne->badge = $request->badge;
        $homepageTwoColumnBannerOne->type = 'homepageTwoColumnBannerOne';
        $homepageTwoColumnBannerOne->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function homepageTwoColSecondBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
            'badge' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $homepageTwoColumnBannerTwo = BannerImage::where('type', 'homepageTwoColumnBannerTwo')->select('link', 'image', 'id', 'banner_location', 'type')->first();


        if (!$homepageTwoColumnBannerTwo) {
            $homepageTwoColumnBannerTwo = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $homepageTwoColumnBannerTwo->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $homepageTwoColumnBannerTwo->image = $banner_name;
            $homepageTwoColumnBannerTwo->save();
        }
        $homepageTwoColumnBannerTwo->product_slug = $request->product_slug;
        $homepageTwoColumnBannerTwo->status = $request->status;
        $homepageTwoColumnBannerTwo->title_one = $request->title_one;
        $homepageTwoColumnBannerTwo->title_two = $request->title_two;
        $homepageTwoColumnBannerTwo->badge = $request->badge;
        $homepageTwoColumnBannerTwo->type = 'homepageTwoColumnBannerTwo';
        $homepageTwoColumnBannerTwo->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function homepageSinleFirstBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $homepageSingleBannerOne = BannerImage::where('type', 'homepageSingleBannerOne')->select('link', 'image', 'id', 'banner_location', 'type')->first();


        if (!$homepageSingleBannerOne) {
            $homepageSingleBannerOne = new BannerImage();
        }
        if ($request->banner_image) {
            $existing_banner = $homepageSingleBannerOne->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $homepageSingleBannerOne->image = $banner_name;
            $homepageSingleBannerOne->save();
        }
        $homepageSingleBannerOne->product_slug = $request->product_slug;
        $homepageSingleBannerOne->status = $request->status;
        $homepageSingleBannerOne->title_one = $request->title_one;
        $homepageSingleBannerOne->title_two = $request->title_two;
        $homepageSingleBannerOne->type = 'homepageSingleBannerOne';
        $homepageSingleBannerOne->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function homepageSinleSecondBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title' => 'required',

        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $homepageSingleBannerTwo = BannerImage::where('type', 'homepageSingleBannerTwo')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$homepageSingleBannerTwo) {
            $homepageSingleBannerTwo = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $homepageSingleBannerTwo->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $homepageSingleBannerTwo->image = $banner_name;
            $homepageSingleBannerTwo->save();
        }
        $homepageSingleBannerTwo->product_slug = $request->product_slug;
        $homepageSingleBannerTwo->status = $request->status;
        $homepageSingleBannerTwo->title_one = $request->title;
        $homepageSingleBannerTwo->type = 'homepageSingleBannerTwo';
        $homepageSingleBannerTwo->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function homepageFlashSaleSidebarBanner(Request $request)
    {
        $rules = [
            'link' => 'required',
            'link_two' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'link.required' => trans('admin_validation.Play store link is required'),
            'link_two.required' => trans('admin_validation.App store link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $homepageFlashSaleSidebarBanner = BannerImage::where('type', 'homepageFlashSaleSidebarBanner')->select('link', 'image', 'id', 'banner_location', 'type')->first();


        if (!$homepageFlashSaleSidebarBanner) {
            $homepageFlashSaleSidebarBanner = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $homepageFlashSaleSidebarBanner->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $homepageFlashSaleSidebarBanner->image = $banner_name;
            $homepageFlashSaleSidebarBanner->save();
        }
        $homepageFlashSaleSidebarBanner->link = $request->link;
        $homepageFlashSaleSidebarBanner->title = $request->link_two;
        $homepageFlashSaleSidebarBanner->status = $request->status;
        $homepageFlashSaleSidebarBanner->type = 'homepageFlashSaleSidebarBanner';
        $homepageFlashSaleSidebarBanner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function shopPageCenterBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'after_product_qty' => 'required',
            'status' => 'required',
            'title' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'after_product_qty.required' => trans('admin_validation.After product quantity is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $shopPageCenterBanner = BannerImage::where('type', 'shopPageCenterBanner')->first();



        if (!$shopPageCenterBanner) {
            $shopPageCenterBanner = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $shopPageCenterBanner->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $shopPageCenterBanner->image = $banner_name;
            $shopPageCenterBanner->save();
        }
        $shopPageCenterBanner->after_product_qty = $request->after_product_qty;
        $shopPageCenterBanner->product_slug = $request->product_slug;
        $shopPageCenterBanner->status = $request->status;
        $shopPageCenterBanner->title_one = $request->title;
        $shopPageCenterBanner->type = 'shopPageCenterBanner';
        $shopPageCenterBanner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function shopPageSidebarBanner(Request $request)
    {
        $rules = [
            'product_slug' => 'required',
            'status' => 'required',
            'title_one' => 'required',
            'title_two' => 'required',
        ];
        $customMessages = [
            'product_slug.required' => trans('admin_validation.Link is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $shopPageSidebarBanner = BannerImage::where('type', 'shopPageSidebarBanner')->select('link', 'image', 'id', 'banner_location', 'type')->first();

        if (!$shopPageSidebarBanner) {
            $shopPageSidebarBanner = new BannerImage();
        }

        if ($request->banner_image) {
            $existing_banner = $shopPageSidebarBanner->image;
            $banner_name = file_upload($request->banner_image, $existing_banner, '/uploads/custom-images/');
            $shopPageSidebarBanner->image = $banner_name;
            $shopPageSidebarBanner->save();
        }

        $shopPageSidebarBanner->product_slug = $request->product_slug;
        $shopPageSidebarBanner->status = $request->status;
        $shopPageSidebarBanner->title_one = $request->title_one;
        $shopPageSidebarBanner->title_two = $request->title_two;
        $shopPageSidebarBanner->type = 'shopPageSidebarBanner';
        $shopPageSidebarBanner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
