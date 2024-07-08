@php
    $variantPrice = 0;
    $activeVariants = $product->variants->where('status', 1);

    foreach ($activeVariants as $variant) {
        $defaultItem = $variant->variantItems->where('status', 1)->where('is_default', 1)->first();
        if ($defaultItem) {
            $variantPrice += $defaultItem->price;
        }
    }

    $isCampaign = false;
    $today = date('Y-m-d H:i:s');

    $today = now(); // Assuming $today is set to the current date

    $campaignProduct = App\Models\CampaignProduct::where([
        'status' => 1,
        'product_id' => $product->id,
    ])->first();

    $totalPrice = $product->price;
    $offerPrice = $product->offer_price;

    if ($campaignProduct) {
        $campaign = $campaignProduct->campaign;
        if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
            $isCampaign = true;
            $campaignOffer = $campaign->offer;
            $campaignOfferPrice = $totalPrice - ($campaignOffer / 100) * $totalPrice;
        }
    }

    if ($offerPrice !== null) {
        $offer = $totalPrice - $offerPrice;
        $percentage = round(($offer * 100) / $totalPrice);
    }

@endphp
<div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _new">
    <div class="wsus__product_item wsus__after">
        @if ($isCampaign)
            <span class="wsus__minus">-{{ $campaignOffer }}%</span>
        @else
            @if ($product->offer_price != null)
                <span class="wsus__minus">-{{ $percentage }}%</span>
            @endif
        @endif
        <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
            <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
            <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_2" />
        </a>

        <ul class="wsus__single_pro_icon">

        </ul>
        <div class="wsus__product_details">
            <a class="wsus__category"
                href="{{ route('product', ['category' => $product->category->slug]) }}">{{ $product->category->name }}
            </a>


            <p class="wsus__pro_rating">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $product->avgReview)
                        <i class="fas fa-star"></i>
                    @elseif($i - $product->avgReview == 0.5)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
                <span>({{ $product->totalReviews() }} {{ __('user.review') }})</span>
            </p>


            <a class="wsus__pro_name"
                href="{{ route('product-detail', $product->slug) }}">{{ $product->short_name }}</a>
            @if ($isCampaign)
                <p class="wsus__price">
                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                    <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                </p>
            @else
                @if ($product->offer_price == null)
                    <p class="wsus__price">
                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                    </p>
                @else
                    <p class="wsus__price">
                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $product->offer_price + $variantPrice) }}
                        <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                    </p>
                @endif
            @endif


            <a class="buy_button" href="javascript:;"
                onclick="addToBuyNow('{{ $product->id }}')">{{ __('user.Order Now') }} </a>

            <a class="add_cart" onclick="addToCartMainProduct('{{ $product->id }}')" href="javascript:;"><i
                    class="far fa-shopping-basket"></i></a>

        </div>
    </div>
</div>
