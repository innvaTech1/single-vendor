<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreadcrumbImage;
use Auth;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\StripePayment;
use App\Mail\OrderSuccessfully;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\Coupon;
use App\Models\ShoppingCart;
use App\Models\ProductVariantItem;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Shipping;
use App\Models\Address;
use App\Models\SslcommerzPayment;
use App\Models\ShoppingCartVariant;
use App\Models\MultiCurrency;
use Mail;
use Stripe;
use Cart;
use Session;
use Str;
use Razorpay\Api\Api;
use Exception;
use Redirect;

use App\Library\SslCommerz\SslCommerzNotification;
use Mollie\Laravel\Facades\Mollie;

use Twilio\Rest\Client;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;
use App\Models\BiztechSms;
use App\Models\MyfatoorahPayment;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class PaymentController extends Controller
{
    public $mfObj;

    public function __construct()
    {
        $this->middleware('auth:api')->except('sslcommerz_success', 'sslcommerz_failed', 'myfatoorah_webview_callback', 'placeOrder');
    }


    public function placeOrder(Request $request)
    {


        $rules = [
            'district' => 'required',
            'thana' => 'required',
            'address' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'shipping_method_id' => 'required',
        ];

        $customMessages = [
            'shipping_method_id.required' => 'Shipping method is required',
            'district.required' => 'District is required',
            'thana.required' => 'Thana is required',
            'address.required' => 'Address is required',
            'name.required' => 'Name is required',
            'phone.required' => 'Phone is required',

        ];

        $this->validate($request, $rules, $customMessages);

        $address_id = $this->storeAddress($request);
        // $address_id = 1;

        $totalProduct = collect($request->products)->map(function ($item) {
            return $item['qty'] ?? 0;
        })->sum();

        $shipping = Shipping::find($request->shipping_method_id);

        $order_result = $this->orderStore(null, $request->total, $totalProduct, 'Cash on Delivery', 'cash_on_delivery', 0, $shipping, $request->shippingFee, 0, 1, $address_id, $address_id, collect($request->products));

        Address::where('id', $address_id)->delete();

        return response()->json(['message' => 'Order submitted successfully. please wait for admin approval', 'order_id' => $order_result['order']->order_id], 200);
    }

    public function storeAddress(Request $request)
    {
        $address = new Address();
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->state_id = $request->district;
        $address->city_id = $request->thana;
        $address->type = 'home';
        $address->save();
        return $address->id;
    }

    public function cashOnDelivery(Request $request)
    {
        $currency = MultiCurrency::where('is_default', 'Yes')->first();
        $rules = [
            'shipping_address_id' => 'required',
            'billing_address_id' => 'required',
            'shipping_method_id' => 'required',
        ];
        $customMessages = [
            'shipping_address_id.required' => trans('user_validation.Shipping address is required'),
            'billing_address_id.required' => trans('user_validation.Billing address is required'),
            'shipping_method_id.required' => trans('user_validation.Shipping method is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('api')->user();

        $total = $this->calculateCartTotal($user, $request->coupon, $request->shipping_method_id);

        if ($total instanceof \Illuminate\Http\JsonResponse) {
            return $total; // Return the JSON response directly
        }

        $total_price = $total['total_price'];
        $coupon_price = $total['coupon_price'];
        $shipping_fee = $total['shipping_fee'];
        $productWeight = $total['productWeight'];
        $shipping = $total['shipping'];

        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');

        $order_result = $this->orderStore($user, $total_price, $totalProduct, 'Cash on Delivery', 'cash_on_delivery', 0, $shipping, $shipping_fee, $coupon_price, 1, $request->billing_address_id, $request->shipping_address_id);

        // $this->sendOrderSuccessMail($user, $total_price, 'Cash on Delivery', 0, $order_result['order'], $order_result['order_details']);

        // $this->sendOrderSuccessSms($user, $order_result['order']);

        $notification = trans('user_validation.Order submited successfully. please wait for admin approval');

        $order = $order_result['order'];
        $order_id = $order->order_id;

        return response()->json(['message' => $notification, 'order_id' => $order_id], 200);
    }

    public function payWithBank(Request $request)
    {
        $rules = [
            'type' => 'required',
            'shipping_address_id' => 'required',
            'billing_address_id' => 'required',
            'shipping_method_id' => 'required',
            'tnx_info' => 'required',
        ];
        $customMessages = [
            'shipping_address_id.required' => trans('user_validation.Shipping address is required'),
            'billing_address_id.required' => trans('user_validation.Billing address is required'),
            'shipping_method_id.required' => trans('user_validation.Shipping method is required'),
            'type.required' => "Bank Type is required",
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('api')->user();


        DB::beginTransaction();
        // try {
        $total = $this->calculateCartTotal($user, $request->coupon, $request->shipping_method_id);

        if ($total instanceof JsonResponse) {
            return $total; // Return the JSON response directly
        }
        $total_price = $total['total_price'];
        $coupon_price = $total['coupon_price'];
        $shipping_fee = $total['shipping_fee'];
        $productWeight = $total['productWeight'];
        $shipping = $total['shipping'];

        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');

        $transaction_id = $request->tnx_info;
        $order_result = $this->orderStore($user, $total_price, $totalProduct, $request->type, $transaction_id, 0, $shipping, $shipping_fee, $coupon_price, 1, $request->billing_address_id, $request->shipping_address_id);

        // $this->sendOrderSuccessMail($user, $total_price, $request->type, 0, $order_result['order'], $order_result['order_details']);

        // $this->sendOrderSuccessSms($user, $order_result['order']);

        $notification = trans('user_validation.Order submited successfully. please wait for admin approval');

        $order = $order_result['order'];
        $order_id = $order->order_id;

        DB::commit();
        return response()->json(['message' => $notification, 'order_id' => $order_id], 200);
        // } catch (Exception $e) {
        DB::rollBack();
        // return response()->json(['message' => $e->getMessage()], 403);
        // }
    }

    public function sslcommerzWebView(Request $request)
    {

        $sslcommerzPaymentInfo = SslcommerzPayment::first();
        $user = Auth::guard('api')->user();

        $total = $this->calculateCartTotal($user, $request->coupon, $request->shipping_method_id);
        $total_price = $total['total_price'];
        $total_price = round($total_price * $sslcommerzPaymentInfo->currency->currency_rate, 2);

        $frontend_success_url = $request->frontend_success_url;
        $frontend_faild_url = $request->frontend_faild_url;
        $request_from = $request->request_from;
        $shipping_address_id = $request->shipping_address_id;
        $billing_address_id = $request->billing_address_id;
        $shipping_method_id = $request->shipping_method_id;
        $coupon = $request->coupon;
        $token = $request->token;

        Session::put('frontend_success_url', $request->frontend_success_url);
        Session::put('frontend_faild_url', $request->frontend_faild_url);
        Session::put('request_from', $request->request_from);
        Session::put('shipping_address_id', $request->shipping_address_id);
        Session::put('billing_address_id', $request->billing_address_id);
        Session::put('shipping_method_id', $request->shipping_method_id);
        Session::put('coupon', $request->coupon);
        Session::put('user', $user);


        return view('sslcommerz_webview', compact('total_price', 'sslcommerzPaymentInfo', 'token'));
    }

    public function sslcommerz(Request $request)
    {

        $user = Auth::guard('api')->user();
        $coupon = Session::get('coupon');
        $shipping_method_id = Session::get('shipping_method_id');
        $total = $this->calculateCartTotal($user, $coupon, $shipping_method_id);
        $total_price = $total['total_price'];

        $sslcommerzPaymentInfo = SslcommerzPayment::first();
        $payableAmount = round($total_price * $sslcommerzPaymentInfo->currency->currency_rate, 2);

        $post_data = array();
        $post_data['total_amount'] = $payableAmount; # You cant not pay less than 10
        $post_data['currency'] = $sslcommerzPaymentInfo->currency->currency_code;
        $post_data['tran_id'] = uniqid();

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name;
        $post_data['cus_email'] = $user->email ? $user->email : 'johndoe@gmail.com';
        $post_data['cus_add1'] = '';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Country";
        $post_data['cus_phone'] =  $user->phone ? $user->phone : '123456789';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "";
        $post_data['ship_add1'] = "";
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = "";
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = "";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = 'Test Product';
        $post_data['product_category'] = "Package";
        $post_data['product_profile'] = "Package";

        config(['sslcommerz.apiCredentials.store_id' => $sslcommerzPaymentInfo->store_id]);
        config(['sslcommerz.apiCredentials.store_password' => $sslcommerzPaymentInfo->store_password]);
        config(['sslcommerz.success_url' => '/user/checkout/sslcommerz-success']);
        config(['sslcommerz.failed_url' => '/user/checkout/sslcommerz-failed']);

        $sslc = new SslCommerzNotification(config('sslcommerz'));

        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }


    public function sslcommerz_success(Request $request)
    {

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslcommerzPaymentInfo = SslcommerzPayment::first();

        config(['sslcommerz.apiCredentials.store_id' => $sslcommerzPaymentInfo->store_id]);
        config(['sslcommerz.apiCredentials.store_password' => $sslcommerzPaymentInfo->store_password]);
        config(['sslcommerz.success_url' => '/user/checkout/sslcommerz-success']);
        config(['sslcommerz.failed_url' => '/user/checkout/sslcommerz-failed']);

        $sslc = new SslCommerzNotification(config('sslcommerz'));

        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

        if ($validation == TRUE) {
            $user = Session::get('user');
            $coupon = Session::get('coupon');
            $shipping_address_id = Session::get('shipping_address_id');
            $billing_address_id = Session::get('billing_address_id');
            $shipping_method_id = Session::get('shipping_method_id');
            $payment_id = $request->get('payment_id');

            $total = $this->calculateCartTotal($user, $coupon, $shipping_method_id);

            $total_price = $total['total_price'];
            $coupon_price = $total['coupon_price'];
            $shipping_fee = $total['shipping_fee'];
            $productWeight = $total['productWeight'];
            $shipping = $total['shipping'];

            $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');
            $setting = Setting::first();

            $amount_real_currency = $total_price;
            $amount_usd = round($total_price / $setting->currency->currency_rate, 2);
            $currency_rate = $setting->currency->currency_rate;
            $currency_icon = $setting->currency->currency_icon;
            $currency_name = $setting->currency->currency_name;

            $transaction_id = $payment_id;
            $order_result = $this->orderStore($user, $total_price, $totalProduct, 'Instamojo', $transaction_id, 1, $shipping, $shipping_fee, $coupon_price, 0, $billing_address_id, $shipping_address_id);

            // $this->sendOrderSuccessMail($user, $total_price, 'Instamojo', 1, $order_result['order'], $order_result['order_details']);

            // $this->sendOrderSuccessSms($user, $order_result['order']);

            $frontend_success_url = Session::get('frontend_success_url');
            $request_from = Session::get('request_from');

            if ($request_from == 'react_web') {
                $order = $order_result['order'];
                $success_url = $frontend_success_url;
                $success_url = $success_url . "/" . $order->order_id;
                return redirect($success_url);
            } else {
                return response()->json(['message' => trans('user_validation.Order Successfully')], 200);
            }
        } else {
            $frontend_faild_url = Session::get('frontend_faild_url');
            $request_from = Session::get('request_from');

            if ($request_from == 'react_web') {
                return redirect($frontend_faild_url);
            } else {
                return response()->json(['message' => trans('user_validation.Payment Faild')], 403);
            }
        }
    }


    public function sslcommerz_failed(Request $request)
    {
        $frontend_faild_url = Session::get('frontend_faild_url');
        $request_from = Session::get('request_from');

        if ($request_from == 'react_web') {
            return redirect($frontend_faild_url);
        } else {
            return response()->json(['message' => trans('user_validation.Payment Faild')], 403);
        }
    }



    public function calculateCartTotal($user, $request_coupon, $request_shipping_method_id, $cart = [])
    {
        $total_price = 0;
        $coupon_price = 0;
        $shipping_fee = 0;
        $productWeight = 0;

        if ($user)
            $cartProducts = ShoppingCart::with('product', 'variants.variantItem')->where('user_id', $user->id)->select('id', 'product_id', 'qty')->get();
        else
            $cartProducts = $cart;

        if ((gettype($cartProducts) == 'array' && count($cartProducts)  == 0) || ($cartProducts->count() == 0)) {
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification], 403);
        }
        foreach ($cartProducts as $index => $cartProduct) {
            $variantPrice = 0;
            if ($cartProduct->variants) {
                foreach ($cartProduct->variants as $item_index => $var_item) {
                    $item = ProductVariantItem::find($var_item->variant_item_id);
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            $product = Product::select('id', 'price', 'offer_price', 'weight')->find($cartProduct->product_id);
            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;
            $weight = $product->weight;
            $weight = $weight * $cartProduct->qty;
            $productWeight += $weight;
            $isFlashSale = FlashSaleProduct::where(['product_id' => $product->id, 'status' => 1])->first();
            $today = date('Y-m-d H:i:s');
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;
                        $price = $price - $offerPrice;
                    }
                }
            }

            $price = $price * $cartProduct->qty;
            $total_price += $price;
        }

        if ($request_coupon) {
            $this->couponCalc($request_coupon);
        }

        $shipping = Shipping::find($request_shipping_method_id);
        if (!$shipping) {
            return response()->json(['message' => trans('user_validation.Shipping method not found')], 403);
        }

        $shipping_fee = $this->shippingCal($shipping);

        $total_price = ($total_price - $coupon_price) + $shipping_fee;
        $total_price = str_replace(array('\'', '"', ',', ';', '<', '>'), '', $total_price);
        $total_price = number_format($total_price, 2, '.', '');

        $arr = [];
        $arr['total_price'] = $total_price;
        $arr['coupon_price'] = $coupon_price;
        $arr['shipping_fee'] = $shipping_fee;
        $arr['productWeight'] = $productWeight;
        $arr['shipping'] = $shipping;

        return $arr;
    }

    public function shippingCal($shipping)
    {
        if ($shipping->shipping_fee == 0) {
            $shipping_fee = 0;
        } else {
            $shipping_fee = $shipping->shipping_fee;
        }

        return $shipping_fee;
    }

    public function couponCalc($request_coupon)
    {
        // calculate coupon cost
        if ($request_coupon) {
            $coupon = Coupon::where(['code' => $request_coupon, 'status' => 1])->first();
            if ($coupon) {
                if ($coupon->expired_date >= date('Y-m-d')) {
                    if ($coupon->apply_qty <  $coupon->max_quantity) {
                        if ($coupon->offer_type == 1) {
                            $couponAmount = $coupon->discount;
                            $couponAmount = ($couponAmount / 100) * $total_price;
                        } elseif ($coupon->offer_type == 2) {
                            $couponAmount = $coupon->discount;
                        }
                        $coupon_price = $couponAmount;

                        $qty = $coupon->apply_qty;
                        $qty = $qty + 1;
                        $coupon->apply_qty = $qty;
                        $coupon->save();
                    }
                }
            }
        }
    }
    public function orderStore($user, $total_price, $totalProduct, $payment_method, $transaction_id, $paymetn_status, $shipping, $shipping_fee, $coupon_price, $cash_on_delivery, $billing_address_id, $shipping_address_id, $cart = [])
    {
        if ($user)
            $cartProducts = ShoppingCart::with('product', 'variants.variantItem')->where('user_id', $user?->id)->select('id', 'product_id', 'qty')->get();
        else
            $cartProducts = $cart;
        if ($cartProducts->count() == 0) {
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification], 403);
        }

        $order = new Order();
        $orderId = substr(rand(0, time()), 0, 10);
        $order->order_id = $orderId;
        $order->user_id = $user ? $user->id : null;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $paymetn_status;
        $order->shipping_method = $shipping->shipping_rule;
        $order->shipping_cost = $shipping_fee;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();

        $order_details = '';
        $currency = MultiCurrency::where('is_default', 'Yes')->first();
        foreach ($cartProducts as $key => $cartProduct) {
            if (is_array($cartProduct))
                $cartProduct = (object)$cartProduct;

            $variantPrice = 0;


            if ($user && $cartProduct->variants) {
                foreach ($cartProduct->variants as $item_index => $var_item) {
                    $item = ProductVariantItem::find($var_item->variant_item_id);
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            } else if (isset($cartProduct->variants) && $cartProduct->variants) {
                foreach ($cartProduct->variants as $var_item) {
                    $item = ProductVariantItem::find($var_item);
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }

            // calculate product price
            $product = Product::select('id', 'price', 'offer_price', 'weight', 'vendor_id', 'qty', 'name')->find($cartProduct->product_id);
            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;
            $isFlashSale = FlashSaleProduct::where(['product_id' => $product->id, 'status' => 1])->first();
            $today = date('Y-m-d H:i:s');
            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;
                        $price = $price - $offerPrice;
                    }
                }
            }


            // store ordre product
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $price;
            $orderProduct->qty = $cartProduct->qty;
            $orderProduct->save();

            // update product stock
            $qty = $product->qty - $cartProduct->qty;
            $product->qty = $qty;
            $product->save();

            // store prouct variant


            if (isset($cartProduct->variants)) {
                foreach ($cartProduct->variants as $index => $variant) {
                    $item = ProductVariantItem::find($variant->variant_item_id);
                    $productVariant = new OrderProductVariant();
                    $productVariant->order_product_id = $orderProduct->id;
                    $productVariant->product_id = $cartProduct->product_id;
                    $productVariant->variant_name = $item->product_variant_name;
                    $productVariant->variant_value = $item->name;
                    $productVariant->save();
                }
            }

            $order_details .= 'Product: ' . $product->name . '<br>';
            $order_details .= 'Quantity: ' . $cartProduct->qty . '<br>';
            $order_details .= 'Price: ' . $currency?->currency_icon . $cartProduct?->qty * $price . '<br>';
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
        $orderAddress->billing_state = $billing->countryState?->name;
        $orderAddress->billing_city = $billing->city->name;
        $orderAddress->billing_address_type = $billing->type;
        $orderAddress->shipping_name = $shipping->name;
        $orderAddress->shipping_email = $shipping->email;
        $orderAddress->shipping_phone = $shipping->phone;
        $orderAddress->shipping_address = $shipping->address;
        $orderAddress->shipping_state = $shipping->countryState->name;
        $orderAddress->shipping_city = $shipping->city->name;
        $orderAddress->shipping_address_type = $shipping->type;
        $orderAddress->save();

        if ($user) {
            foreach ($cartProducts as $cartProduct) {
                ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
                $cartProduct->delete();
            }
        }

        $arr = [];
        $arr['order'] = $order;
        $arr['order_details'] = $order_details;

        return $arr;
    }


    public function sendOrderSuccessMail($user, $total_price, $payment_method, $payment_status, $order, $order_details)
    {
        $currency = MultiCurrency::where('is_default', 'Yes')->first();
        MailHelper::setMailConfig();

        $template = EmailTemplate::where('name', 'Order Successfully')->first();
        $subject = $template->subject;
        $message = $template->description;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{total_amount}}', $currency?->currency_icon . $total_price, $message);
        $message = str_replace('{{payment_method}}', $payment_method, $message);
        $message = str_replace('{{payment_status}}', $payment_status, $message);
        $message = str_replace('{{order_status}}', 'Pending', $message);
        $message = str_replace('{{order_date}}', $order->created_at->format('d F, Y'), $message);
        $message = str_replace('{{order_detail}}', $order_details, $message);
        Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));
    }

    public function sendOrderSuccessSms($user, $order)
    {
        $template = SmsTemplate::where('id', 3)->first();
        $message = $template->description;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{order_id}}', $order->order_id, $message);

        $twilio = TwilioSms::first();
        if ($twilio->enable_order_confirmation_sms == 1) {
            if ($user->phone) {
                try {
                    $account_sid = $twilio->account_sid;
                    $auth_token = $twilio->auth_token;
                    $twilio_number = $twilio->twilio_phone_number;
                    $recipients = $user->phone;
                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create(
                        $recipients,
                        ['from' => $twilio_number, 'body' => $message]
                    );
                } catch (Exception $ex) {
                }
            }
        }

        $biztech = BiztechSms::first();
        if ($biztech->enable_order_confirmation_sms == 1) {
            if ($user->phone) {
                try {
                    $apikey = $biztech->api_key;
                    $clientid = $biztech->client_id;
                    $senderid = $biztech->sender_id;
                    $senderid = urlencode($senderid);
                    $message = $message;
                    $msg_type = true;  // true or false for unicode message
                    $message  = urlencode($message);
                    $mobilenumbers = $user->phone; //8801700000000 or 8801700000000,9100000000
                    $url = "https://api.smsq.global/api/v2/SendSMS?ApiKey=$apikey&ClientId=$clientid&SenderId=$senderid&Message=$message&MobileNumbers=$mobilenumbers&Is_Unicode=$msg_type";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    $response = json_decode($response);
                } catch (Exception $ex) {
                }
            }
        }
    }


    public function myfatoorah_webview(Request $request)
    {
        // try {
        $rules = [
            'request_from' => 'required',
            'shipping_address_id' => 'required',
            'billing_address_id' => 'required',
            'shipping_method_id' => 'required',
        ];
        $this->validate($request, $rules);

        $user = Auth::guard('api')->user();

        Session::put('frontend_success_url', $request->frontend_success_url);
        Session::put('frontend_faild_url', $request->frontend_faild_url);
        Session::put('request_from', $request->request_from);
        Session::put('shipping_address_id', $request->shipping_address_id);
        Session::put('billing_address_id', $request->billing_address_id);
        Session::put('shipping_method_id', $request->shipping_method_id);
        Session::put('coupon', $request->coupon);
        Session::put('user', $user);

        $total = $this->calculateCartTotal($user, $request->coupon, $request->shipping_method_id);

        $total_price = $total['total_price'];
        $coupon_price = $total['coupon_price'];
        $shipping_fee = $total['shipping_fee'];
        $productWeight = $total['productWeight'];
        $shipping = $total['shipping'];


        $amount_real_currency = $total_price;
        $myfatoorah = MyfatoorahPayment::first();
        $price = $amount_real_currency * $myfatoorah->currency->currency_rate;
        $price = sprintf('%0.2f', $price);


        $paymentMethodId = 0;

        $curlData = $this->getPayLoadData();
        $data     = $this->mfObj->getInvoiceURL($curlData, $paymentMethodId);

        return redirect()->to($data['invoiceURL']);
        // } catch (\Exception $e) {
        //    if($request->request_from == 'react_web'){
        //         return redirect()->to($request->frontend_faild_url);
        //     }else{
        //         return redirect()->route('user.checkout.order-fail-url-for-mobile-app');
        //     }
        // }
    }


    private function getPayLoadData($orderId = null)
    {
        $callbackURL = route('user.checkout.myfatoorah-webview-callback');

        return [
            'CustomerName'       => 'FName LName',
            'InvoiceValue'       => '10',
            'DisplayCurrencyIso' => 'KWD',
            'CustomerEmail'      => 'test@test.com',
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+965',
            'CustomerMobile'     => '12345678',
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }
}
