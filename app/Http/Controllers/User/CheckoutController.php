<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use Auth;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\Address;
use App\Models\Vendor;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\BankPayment;
use App\Models\InstamojoPayment;
use App\Models\PaypalPayment;
use App\Models\SslcommerzPayment;
use App\Models\ShoppingCart;
use App\Models\Coupon;
use App\Models\MobilePayment;
use App\Models\Shipping;
use App\Models\MyfatoorahPayment;
use Cart;
use Session;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function checkout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $cartProducts = ShoppingCart::with('product', 'variants.variantItem')->where('user_id', $user->id)->select('id', 'product_id', 'qty')->get();

        if ($cartProducts->count() == 0) {
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification], 403);
        }

        $addresses = Address::with('country', 'countryState', 'city')->where(['user_id' => $user->id])->get();
        $shippings = Shipping::all();

        $couponOffer = '';
        if ($request->coupon) {
            $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();
            if ($coupon) {
                if ($coupon->expired_date >= date('Y-m-d')) {
                    if ($coupon->apply_qty <  $coupon->max_quantity) {
                        $couponOffer = $coupon;
                    }
                }
            }
        }
        $bankPaymentInfo = BankPayment::first();

        $sslcommerz = SslcommerzPayment::first();

        $bkash = MobilePayment::where('name', 'bkash')->first();
        $rocket = MobilePayment::where('name', 'rocket')->first();
        $nagad = MobilePayment::where('name', 'nagad')->first();

        return response()->json([
            'cartProducts' => $cartProducts,
            'addresses' => $addresses,
            'shippings' => $shippings,
            'couponOffer' => $couponOffer,
            'bankPaymentInfo' => $bankPaymentInfo,
            'sslcommerz' => $sslcommerz,
            'bkash' => $bkash,
            'rocket' => $rocket,
            'nagad' => $nagad,
        ], 200);
    }
}
