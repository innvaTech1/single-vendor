@php
    $cartContents = Cart::content();
    $shippings = \App\Models\Shipping::all();
    $bankInfo = \App\Models\BankPayment::first();
    $bkash = \App\Models\MobilePayment::where('name', 'bkash')->first();
    $rocket = \App\Models\MobilePayment::where('name', 'rocket')->first();
    $nagad = \App\Models\MobilePayment::where('name', 'nagad')->first();

    $states = \App\Models\CountryState::all();
    $tax_amount = 0;
        $total_price = 0;
        $coupon_price = 0;
@endphp


<section class="product_popup_modal">
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal_wrapper">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        {{ __('user.Fill The Details') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="far fa-times"></i></button>
                </div>
                <div class="modal-body billing_info">
                    @include('components.billing-info')
                </div>
            </div>
        </div>
    </div>
</section>


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
