<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintainanceText;
use App\Models\AnnouncementModal;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use Image;
use File;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();

        return view('admin.maintainance_mode', compact('maintainance'));
    }

    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            'description' => 'required',

        ];
        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $maintainance = MaintainanceText::first();
        if ($request->image) {
            $old_image = $maintainance->image;
            $image = $request->image;
            $image_name = file_upload($image, $old_image, '/uploads/custom-images/');
            $maintainance->image = $image_name;
            $maintainance->save();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }
        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function announcementModal()
    {
        $announcement = AnnouncementModal::first();

        return view('admin.announcement', compact('announcement'));
    }

    public function announcementModalUpdate(Request $request)
    {
        $rules = [
            'description' => 'required',
            'title' => 'required',
            'expired_date' => 'required',
        ];
        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
            'title.required' => trans('admin_validation.Title is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'expired_date.required' => trans('admin_validation.Expired date is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $announcement = AnnouncementModal::first();

        if (!$announcement) {
            $announcement = new AnnouncementModal();
        }

        if ($request->image) {
            $old_image = $announcement->image;
            $image = $request->image;
            $image_name = file_upload($image, $old_image, '/uploads/custom-images/');
            $announcement->image = $image_name;
            $announcement->save();
        }
        $announcement->description = $request->description;
        $announcement->title = $request->title;
        $announcement->expired_date = $request->expired_date;
        $announcement->status = $request->status ? 1 : 0;
        $announcement->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function headerPhoneNumber()
    {
        $setting = Setting::select('topbar_phone', 'topbar_email')->first();

        return response()->json(['setting' => $setting], 200);
    }

    public function updateHeaderPhoneNumber(Request $request)
    {
        $rules = [
            'topbar_phone' => 'required',
            'topbar_email' => 'required',
        ];
        $customMessages = [
            'topbar_phone.required' => trans('admin_validation.Topbar phone is required'),
            'topbar_email.required' => trans('admin_validation.Topbar email is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $setting = Setting::first();
        $setting->topbar_phone = $request->topbar_phone;
        $setting->topbar_email = $request->topbar_email;
        $setting->save();

        $notification = trans('admin_validation.Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function loginPage()
    {
        $banner = BannerImage::select('image')->whereId('13')->first();
        return view('admin.login_page', compact('banner'));
    }

    public function updateloginPage(Request $request)
    {

        $banner = BannerImage::whereId('13')->first();
        if ($request->image) {
            $existing_banner = $banner->image;
            $banner_name = file_upload($request->image, $existing_banner, '/uploads/custom-images/');
            $banner->image = $banner_name;
            $banner->save();
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function shopPage()
    {
        $shop_page = ShopPage::first();

        return view('admin.shop_page', compact('shop_page'));
    }

    public function updateFilterPrice(Request $request)
    {
        $rules = [
            'filter_price_range' => 'required|numeric',
        ];
        $customMessages = [
            'filter_price_range.required' => trans('admin_validation.Filter price is required'),
            'filter_price_range.numeric' => trans('admin_validation.Filter price should be numeric number'),
        ];
        $this->validate($request, $rules, $customMessages);

        $shop_page = ShopPage::first();

        if (!$shop_page) {
            $shop_page = new ShopPage();
        }

        $shop_page->filter_price_range = $request->filter_price_range;
        $shop_page->save();
        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function seoSetup()
    {
        $pages = SeoSetting::all();
        return view('admin.seo_setup', compact('pages'));
    }

    public function getSeoSetup($id)
    {
        $page = SeoSetting::find($id);
        return response()->json(['page' => $page], 200);
    }

    public function updateSeoSetup(Request $request, $id)
    {
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required'
        ];
        $customMessages = [
            'seo_title.required' => trans('admin_validation.Seo title is required'),
            'seo_description.required' => trans('admin_validation.Seo description is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function productProgressbar()
    {
        $setting = Setting::select('show_product_progressbar')->first();
        return response()->json(['setting' => $setting], 200);
    }


    public function updateProductProgressbar()
    {
        $setting = Setting::first();
        if ($setting->show_product_progressbar == 1) {
            $setting->show_product_progressbar = 0;
            $setting->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $setting->show_product_progressbar = 1;
            $setting->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function defaultAvatar()
    {
        $defaultProfile = BannerImage::select('title', 'image')->whereId('15')->first();
        return view('admin.default_profile_image', compact('defaultProfile'));
    }

    public function updateDefaultAvatar(Request $request)
    {
        $defaultProfile = BannerImage::whereId('15')->first();
        if ($request->avatar) {
            $existing_avatar = $defaultProfile->image;
            $default_avatar = file_upload($request->avatar, $existing_avatar, '/uploads/custom-images/');
            $defaultProfile->image = $default_avatar;
            $defaultProfile->save();
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function sellerCondition()
    {
        $setting = Setting::select('seller_condition')->first();
        return view('admin.seller_condition', compact('setting'));
    }

    public function updatesellerCondition(Request $request)
    {
        $rules = [
            'terms_and_condition' => 'required'
        ];
        $customMessages = [
            'terms_and_condition.required' => trans('admin_validation.Terms and condition is required')
        ];
        $this->validate($request, $rules, $customMessages);

        $setting = Setting::first();
        $setting->seller_condition = $request->terms_and_condition;
        $setting->save();
        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function subscriptionBanner()
    {
        $subscription_banner = BannerImage::select('id', 'image', 'banner_location', 'header', 'title')->find(27);
        return view('admin.subscription_banner', compact('subscription_banner'));
    }

    public function updatesubscriptionBanner(Request $request)
    {
        $rules = [
            'title' => 'required',
            'header' => 'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'header.required' => trans('admin_validation.Header is required')
        ];
        $this->validate($request, $rules, $customMessages);

        $subscription_banner = BannerImage::find(27);

        if (!$subscription_banner) {
            $subscription_banner = new BannerImage();
        }

        if ($request->image) {
            $existing_banner = $subscription_banner->image;
            $banner_name = file_upload($request->image, $existing_banner, '/uploads/custom-images/');
            $subscription_banner->image = $banner_name;
            $subscription_banner->save();
        }
        $subscription_banner->title = $request->title;
        $subscription_banner->header = $request->header;
        $subscription_banner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function image_content()
    {

        $image_content = Setting::select('empty_cart', 'empty_wishlist', 'change_password_image', 'become_seller_avatar', 'become_seller_banner', 'login_image', 'error_page')->first();

        return view('admin.image_content', compact('image_content'));
    }

    public function updateImageContent(Request $request)
    {
        $image_content = Setting::first();

        if ($request->empty_cart) {
            $existing_banner = $image_content->empty_cart;
            $banner_name = file_upload($request->empty_cart, $existing_banner, '/uploads/custom-images/');
            $image_content->empty_cart = $banner_name;
            $image_content->save();
        }

        if ($request->empty_wishlist) {
            $existing_banner = $image_content->empty_wishlist;
            $banner_name = file_upload($request->empty_wishlist, $existing_banner, '/uploads/custom-images/');
            $image_content->empty_wishlist = $banner_name;
            $image_content->save();
        }

        if ($request->change_password_image) {
            $existing_banner = $image_content->change_password_image;
            $banner_name = file_upload($request->change_password_image, $existing_banner, '/uploads/custom-images/');
            $image_content->change_password_image = $banner_name;
            $image_content->save();
        }

        if ($request->become_seller_avatar) {
            $existing_banner = $image_content->become_seller_avatar;
            $banner_name = file_upload($request->become_seller_avatar, $existing_banner, '/uploads/custom-images/');
            $image_content->become_seller_avatar = $banner_name;
            $image_content->save();
        }

        if ($request->become_seller_banner) {
            $existing_banner = $image_content->become_seller_banner;
            $banner_name = file_upload($request->become_seller_banner, $existing_banner, '/uploads/custom-images/');
            $image_content->become_seller_banner = $banner_name;
            $image_content->save();
        }

        if ($request->login_image) {
            $existing_banner = $image_content->login_image;
            $banner_name = file_upload($request->login_image, $existing_banner, '/uploads/custom-images/');
            $image_content->login_image = $banner_name;
            $image_content->save();
        }

        if ($request->error_page) {
            $existing_banner = $image_content->error_page;
            $banner_name = file_upload($request->error_page, $existing_banner, '/uploads/custom-images/');
            $image_content->error_page = $banner_name;
            $image_content->save();
        }





        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
