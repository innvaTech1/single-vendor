<div class="wsus__pro_details_text">
    <a class="title" href="{{ route('product-detail', $product->slug) }}">{{ $product->name }}</a>

    @if ($product->qty == 0)
        <p class="wsus__stock_area"><span class="in_stock">{{ __('user.Out of Stock') }}</span></p>
    @else
        <p class="wsus__stock_area"><span class="in_stock">{{ __('user.In stock') }}
                @if ($setting->show_product_qty == 1)
            </span> ({{ $product->qty }} {{ __('user.item') }})
    @endif
    </p>
    @endif


    @php
        $reviewQty = $product->reviews->where('status', 1)->count();
        $totalReview = $product->reviews->where('status', 1)->sum('rating');

        if ($reviewQty > 0) {
            $average = $totalReview / $reviewQty;

            $intAverage = intval($average);

            $nextValue = $intAverage + 1;
            $reviewPoint = $intAverage;
            $halfReview = false;
            if ($intAverage < $average && $average < $nextValue) {
                $reviewPoint = $intAverage + 0.5;
                $halfReview = true;
            }
        }
    @endphp

    @php
        $variantPrice = 0;
        $variants = $product->variants->where('status', 1);
        if ($variants->count() != 0) {
            foreach ($variants as $variants_key => $variant) {
                if ($variant->variantItems->where('status', 1)->count() != 0) {
                    $item = $variant->variantItems->where('is_default', 1)->first();
                    if ($item) {
                        $variantPrice += $item->price;
                    }
                }
            }
        }
        $isCampaign = false;
        $today = date('Y-m-d H:i:s');
        $campaign = App\Models\CampaignProduct::where([
            'status' => 1,
            'product_id' => $product->id,
        ])->first();
        if ($campaign) {
            $campaign = $campaign->campaign;
            if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
                $isCampaign = true;
            }
            $campaignOffer = $campaign->offer;
            $productPrice = $product->price;
            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
            $totalPrice = $product->price;
            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
        }

        $totalPrice = $product->price;
        if ($product->offer_price != null) {
            $offerPrice = $product->offer_price;
            $offer = $totalPrice - $offerPrice;
            $percentage = ($offer * 100) / $totalPrice;
            $percentage = round($percentage);
        }

    @endphp

    @if ($isCampaign)
        <h4>{{ $currencySetting->currency_icon }} <span
                id="mainProductModalPrice-{{ $product->id }}">{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}</span>
            <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
        </h4>
    @else
        @if ($product->offer_price == null)
            <h4>{{ $currencySetting->currency_icon }}<span
                    id="mainProductModalPrice-{{ $product->id }}">{{ sprintf('%.2f', $totalPrice + $variantPrice) }}</span>
            </h4>
        @else
            <h4>{{ $currencySetting->currency_icon }}<span
                    id="mainProductModalPrice-{{ $product->id }}">{{ sprintf('%.2f', $product->offer_price + $variantPrice) }}</span>
                <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
            </h4>
        @endif
    @endif

    @if ($reviewQty > 0)
        <p class="review">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $reviewPoint)
                    <i class="fas fa-star"></i>
                @elseif ($i > $reviewPoint)
                    @if ($halfReview == true)
                        <i class="fas fa-star-half-alt"></i>
                        @php
                            $halfReview = false;
                        @endphp
                    @else
                        <i class="fal fa-star"></i>
                    @endif
                @endif
            @endfor
            <span>({{ $reviewQty }} {{ __('user.review') }})</span>
        </p>
    @endif

    @if ($reviewQty == 0)
        <p class="review">
            <i class="fal fa-star"></i>
            <i class="fal fa-star"></i>
            <i class="fal fa-star"></i>
            <i class="fal fa-star"></i>
            <i class="fal fa-star"></i>
            <span>(0 {{ __('user.review') }})</span>
        </p>
    @endif

    @php
        $productPrice = 0;
        if ($isCampaign) {
            $productPrice = $campaignOfferPrice + $variantPrice;
        } else {
            if ($product->offer_price == null) {
                $productPrice = $totalPrice + $variantPrice;
            } else {
                $productPrice = $product->offer_price + $variantPrice;
            }
        }
    @endphp
    <form id="productModalFormId-{{ $product->id }}">
        <div class="wsus__quentity">
            <h5>{{ __('user.quantity') }} :</h5>
            <div class="modal_btn">
                <button onclick="productModalDecrement('{{ $product->id }}')" type="button"
                    class="btn btn-danger btn-sm">-</button>
                <input id="productModalQty-{{ $product->id }}" name="quantity" readonly class="form-control"
                    type="text" min="1" max="100" value="1" />
                <button onclick="productModalIncrement('{{ $product->id }}','{{ $product->qty }}')"
                    type="button" class="btn btn-success btn-sm">+</button>
            </div>
            <h3 class="d-none">{{ $currencySetting->currency_icon }}<span
                    id="productModalPrice-{{ $product->id }}">{{ sprintf('%.2f', $productPrice) }}</span>
            </h3>

            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="image" value="{{ $product->thumb_image }}">
            <input type="hidden" name="slug" value="{{ $product->slug }}">

        </div>
        @php
            $productVariants = App\Models\ProductVariant::where([
                'status' => 1,
                'product_id' => $product->id,
            ])->get();
        @endphp
        @if ($productVariants->count() != 0)
            <div class="wsus__selectbox">
                <div class="row">
                    @foreach ($productVariants as $productVariant)
                        @php
                            $items = App\Models\ProductVariantItem::orderBy('is_default', 'desc')
                                ->where([
                                    'product_variant_id' => $productVariant->id,
                                    'product_id' => $product->id,
                                ])
                                ->get();
                        @endphp
                        @if ($items->count() != 0)
                            <div class="col-xl-6 col-sm-6 mb-3">
                                <h5 class="mb-2">{{ $productVariant->name }}:</h5>

                                <input type="hidden" name="variants[]" value="{{ $productVariant->id }}">
                                <input type="hidden" name="variantNames[]" value="{{ $productVariant->name }}">

                                <select class="select_2 productModalVariant" name="items[]"
                                    data-product="{{ $product->id }}">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        <ul class="wsus__button_area">
            <li><button type="button" onclick="addToCartInProductModal('{{ $product->id }}')"
                    class="add_cart">{{ __('user.add to cart') }}</button></li>
            <li><a class="buy_now" href="javascript:;" onclick="addToBuyNow('{{ $product->id }}')">অর্ডার করুন
                </a></li>

        </ul>
    </form>
    @if ($product->sku)
        <p class="brand_model"><span>{{ __('user.Model') }} :</span> {{ $product->sku }}</p>
    @endif

    @if ($product->brand)
        <p class="brand_model"><span>{{ __('user.Brand') }} :</span> <a
                href="{{ route('product', ['brand' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>
        </p>
    @endif
    <p class="brand_model"><span>{{ __('user.Category') }} :</span> <a
            href="{{ route('product', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
    </p>
    <div class="wsus__pro_det_share d-none">
        <h5>{{ __('user.share') }} :</h5>
        <ul class="d-flex">
            <li><a class="facebook"
                    href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $product->slug) }}&t={{ $product->name }}"><i
                        class="fab fa-facebook-f"></i></a></li>
            <li><a class="twitter"
                    href="https://twitter.com/share?text={{ $product->name }}&url={{ route('product-detail', $product->slug) }}"><i
                        class="fab fa-twitter"></i></a></li>
            <li><a class="linkedin"
                    href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $product->slug) }}&title={{ $product->name }}"><i
                        class="fab fa-linkedin"></i></a></li>
            <li><a class="pinterest"
                    href="https://www.pinterest.com/pin/create/button/?description={{ $product->name }}&media=&url={{ route('product-detail', $product->slug) }}"><i
                        class="fab fa-pinterest-p"></i></a></li>
        </ul>
    </div>
</div>
