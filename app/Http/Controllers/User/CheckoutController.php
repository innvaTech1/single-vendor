<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use App\Models\CountryState;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\Setting;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\BankPayment;
use App\Models\Coupon;
use App\Models\InstamojoPayment;
use App\Models\MobilePayment;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\PaypalPayment;
use App\Models\PaymongoPayment;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\SslcommerzPayment;
use Cart;
use Exception;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:web');
    }

    public function checkoutBillingAddress()
    {
        if (Cart::count() == 0) {
            $notification = trans('user_validation.Your Shopping Cart is Empty');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('product')->with($notification);
        }
        $cartContents = Cart::content();

        $user = Auth::guard('web')->user();

        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $setting = Setting::first();

        $shippings = Shipping::all();


        $inside_fee = 0;
        $outside_fee = 0;

        foreach ($cartContents as $cartContent) {

            $product = Product::find($cartContent->id);
            $inside_single = (int)$product->inside_fee *  (int)$cartContent->qty;
            $inside_fee += $inside_single;


            $outside_single = ((int)$product->outside_fee * (int)$cartContent->qty);


            $outside_fee = $outside_fee + $outside_single;
        }


        $bankInfo = BankPayment::first();

        $sslcommerz = SslcommerzPayment::first();

        $bkash = MobilePayment::where('name', 'bkash')->first();
        $rocket = MobilePayment::where('name', 'rocket')->first();
        $nagad = MobilePayment::where('name', 'nagad')->first();

        $states = CountryState::all();


        return view('user.checkout_billing_address', compact('banner', 'cartContents', 'setting', 'inside_fee', 'outside_fee', 'shippings', "bankInfo", 'sslcommerz', 'bkash', 'rocket', 'nagad', 'states'));
    }

    public function payment()
    {
        if (!Session::get('is_billing') && !Session::put('is_shipping')) {
            return redirect()->route('user.checkout.billing-address');
        }

        $shipping_fee = 0;
        $shipping_method = Session::get('shipping_method');
        $shippingMethod = ShippingMethod::where('id', $shipping_method)->first();
        $shipping_fee = $shippingMethod->fee;

        $banner = BreadcrumbImage::where(['id' => 2])->first();
        $cartContents = Cart::content();
        $setting = Setting::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $user = Auth::guard('web')->user();
        $paystack = PaystackAndMollie::first();
        $bankPayment = BankPayment::first();
        $instamojoPayment = InstamojoPayment::first();
        $paypal = PaypalPayment::first();
        $paymongo = PaymongoPayment::first();
        return view('payment', compact('banner', 'cartContents', 'shipping_fee', 'setting', 'stripe', 'razorpay', 'flutterwave', 'user', 'paystack', 'bankPayment', 'instamojoPayment', 'paypal', 'paymongo'));
    }


    public function updateShippingBillingAddress(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'shipping_method' => 'required',
            'agree_terms_condition' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'country.required' => trans('user_validation.Country is required'),
            'zip_code.required' => trans('user_validation.Zip code is required'),
            'address.required' => trans('user_validation.Address is required'),
            'agree_terms_condition.required' => trans('user_validation.Agree field is required'),
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
        }
        Session::put('is_shipping', 'yes');
        Session::put('shipping_method', $request->shipping_method);
        Session::put('shipping_method', $request->shipping_method);
        if ($request->agree_terms_condition) {
            Session::put('agree_terms_condition', 'yes');
        }
        if ($request->addition_information) {
            Session::put('addition_information', $request->addition_information);
        }
        return redirect()->route('user.checkout.payment');
    }


    public function placeOrder(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'delivery_fee' => 'required',
            'shipping_method' => 'required',
            'address' => 'required',
            'payment_method' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'address.required' => trans('user_validation.Address is required'),
            'payment_method.required' => trans('user_validation.Payment method is required'),
            'shipping_method.required' => trans('user_validation.Shipping method is required'),
            'delivery_fee.required' => trans('user_validation.Delivery fee is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        DB::beginTransaction();
        try {
            $address_id = $this->storeAddress($request);
            $this->orderStore($request, auth('web')->user(),  $request->delivery_fee, $address_id, $address_id);

            Address::where('id', $address_id)->delete();


            $notification = trans('user_validation.Order submited successfully. please wait for admin approval');
            $notification = array('messege' => $notification, 'alert-type' => 'success');

            $route = 'user.order';
            if (!auth('web')->user()) {
                $route = 'track-order';
            }
            DB::commit();

            // forget cart
            Cart::destroy();
            return redirect()->route($route)->with($notification);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $notification = trans('user_validation.Something went wrong, please try again');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

    public function storeAddress(Request $request)
    {
        $address = new Address();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->type = 'home';
        $address->save();
        return $address->id;
    }

    public function orderStore($request, $user, $shipping_fee, $billing_address_id, $shipping_address_id)
    {

        $tax_amount = 0;
        $total_price = 0;
        $coupon_price = 0;

        $user = Auth::guard('web')->user();
        $billing = Address::where('id', $billing_address_id)->first();
        $shipping = Address::where('id', $shipping_address_id)->first();

        $cartContents = Cart::content();
        $shipping_method = $request->shipping_method;

        $shippingMethod = Shipping::where('shipping_rule', $shipping_method)->first();


        foreach ($cartContents as $key => $content) {
            $tax = $content->options->tax * $content->qty;
            $tax_amount = $tax_amount + $tax;
        }


        $subTotal = 0;
        foreach ($cartContents as $cartContent) {
            $variantPrice = 0;
            foreach ($cartContent->options->variants as $indx => $variant) {
                $variantPrice += $cartContent->options->prices[$indx];
            }
            $productPrice = $cartContent->price;
            $total = $productPrice * $cartContent->qty;
            $subTotal += $total;
        }

        $total_price = $tax_amount + $subTotal;
        if (Session::get('coupon_price') && Session::get('offer_type')) {
            if (Session::get('offer_type') == 1) {
                $coupon_price = Session::get('coupon_price');
                $coupon_price = ($coupon_price / 100) * $total_price;
            } else {
                $coupon_price = Session::get('coupon_price');
            }
        }

        $total_price = $total_price - $coupon_price;
        $total_price += $shipping_fee;
        $total_price = str_replace(array('\'', '"', ',', ';', '<', '>'), '', $total_price);
        $setting = Setting::first();

        $order = new Order();
        $orderId = substr(rand(0, time()), 0, 10);
        $order->order_id = $orderId;
        $order->user_id = $user ? $user->id : null;
        $order->sub_total = $subTotal;
        $order->product_qty = Cart::count();
        $order->total_amount = $total_price;
        $order->payment_method = $request->payment_method;
        $order->transection_id = $request->transaction_info;
        $order->payment_status = 0;
        $order->shipping_method = $shippingMethod->shipping_rule;
        $order->shipping_cost = $shipping_fee;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $request->payment_method == 'Cash on Delivery' ? 1 : 0;
        $order->additional_info = $request->additional_info;
        $order->save();


        if (Session::get('coupon_name')) {
            $coupon = Coupon::where(['code' => Session::get('coupon_name')])->first();
            $qty = $coupon->apply_qty;
            $qty = $qty + 1;
            $coupon->apply_qty = $qty;
            $coupon->save();
        }


        $order_details = '';
        foreach ($cartContents as $key => $cartContent) {

            $productUnitPrice = 0;
            $variantPrice = 0;
            foreach ($cartContent->options->variants as $indx => $variant) {
                $variantPrice += $cartContent->options->prices[$indx];
            }
            $productUnitPrice = $cartContent->price;

            $product = Product::find($cartContent->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartContent->id;
            $orderProduct->seller_id = $product->vendor_id;
            $orderProduct->product_name = $cartContent->name;
            $orderProduct->unit_price = $productUnitPrice;
            $orderProduct->qty = $cartContent->qty;
            $orderProduct->vat = $cartContent->options->tax * $cartContent->qty;
            $orderProduct->save();

            $productStock = Product::find($cartContent->id);
            $qty = $productStock->qty - $cartContent->qty;
            $productStock->qty = $qty;
            $productStock->save();

            if (count($cartContent->options->variants) > 0) {
                foreach ($cartContent->options->variants as $index => $variant) {
                    $productVariant = new OrderProductVariant();
                    $productVariant->order_product_id = $orderProduct->id;
                    $productVariant->product_id = $cartContent->id;
                    $productVariant->variant_name = $variant;
                    $productVariant->variant_value = $cartContent->options->values[$index];
                    $productVariant->variant_price = $cartContent->options->prices[$index];
                    $productVariant->save();
                }
            }

            $order_details .= 'Product: ' . $cartContent->name . '<br>';
            $order_details .= 'Quantity: ' . $cartContent->qty . '<br>';
            $order_details .= 'Price: ' . $setting->currency_icon . $cartContent->qty * $productUnitPrice . '<br>';
        }

        // store shipping and billing address
        $billing = Address::find($billing_address_id);
        $shipping = Address::find($shipping_address_id);
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->billing_name = $billing->name;
        $orderAddress->billing_email = $billing->email;
        $orderAddress->billing_phone = $billing->phone;
        $orderAddress->billing_address = $billing->address;
        $orderAddress->billing_address_type = $billing->type;
        $orderAddress->shipping_name = $shipping->name;
        $orderAddress->shipping_email = $shipping->email;
        $orderAddress->shipping_phone = $shipping->phone;
        $orderAddress->shipping_address = $shipping->address;
        $orderAddress->shipping_address_type = $shipping->type;
        $orderAddress->save();


        $arr = [];
        $arr['order'] = $order;
        $arr['order_details'] = $order_details;



        // create courier order
        // if ($request->payment_method == 'Cash on Delivery') {

        $orderData =
            [

                'invoice' => $order->order_id,

                'recipient_name' => $shipping->name,

                'recipient_phone' => $shipping->phone,

                'recipient_address' => $shipping->address,

                'cod_amount' => $request->payment_method == 'Cash on Delivery' ? $order->total_amount : 0,

                'note' => $request->additional_info,
            ];

        $response = SteadfastCourier::placeOrder($orderData);


        if ($response['status'] == 200) {
            $arr['tracking_code'] = $response['consignment']['tracking_code'];

            $order->tracking_code = $response['consignment']['tracking_code'];
            $order->consignment_id = $response['consignment']['consignment_id'];
            $order->save();
        }
        // clear coupon
        Session::forget('coupon_name');
        Session::forget('coupon_discount');

        return $arr;
    }
}
