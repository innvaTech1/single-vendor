<?php

namespace App\Http\Controllers\User;

use File;
use Image;

use App\Models\City;
use App\Models\Order;
use App\Rules\Captcha;
use App\Models\Country;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Models\BannerImage;
use Illuminate\Support\Str;
use App\Models\CountryState;
use App\Models\OrderProduct;
use Illuminate\Http\Request;

use App\Models\ProductReport;

use App\Models\ProductReview;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id', $user->id)->get();
        $reviews = $user->reviews;
        $wishlists = $user->wishlists;
        return view('user.dashboard', compact('orders', 'reviews', 'wishlists'));
    }


    public function order()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id', 'desc')->where('user_id', $user->id)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders', 'setting'));
    }

    public function pendingOrder()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id', 'desc')->where('user_id', $user->id)->where('order_status', 0)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders', 'setting'));
    }

    public function completeOrder()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id', 'desc')->where('user_id', $user->id)->where('order_status', 3)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders', 'setting'));
    }

    public function declinedOrder()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::orderBy('id', 'desc')->where('user_id', $user->id)->where('order_status', 4)->paginate(10);
        $setting = Setting::first();
        return view('user.order', compact('orders', 'setting'));
    }

    public function orderShow($orderId)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('user_id', $user->id)->where('order_id', $orderId)->first();
        $setting = Setting::first();
        $products = Product::all();
        return view('user.show_order', compact('order', 'setting', 'products'));
    }

    public function update_order_info(Request $request, $id)
    {

        $order = Order::where('id', $id)->first();

        $inside_fee = 0;
        $outside_fee = 0;

        $order_products = OrderProduct::where('order_id', $id)->get();

        foreach ($order_products as $order_product) {

            $product = Product::find($order_product->product_id);
            $inside_single = (int)$product->inside_fee * (int)$order_product->qty;
            $inside_fee += $inside_single;

            $outside_single = (int)$product->outside_fee * (int)$order_product->qty;
            $outside_fee += $outside_single;
        }

        $delivery_fee = 0;
        if ($request->address_type == 'inside') {
            $delivery_fee = $inside_fee;
        } else {
            $delivery_fee = $outside_fee;
        }

        $amount_real_currency = $order->sub_total + $delivery_fee;
        $order->amount_real_currency = $amount_real_currency;
        $order->shipping_cost = $delivery_fee;
        $order->address_type = $request->address_type;
        $order->address = $request->address;
        $order->additional_info = $request->additional_info;
        $order->save();

        $notification = trans('user_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function wishlist()
    {
        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->paginate(10);
        $setting = Setting::first();
        return view('user.wishlist', compact('wishlists', 'setting'));
    }

    public function myProfile()
    {
        $user = Auth::guard('web')->user();
        $defaultProfile = BannerImage::whereId('15')->first();
        $states = CountryState::all();
        $cities = City::all();
        return view('user.my_profile', compact('user', 'defaultProfile', 'states', 'cities'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $rules = [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'address' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->state_id = $request->state;
        $user->city_id = $request->city;
        $user->address = $request->address;
        $user->save();

        if ($request->file('image')) {
            $old_image = $user->image;
            $user_image = $request->image;
            $extention = $user_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;

            Image::make($user_image)
                ->save(public_path() . '/' . $image_name);

            $user->image = $image_name;
            $user->save();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }

        $notification = trans('user_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function changePassword()
    {
        return view('user.change_password');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('user_validation.Current password is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password minimum 4 character'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();


        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => trans('user_validation.Current password does not match')]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $notification = 'Password change successfully';
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function address()
    {
        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        return view('user.address', compact('billing', 'shipping'));
    }

    public function editBillingAddress()
    {
        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name', 'asc')->where('status', 1)->get();

        if ($billing) {
            $states = CountryState::orderBy('name', 'asc')->where(['status' => 1, 'country_id' => $billing->country_id])->get();
            $cities = City::orderBy('name', 'asc')->where(['status' => 1, 'country_state_id' => $billing->state_id])->get();
        } else {
            $states = CountryState::orderBy('name', 'asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name', 'asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        return view('user.edit_billing_address', compact('billing', 'countries', 'states', 'cities'));
    }

    public function updateBillingAddress(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',

            'address' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        $billing = BillingAddress::where('user_id', $user->id)->first();
        if ($billing) {
            $billing->name = $request->name;
            $billing->email = $request->email;
            $billing->phone = $request->phone;
            $billing->country_id = $request->country;
            $billing->state_id = $request->state;
            $billing->city_id = $request->city;
            $billing->zip_code = $request->zip_code;
            $billing->address = $request->address;
            $billing->save();

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.address')->with($notification);
        } else {
            $billing = new BillingAddress();
            $billing->user_id = $user->id;
            $billing->name = $request->name;
            $billing->email = $request->email;
            $billing->phone = $request->phone;
            $billing->country_id = $request->country;
            $billing->state_id = $request->state;
            $billing->city_id = $request->city;
            $billing->zip_code = $request->zip_code;
            $billing->address = $request->address;
            $billing->save();

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.address')->with($notification);
        }
    }


    public function editShippingAddress()
    {
        $user = Auth::guard('web')->user();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        $countries = Country::orderBy('name', 'asc')->where('status', 1)->get();

        if ($shipping) {
            $states = CountryState::orderBy('name', 'asc')->where(['status' => 1, 'country_id' => $shipping->country_id])->get();
            $cities = City::orderBy('name', 'asc')->where(['status' => 1, 'country_state_id' => $shipping->state_id])->get();
        } else {
            $states = CountryState::orderBy('name', 'asc')->where(['status' => 1, 'country_id' => 0])->get();
            $cities = City::orderBy('name', 'asc')->where(['status' => 1, 'country_state_id' => 0])->get();
        }
        return view('user.edit_shipping_address', compact('shipping', 'countries', 'states', 'cities'));
    }

    public function updateShippingAddress(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        $shipping = ShippingAddress::where('user_id', $user->id)->first();
        if ($shipping) {
            $shipping->name = $request->name;
            $shipping->email = $request->email;
            $shipping->phone = $request->phone;
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->state;
            $shipping->city_id = $request->city;
            $shipping->zip_code = $request->zip_code;
            $shipping->address = $request->address;
            $shipping->save();

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.address')->with($notification);
        } else {
            $shipping = new ShippingAddress();
            $shipping->user_id = $user->id;
            $shipping->name = $request->name;
            $shipping->email = $request->email;
            $shipping->phone = $request->phone;
            $shipping->country_id = $request->country;
            $shipping->state_id = $request->state;
            $shipping->city_id = $request->city;
            $shipping->zip_code = $request->zip_code;
            $shipping->address = $request->address;
            $shipping->save();

            $notification = trans('user_validation.Update Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.address')->with($notification);
        }
    }


    public function stateByCountry($id)
    {
        $states = CountryState::where(['status' => 1, 'country_id' => $id])->get();
        $response = '<option value="0">Select a State</option>';
        if ($states->count() > 0) {
            foreach ($states as $state) {
                $response .= "<option value=" . $state->id . ">" . $state->name . "</option>";
            }
        }
        return response()->json(['states' => $response]);
    }

    public function cityByState($id)
    {
        $cities = City::where(['status' => 1, 'country_state_id' => $id])->get();
        $response = '<option value="0">Select a City</option>';
        if ($cities->count() > 0) {
            foreach ($cities as $city) {
                $response .= "<option value=" . $city->id . ">" . $city->name . "</option>";
            }
        }
        return response()->json(['cities' => $response]);
    }

    public function addToWishlist($id)
    {
        $user = Auth::guard('web')->user();
        $product = Product::find($id);
        $isExist = Wishlist::where(['user_id' => $user->id, 'product_id' => $product->id])->count();
        if ($isExist == 0) {
            $wishlist = new Wishlist();
            $wishlist->product_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();
            $message = trans('user_validation.Wishlist added successfully');
            return response()->json(['status' => 1, 'message' => $message]);
        } else {
            $message = trans('user_validation.Already added');
            return response()->json(['status' => 0, 'message' => $message]);
        }
    }

    public function removeWishlist($id)
    {
        $wishlist = Wishlist::find($id);
        $wishlist->delete();
        $notification = trans('user_validation.Removed successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function storeProductReport(Request $request)
    {
        if ($request->subject == null) {
            $message = trans('user_validation.Subject filed is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }
        if ($request->description == null) {
            $message = trans('user_validation.Description filed is required');
            return response()->json(['status' => 0, 'message' => $message]);
        }
        $user = Auth::guard('web')->user();
        $report = new ProductReport();
        $report->user_id = $user->id;
        $report->seller_id = $request->seller_id;
        $report->product_id = $request->product_id;
        $report->subject = $request->subject;
        $report->description = $request->description;
        $report->save();

        $message = trans('user_validation.Report Submited successfully');
        return response()->json(['status' => 1, 'message' => $message]);
    }

    public function review()
    {
        $user = Auth::guard('web')->user();
        $reviews = ProductReview::orderBy('id', 'desc')->where(['user_id' => $user->id, 'status' => 1])->paginate(10);
        return view('user.review', compact('reviews'));
    }


    public function storeProductReview(Request $request)
    {
        $rules = [
            'rating' => 'required',
            'review' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('user_validation.Rating is required'),
            'review.required' => trans('user_validation.Review is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        $isExistOrder = false;
        $orders = Order::where(['user_id' => $user->id])->get();
        foreach ($orders as $key => $order) {
            foreach ($order->orderProducts as $key => $orderProduct) {
                if ($orderProduct->product_id == $request->product_id) {
                    $isExistOrder = true;
                }
            }
        }

        if ($isExistOrder) {
            $isReview = ProductReview::where(['product_id' => $request->product_id, 'user_id' => $user->id])->count();
            if ($isReview > 0) {
                $message = trans('user_validation.You have already submited review');
                return response()->json(['status' => 0, 'message' => $message]);
            }
            $review = new ProductReview();
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->review = $request->review;
            $review->product_vendor_id = $request->seller_id;
            $review->product_id = $request->product_id;
            $review->save();
            $message = trans('user_validation.Review Submited successfully');
            return response()->json(['status' => 1, 'message' => $message]);
        } else {
            $message = trans('user_validation.Opps! You can not review this product');
            return response()->json(['status' => 0, 'message' => $message]);
        }
    }

    public function updateReview(Request $request, $id)
    {
        $rules = [
            'rating' => 'required',
            'review' => 'required',
        ];
        $this->validate($request, $rules);
        $user = Auth::guard('web')->user();
        $review = ProductReview::find($id);
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        $notification = trans('user_validation.Updated successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
