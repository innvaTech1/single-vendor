@extends('layout')
@section('title')
    <title>{{ __('user.Billing Address') }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ __('user.Billing Address') }}">
@endsection

@section('public-content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .selected {
            position: relative;
            text-align: center;
            width: 100%;
            font-size: small;
            color: #0d0d0d;
            /* Assuming qgreen is a shade of green like limegreen */
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 1rem;
            padding-right: 1rem;
            text-transform: uppercase;
            cursor: pointer;
            border: 2px solid rgb(215, 204, 245);
        }
        /* .active-item {
            color: #0d0d0d;
            border: 2px solid rgb(215, 204, 245);
        } */
    </style>
    <!--============================
                                     BREADCRUMB START
                                ==============================-->
    <section id="wsus__breadcrumb" style="background: url({{ asset($banner->image) }});">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>{{ __('user.Billing Address') }}</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">{{ __('user.home') }}</a></li>
                            <li><a href="{{ route('user.checkout.billing-address') }}">{{ __('user.Billing Address') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                                    BREADCRUMB END
                                ==============================-->


    <!--============================
                                      CHECK OUT PAGE START
                                ==============================-->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="wsus__cart_tab">
                        <li><a href="{{ route('cart') }}">{{ __('user.Shopping Cart') }}</a></li>
                        <li><a class="wsus__order_active" href="javascript:;">{{ __('Delivery Address') }}</a></li>

                    </ul>
                </div>
                <form class="wsus__checkout_form" method="POST"
                    action="{{ route('user.checkout.update-billing-address') }}" id="checkout_form_submit">
                    @csrf

                    <input type="hidden" name="payment_method" value="Cash on Delivery">
                    <div class="row">
                        <div class="col-xl-7 col-lg-6">
                            <div class="wsus__check_form">
                                <h5>{{ __('user.Delivery Address') }}</h5>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="wsus__add_address_single">
                                            <input type="text" placeholder="{{ __('user.Name') }} *" name="name" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="wsus__add_address_single">
                                            <input type="text" placeholder="{{ __('user.Phone') }} *" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="wsus__add_address_single">
                                            <select name="state_id" id="state_id" class="select_2">
                                                <option value="">{{ __('user.Select State') }}</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="wsus__add_address_single">
                                            <select name="city_id" id="city_id" class="select_2">
                                                <option value="">{{ __('user.Select City') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="wsus__add_address_single">
                                            <input type="text" name="address" placeholder="{{ __('user.Address') }} *"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="wsus__add_address_single">
                                            <textarea cols="3" rows="4" name="additional_info" placeholder="{{ __('user.Additional Information') }} "></textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <input type="hidden" name="delivery_fee" id="delivery_fee" value="0.00">

                </form>
                <div class="col-xl-5 col-lg-6">
                    <div class="wsus__order_details" id="sticky_sidebar">
                        <h5>{{ __('user.products') }}</h5>
                        <form id="checkout_form_update" action="{{ route('update-checkout-cart-item') }}" method="POST">
                            @csrf
                            <ul class="wsus__order_details_item">
                                @php
                                    $subTotal = 0;
                                @endphp
                                @foreach ($cartContents as $cartContent)
                                    @php
                                        $variantPrice = 0;
                                    @endphp
                                    <li>
                                        <div class="wsus__order_details_img">
                                            <img src="{{ asset($cartContent->options->image) }}" alt="blazer"
                                                class="img-fluid w-100">
                                            <span>{{ $cartContent->qty }}</span>
                                        </div>
                                        <div class="wsus__order_details_text">
                                            <p>{{ $cartContent->name }}</p>

                                            <div class="checkout_quentity">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm decreament_checkout">-</button>
                                                <input type="text" value="{{ $cartContent->qty }}"
                                                    class="form-control checkout_cart_item" name="quantities[]"
                                                    min="1" autocomplete="off">
                                                <input type="hidden" value="{{ $cartContent->rowId }}"
                                                    class="form-control" name="ids[]">
                                                <button type="button"
                                                    class="btn btn-success btn-sm increament_checkout">+</button>
                                            </div>
                                            <span>
                                                @php
                                                    $totalVariant = count($cartContent->options->variants);
                                                @endphp
                                                @foreach ($cartContent->options->variants as $indx => $variant)
                                                    @php
                                                        $variantPrice += $cartContent->options->prices[$indx];
                                                    @endphp
                                                    {{ $variant }}:
                                                    {{ $cartContent->options->values[$indx] }}{{ $totalVariant == ++$indx ? '' : ',' }}
                                                @endforeach

                                            </span>
                                        </div>

                                        @php
                                            $productPrice = $cartContent->price;
                                            $total = $productPrice * $cartContent->qty;
                                            $subTotal += $total;
                                        @endphp
                                        <div class="wsus__order_details_tk">
                                            <p>{{ $setting->currency_icon }}{{ $total }}</p>
                                        </div>
                                    </li>
                                    @php
                                        $totalVariant = 0;
                                    @endphp
                                @endforeach
                            </ul>
                        </form>
                        @php
                            $tax_amount = 0;
                            $total_price = 0;
                            $coupon_price = 0;

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
                        @endphp

                        <div class="wsus__order_details_summery">
                            <p>{{ __('user.subtotal') }}: <span>{{ $setting->currency_icon }}{{ $subTotal }}</span>
                            </p>

                            <p class="total"><span>{{ __('Shipping') }}(+):</span></p>

                            @foreach ($shippings as $ship)
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <input type="radio" name="shipping_method" id="shipping_method"
                                            data-cost="{{ $ship->shipping_fee }}" value="{{ $ship->shipping_rule }}">
                                        {{ $ship->shipping_rule }}
                                    </div>
                                    <div class="shipping_cost">
                                        {{ $setting->currency_icon }} {{ $ship->shipping_fee }}
                                    </div>
                                </div>
                            @endforeach

                            @include('user.payment-section')

                            <p class="total"><span>{{ __('user.total') }}:</span>
                                <span>{{ $setting->currency_icon }}<span class="total_price">{{ $total_price }}</span>
                                </span>
                            </p>
                        </div>
                        <button type="button" class="common_btn checkout_submit">{{ __('user.Place Order Now') }}</button>
                    </div>
                </div>
            </div>


        </div>
        </div>
    </section>
    {{-- <!--============================
            CHECK OUT PAGE END
    ==============================--> --}}


    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let total_price = "{{ $total_price }}"
                let inside_fee = "{{ $inside_fee }}"
                let outside_fee = "{{ $outside_fee }}"

                $(".checkout_cart_item").on('keyup change', function() {
                    $("#checkout_form_update").submit();
                })

                $(".checkout_submit").on('click', function() {
                    $("#checkout_form_submit").submit();
                })

                $(".increament_checkout").on('click', function() {
                    let root_div = $(this).parents('.checkout_quentity');
                    let quantity = root_div.find('.checkout_cart_item').val();
                    quantity = parseInt(quantity) + parseInt(1);
                    root_div.find('.checkout_cart_item').val(quantity);
                    $("#checkout_form_update").submit();

                })

                $(".decreament_checkout").on('click', function() {
                    let root_div = $(this).parents('.checkout_quentity');
                    let quantity = root_div.find('.checkout_cart_item').val();
                    if (quantity > 1) {
                        quantity = parseInt(quantity) - parseInt(1);
                        root_div.find('.checkout_cart_item').val(quantity);
                        $("#checkout_form_update").submit();
                    }
                })

                $('[name="state_id"]').on('change',function(){

                    const stateId = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ route('user.city-by-state', '') }}" + "/" + stateId,
                        success: function(response) {
                            console.log(response);
                            $("#city_id").html(response.cities);
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                })

                $('[name="shipping_method"]').on('change',function(){

                    let val = $(this).val();

                    const cost = $(this).data('cost')
                    $(".delivery_charge").html(cost)
                    $(".total_price").html((parseInt(total_price) + parseInt(cost)))

                    $("#delivery_fee").val(cost)
                })

            });
        })(jQuery);
    </script>
@endsection


@push('js')
    <script>
        $('.payment-item').on('click',function(){


            $('.payment-item').removeClass('selected')

            $(this).addClass('selected')
        })
        function setPaymentMethod(ship) {
            const paymentMethods = ['bank', 'bkash', 'rocket', 'nagad'];

            // Hide all inputs
            paymentMethods.forEach(method => {
                $(`.${method}-inputs`).addClass('d-none');
            });

            // Show the selected input
            if (paymentMethods.includes(ship)) {
                $(`.${ship}-inputs`).removeClass('d-none');
            }

            // Set the selected payment method
            $('input[name="payment_method"]').val(ship);
        }
    </script>
@endpush
