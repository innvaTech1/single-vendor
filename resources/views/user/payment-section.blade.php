<div class="mt-3 mb-5 position-relative">
    <div class="w-100">
        <div class="d-flex flex-column gap-3">
            @if ($bankInfo && $bankInfo->cash_on_delivery_status)
                <div onclick="setPaymentMethod('Cash on Delivery')"
                    class="payment-item bg-light text-center w-100 h-50px text-sm d-flex justify-content-center align-items-center px-3 text-uppercase cursor-pointer">
                    <div class="w-100">
                        <span class="text-dark font-weight-bold text-base">
                            {{ __('user.Cash On Delivery') }}
                        </span>
                    </div>

                        <span data-aos="zoom-in"
                            class="position-absolute text-white z-index-10 w-6 h-6 rounded-circle bg-warning"
                            style="right: -10px; top: -10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>

                </div>
            @endif
            @if ($bankInfo && $bankInfo->status)
                <div onclick="setPaymentMethod('bank')"
                    class="payment-item text-center bg-light  w-100 h-50px font-weight-bold text-sm text-white d-flex justify-content-center align-items-center px-3 text-uppercase cursor-pointer">
                    <div class="w-100">
                        <span class="text-dark font-weight-bold text-base">
                            {{ __('user.Bank Payment') }}

                        </span>
                    </div>

                        <span data-aos="zoom-in"
                            class="position-absolute text-white z-index-10 w-6 h-6 rounded-circle bg-warning"
                            style="right: -10px; top: -10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>

                </div>
            @endif
            @if ($bkash && $bkash->status)
                <div onclick="setPaymentMethod('bkash')"
                    class="payment-item text-center bg-light  w-100 h-50px font-weight-bold text-sm text-white d-flex justify-content-center align-items-center px-3 text-uppercase cursor-pointer">
                    <div class="w-100">
                        <span class="text-dark font-weight-bold text-base">
                            {{__('user.Bkash Payment')}}
                        </span>
                    </div>

                        <span data-aos="zoom-in"
                            class="position-absolute text-white z-index-10 w-6 h-6 rounded-circle bg-warning"
                            style="right: -10px; top: -10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>

                </div>
            @endif
            @if ($rocket && $rocket->status)
                <div onclick="setPaymentMethod('rocket')"
                    class="payment-item text-center bg-light  w-100 h-50px font-weight-bold text-sm text-white d-flex justify-content-center align-items-center px-3 text-uppercase cursor-pointer">
                    <div class="w-100">
                        <span class="text-dark font-weight-bold text-base">
                            {{__('user.Rocket Payment')}}
                        </span>
                    </div>

                        <span data-aos="zoom-in"
                            class="position-absolute text-white z-index-10 w-6 h-6 rounded-circle bg-warning"
                            style="right: -10px; top: -10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>

                </div>
            @endif
            @if ($nagad && $nagad->status)
                <div onclick="setPaymentMethod('nagad')"
                    class="payment-item text-center bg-light  w-100 h-50px font-weight-bold text-sm text-white d-flex justify-content-center align-items-center px-3 text-uppercase cursor-pointer">
                    <div class="w-100">
                        <span class="text-dark font-weight-bold text-base">
                            {{ __('Nagad Payment') }}
                        </span>
                    </div>

                        <span data-aos="zoom-in"
                            class="position-absolute text-white z-index-10 w-6 h-6 rounded-circle bg-warning"
                            style="right: -10px; top: -10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>

                </div>
            @endif
        </div>
    </div>
</div>
@if ($bankInfo)
    <div class="w-100 bank-inputs mt-5 d-none">
        <div class="input-item mb-5">
            <div class="bank-info-alert w-100 p-3 rounded mb-4 overflow-auto" style="background:#18587a">
                <pre class="w-100 table table-fixed text-white">
          {{ $bankInfo->account_info }}
        </pre>
            </div>
            <h6 class="input-label text-capitalize text-sm font-weight-bold leading-24 text-dark mb-2">
                {{ __('user.Transaction Information') }}*
            </h6>
            <textarea cols="5" rows="7"
                class="w-100 focus:ring-0 focus:outline-none py-3 px-4 border placeholder-text-sm text-sm"
                placeholder="Example:{{ "\n" . $bankInfo->account_info }}" name="transaction_info"></textarea>
        </div>
    </div>
@endif

@if ($bkash)
    <div class="w-100 bkash-inputs mt-5 d-none" style="transition: 1s all ease">
        <div class="input-item mb-5">
            <div class="bank-info-alert w-100 p-3 rounded mb-4 overflow-auto" style="background:#18587a">
                <pre class="w-100 table table-fixed text-white">
          {{ $bkash->account_info }}
        </pre>
            </div>
            <h6 class="input-label text-capitalize text-sm font-weight-bold leading-24 text-dark mb-2">
                {{ __('user.Transaction Information') }}*
            </h6>
            <textarea cols="5" rows="3"
                class="w-100 focus:ring-0 focus:outline-none py-3 px-4 border placeholder-text-sm text-sm"
                placeholder="Example:{{ "\n" . $bkash->instruction }}" name="transaction_info"></textarea>
        </div>
    </div>
@endif

@if ($rocket)
    <div class="w-100 rocket-inputs mt-5 d-none">
        <div class="input-item mb-5">
            <div class="bank-info-alert w-100 p-3 rounded mb-4 overflow-auto" style="background:#18587a">
                <pre class="w-100 table table-fixed text-white">
          {{ $rocket->account_info }}
        </pre>
            </div>
            <h6 class="input-label text-capitalize text-sm font-weight-bold leading-24 text-dark mb-2">
                {{ __('user.Transaction Information') }}*
            </h6>
            <textarea cols="5" rows="3"
                class="w-100 focus:ring-0 focus:outline-none py-3 px-4 border placeholder-text-sm text-sm"
                placeholder="Example:{{ "\n" . $rocket->instruction }}" name="transaction_info"></textarea>
        </div>
    </div>
@endif


@if ($nagad)
    <div class="w-100 nagad-inputs mt-5 d-none">
        <div class="input-item mb-5">
            <div class="bank-info-alert w-100 p-3 rounded mb-4 overflow-auto" style="background:#18587a; color:#fff">
                <pre class="w-100 table table-fixed text-white">
          {{ $nagad->account_info }}
        </pre>
            </div>
            <h6 class="input-label text-capitalize text-sm font-weight-bold leading-24 text-dark mb-2">
                {{ __('user.Transaction Information') }}*
            </h6>
            <textarea cols="5" rows="3"
                class="w-100 focus:ring-0 focus:outline-none py-3 px-4 border placeholder-text-sm text-sm"
                placeholder="Example:{{ "\n" . $nagad->instruction }}" name="transaction_info"></textarea>
        </div>
    </div>
@endif
