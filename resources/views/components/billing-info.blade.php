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
</style>

<form id="checkout_form_update"
    action="{{ request()->ajax() ? route('user.checkout.update-billing-address') : route('update-checkout-cart-item') }}"
    method="POST">
    <div class="wsus__order_details" id="sticky_sidebar">
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
                        <img src="{{ asset($cartContent->options->image) }}" alt="blazer" class="img-fluid w-100">
                        <span>{{ $cartContent->qty }}</span>
                    </div>
                    <div class="wsus__order_details_text">
                        <p>{{ $cartContent->name }}</p>

                        <div class="checkout_quentity">
                            @if (request()->ajax())
                                x {{ $cartContent->qty }}
                            @else
                                <button type="button" class="btn btn-danger btn-sm decreament_checkout">-</button>
                                <input type="text" value="{{ $cartContent->qty }}"
                                    class="form-control checkout_cart_item" name="quantities[]" min="1"
                                    autocomplete="off">
                                <input type="hidden" value="{{ $cartContent->rowId }}" class="form-control"
                                    name="ids[]">
                                <button type="button" class="btn btn-success btn-sm increament_checkout">+</button>
                            @endif
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

        <input type="hidden" name="delivery_fee" id="delivery_fee" value="0.00">
        <input type="hidden" name="payment_method" value="Cash on Delivery">

        @php
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

        <div class="row">
            <div class="col-12">
                <div class="wsus__add_address_single">
                    <input type="text" placeholder="{{ __('user.Name') }} *" name="name" required
                        value="{{ old('name') }}">
                </div>
            </div>

            <div class="col-12">
                <div class="wsus__add_address_single">
                    <input type="text" placeholder="{{ __('user.Phone') }} *" name="phone" required
                        value="{{ old('phone') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="wsus__add_address_single">
                    <input type="text" name="address" placeholder="{{ __('user.Address') }} *" required
                        value="{{ old('address') }}">
                </div>
            </div>

            <div class="col-xl-12">
                <div class="wsus__add_address_single">
                    <textarea cols="3" rows="4" name="additional_info" placeholder="{{ __('user.Additional Information') }} ">{{ old('additional_info') }}</textarea>
                </div>
            </div>

        </div>
        <button type="button" class="common_btn checkout_submit">{{ __('user.Place Order Now') }}</button>
    </div>
</form>


<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            let total_price = "{{ $total_price }}"

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

            $('[name="state_id"]').on('change', function() {

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

            $('[name="shipping_method"]').on('change', function() {

                let val = $(this).val();

                const cost = $(this).data('cost')
                $(".delivery_charge").html(cost)
                $(".total_price").html((parseInt(total_price) + parseInt(cost)))

                $("#delivery_fee").val(cost)
            })



        });
    })(jQuery);
</script>




<script>
    $('.payment-item').on('click', function() {


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
