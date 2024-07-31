@extends('layout')
@section('title')
    <title>{{ $seoSetting->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seoSetting->seo_description }}">
@endsection

@section('public-content')
    <style>
        .cursor {
            cursor: pointer !important;
        }
    </style>
    <!--============================
            BANNER PART START
        ==============================-->
    @php
        $sliderVisibility = $visibilities->where('id', 1)->first();
    @endphp
    @if ($sliderVisibility->status == 1)
        <section id="wsus__banner">
            <div class="container">
                <div class="row">

                    <div class="col-xl-12">
                        <div class="wsus__banner_content">
                            <div class="row banner_slider">
                                @foreach ($sliders->take($sliderVisibility->qty) as $slider)
                                    <div class="col-xl-12">
                                        <div class="wsus__single_slider cursor"
                                            style="background: url({{ asset($slider->image) }});"
                                            data-link="{{ $slider->link }}">
                                            <div class="wsus__single_slider_text">
                                                @if ($slider->title)
                                                    <h1>{!! nl2br($slider->title) !!}</h1>
                                                @endif
                                                @if ($slider->description)
                                                    <h6>{!! nl2br($slider->description) !!}</h6>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
            BANNER PART END
        ==============================-->

    <!--============================
            FLASH SELL START
        ==============================-->
    @php
        $campaignVisibility = $visibilities->where('id', 3)->first();
    @endphp
    @if ($campaignVisibility->status == 1)
        <section id="wsus__flash_sell">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 offer_time">
                        @if ($campaign)
                            @php
                                $end = strtotime($campaign->end_date);
                                $current_time = Carbon\Carbon::now()->timestamp;
                                $capmaign_time = $end - $current_time;
                            @endphp
                            <script>
                                var capmaign_time = {{ $capmaign_time }};
                            </script>

                            <script>
                                var campaign_end_year = {{ date('Y', strtotime($campaign->end_date)) }}
                                var campaign_end_month = {{ date('m', strtotime($campaign->end_date)) }}
                                var campaign_end_date = {{ date('d', strtotime($campaign->end_date)) }}
                                var campaign_hour = {{ date('H', strtotime($campaign->end_date)) }}
                                var campaign_min = {{ date('i', strtotime($campaign->end_date)) }}
                                var campaign_sec = {{ date('s', strtotime($campaign->end_date)) }}
                            </script>

                            <div class="wsus__flash_coundown">
                                <span class="end_text">{{ $campaign->name }}</span>

                                <div class="simply-countdown campaign-details"></div>

                                <a class="common_btn"
                                    href="{{ route('campaign-detail', $campaign->slug) }}">{{ __('user.see more') }} <i
                                        class="fas fa-caret-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row flash_sell_slider">
                    @if ($campaignProducts != null)
                        @foreach ($campaignProducts->take($campaignVisibility->qty) as $campaignProduct)
                            <div class="col-xl-3 col-sm-6 col-lg-4">
                                <div class="wsus__product_item">
                                    @if ($campaignProduct->product->new_product == 1)
                                        <span class="wsus__new">{{ __('user.New') }}</span>
                                    @elseif ($campaignProduct->product->is_featured == 1)
                                        <span class="wsus__new">{{ __('user.Featured') }}</span>
                                    @elseif ($campaignProduct->product->is_top == 1)
                                        <span class="wsus__new">{{ __('user.Top') }}</span>
                                    @elseif ($campaignProduct->product->is_best == 1)
                                        <span class="wsus__new">{{ __('user.Best') }}</span>
                                    @endif

                                    @php
                                        $variantPrice = 0;
                                        $variants = $campaignProduct->product->variants->where('status', 1);
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
                                            'product_id' => $campaignProduct->product->id,
                                        ])->first();
                                        if ($campaign) {
                                            $campaign = $campaign->campaign;
                                            if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
                                                $isCampaign = true;
                                            }
                                            $campaignOffer = $campaign->offer;
                                            $productPrice = $campaignProduct->product->price;
                                            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                            $totalPrice = $campaignProduct->product->price;
                                            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                        }

                                        $totalPrice = $campaignProduct->product->price;
                                        if ($campaignProduct->product->offer_price != null) {
                                            $offerPrice = $campaignProduct->product->offer_price;
                                            $offer = $totalPrice - $offerPrice;
                                            $percentage = ($offer * 100) / $totalPrice;
                                            $percentage = round($percentage);
                                        }
                                    @endphp

                                    @if ($isCampaign)
                                        <span class="wsus__minus">-{{ $campaignOffer }}%</span>
                                    @else
                                        @if ($campaignProduct->product->offer_price != null)
                                            <span class="wsus__minus">-{{ $percentage }}%</span>
                                        @endif
                                    @endif

                                    <a class="wsus__pro_link"
                                        href="{{ route('product-detail', $campaignProduct->product->slug) }}">
                                        <img src="{{ asset($campaignProduct->product->thumb_image) }}" alt="product"
                                            class="img-fluid w-100 img_1" />
                                        <img src="{{ asset($campaignProduct->product->thumb_image) }}" alt="product"
                                            class="img-fluid w-100 img_2" />
                                    </a>
                                    <ul class="wsus__single_pro_icon">

                                    </ul>
                                    <div class="wsus__product_details">
                                        <a class="wsus__category"
                                            href="{{ route('product', ['category' => $campaignProduct->product->category->slug]) }}">{{ $campaignProduct->product->category->name }}
                                        </a>
                                        @php
                                            $reviewQty = $campaignProduct->product->reviews
                                                ->where('status', 1)
                                                ->count();
                                            $totalReview = $campaignProduct->product->reviews
                                                ->where('status', 1)
                                                ->sum('rating');

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

                                        @if ($reviewQty > 0)
                                            <p class="wsus__pro_rating">
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
                                            <p class="wsus__pro_rating">
                                                <i class="fal fa-star"></i>
                                                <i class="fal fa-star"></i>
                                                <i class="fal fa-star"></i>
                                                <i class="fal fa-star"></i>
                                                <i class="fal fa-star"></i>
                                                <span>(0 {{ __('user.review') }})</span>
                                            </p>
                                        @endif
                                        <a class="wsus__pro_name"
                                            href="{{ route('product-detail', $campaignProduct->product->slug) }}">{{ $campaignProduct->product->short_name }}</a>

                                        @if ($isCampaign)
                                            <p class="wsus__price">
                                                {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                                <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                            </p>
                                        @else
                                            @if ($campaignProduct->product->offer_price == null)
                                                <p class="wsus__price">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                                </p>
                                            @else
                                                <p class="wsus__price">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignProduct->product->offer_price + $variantPrice) }}
                                                    <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                                </p>
                                            @endif
                                        @endif

                                        <a class="buy_button" onclick="addToBuyNow('{{ $campaignProduct->product->id }}')"
                                            href="javascript:;">{{ __('user.Order Now') }}</a>

                                        <a class="add_cart"
                                            onclick="addToCartMainProduct('{{ $campaignProduct->product->id }}')"
                                            href="javascript:;"><i class="far fa-shopping-basket"></i></a>


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if ($campaignProducts != null)
                    @foreach ($campaignProducts->take($campaignVisibility->qty) as $campaignProduct)
                        <section class="product_popup_modal">
                            <div class="modal fade" id="productModalView-{{ $campaignProduct->product->id }}"
                                tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="far fa-times"></i></button>
                                            <div class="row">
                                                <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                    <div class="wsus__quick_view_img">
                                                        @if ($campaignProduct->product->video_link)
                                                            @php
                                                                $video_id = explode(
                                                                    '=',
                                                                    $campaignProduct->product->video_link,
                                                                );
                                                            @endphp
                                                            <a class="venobox wsus__pro_det_video" data-autoplay="true"
                                                                data-vbtype="video"
                                                                href="https://youtu.be/{{ $video_id[1] }}">
                                                                <i class="fas fa-play"></i>
                                                            </a>
                                                        @endif

                                                        <div class="row modal_slider">
                                                            @foreach ($campaignProduct->product->gallery as $image)
                                                                <div class="col-xl-12">
                                                                    <div class="modal_slider_img">
                                                                        <img src="{{ asset($image->image) }}"
                                                                            alt="product" class="img-fluid w-100">
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                    <div class="wsus__pro_details_text">
                                                        <a class="title"
                                                            href="{{ route('product-detail', $campaignProduct->product->slug) }}">{{ $campaignProduct->product->name }}</a>

                                                        @if ($campaignProduct->product->qty == 0)
                                                            <p class="wsus__stock_area"><span
                                                                    class="in_stock">{{ __('user.Out of Stock') }}</span>
                                                            </p>
                                                        @else
                                                            <p class="wsus__stock_area"><span
                                                                    class="in_stock">{{ __('user.In stock') }}
                                                                    @if ($setting->show_product_qty == 1)
                                                                </span> ({{ $campaignProduct->product->qty }}
                                                                {{ __('user.item') }})
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif


                    @php
                        $reviewQty = $campaignProduct->product->reviews->where('status', 1)->count();
                        $totalReview = $campaignProduct->product->reviews->where('status', 1)->sum('rating');

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
                        $variants = $campaignProduct->product->variants->where('status', 1);
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
                            'product_id' => $campaignProduct->product->id,
                        ])->first();
                        if ($campaign) {
                            $campaign = $campaign->campaign;
                            if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
                                $isCampaign = true;
                            }
                            $campaignOffer = $campaign->offer;
                            $productPrice = $campaignProduct->product->price;
                            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                            $totalPrice = $campaignProduct->product->price;
                            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                        }

                        $totalPrice = $campaignProduct->product->price;
                        if ($campaignProduct->product->offer_price != null) {
                            $offerPrice = $campaignProduct->product->offer_price;
                            $offer = $totalPrice - $offerPrice;
                            $percentage = ($offer * 100) / $totalPrice;
                            $percentage = round($percentage);
                        }

                    @endphp

                    @if ($isCampaign)
                        <h4>{{ $currencySetting->currency_icon }} <span
                                id="mainProductModalPrice-{{ $campaignProduct->product->id }}">{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}</span>
                            <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                        </h4>
                    @else
                        @if ($campaignProduct->product->offer_price == null)
                            <h4>{{ $currencySetting->currency_icon }}<span
                                    id="mainProductModalPrice-{{ $campaignProduct->product->id }}">{{ sprintf('%.2f', $totalPrice + $variantPrice) }}</span>
                            </h4>
                        @else
                            <h4>{{ $currencySetting->currency_icon }}<span
                                    id="mainProductModalPrice-{{ $campaignProduct->product->id }}">{{ sprintf('%.2f', $campaignProduct->product->offer_price + $variantPrice) }}</span>
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
                            if ($campaignProduct->product->offer_price == null) {
                                $productPrice = $totalPrice + $variantPrice;
                            } else {
                                $productPrice = $campaignProduct->product->offer_price + $variantPrice;
                            }
                        }
                    @endphp
                    <form id="productModalFormId-{{ $campaignProduct->product->id }}">
                        <div class="wsus__quentity">
                            <h5>{{ __('user.quantity') }} :</h5>
                            <div class="modal_btn">
                                <button onclick="productModalDecrement('{{ $campaignProduct->product->id }}')"
                                    type="button" class="btn btn-danger btn-sm">-</button>
                                <input id="productModalQty-{{ $campaignProduct->product->id }}" name="quantity" readonly
                                    class="form-control" type="text" min="1" max="100" value="1" />
                                <button
                                    onclick="productModalIncrement('{{ $campaignProduct->product->id }}','{{ $campaignProduct->product->qty }}')"
                                    type="button" class="btn btn-success btn-sm">+</button>
                            </div>
                            <h3 class="d-none">{{ $currencySetting->currency_icon }}<span
                                    id="productModalPrice-{{ $campaignProduct->product->id }}">{{ sprintf('%.2f', $productPrice) }}</span>
                            </h3>

                            <input type="hidden" name="product_id" value="{{ $campaignProduct->product->id }}">
                            <input type="hidden" name="image" value="{{ $campaignProduct->product->thumb_image }}">
                            <input type="hidden" name="slug" value="{{ $campaignProduct->product->slug }}">

                        </div>
                        @php
                            $productVariants = App\Models\ProductVariant::where([
                                'status' => 1,
                                'product_id' => $campaignProduct->product->id,
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
                                                    'product_id' => $campaignProduct->product->id,
                                                ])
                                                ->get();
                                        @endphp
                                        @if ($items->count() != 0)
                                            <div class="col-xl-6 col-sm-6 mb-3">
                                                <h5 class="mb-2">{{ $productVariant->name }}:</h5>

                                                <input type="hidden" name="variants[]"
                                                    value="{{ $productVariant->id }}">
                                                <input type="hidden" name="variantNames[]"
                                                    value="{{ $productVariant->name }}">

                                                <select class="select_2 productModalVariant" name="items[]"
                                                    data-product="{{ $campaignProduct->product->id }}">
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <ul class="wsus__button_area">
                            <li><button type="button"
                                    onclick="addToCartInProductModal('{{ $campaignProduct->product->id }}')"
                                    class="add_cart">{{ __('user.add to cart') }}</button></li>
                            <li><a class="buy_now" href="javascript:;"
                                    onclick="addToBuyNow('{{ $campaignProduct->product->id }}')">{{ __('user.Order Now') }}
                                </a></li>

                        </ul>
                    </form>
                    @if ($campaignProduct->product->sku)
                        <p class="brand_model"><span>{{ __('user.Model') }} :</span>
                            {{ $campaignProduct->product->sku }}</p>
                    @endif
                    @if ($campaignProduct->product->brand)
                        <p class="brand_model"><span>{{ __('user.Brand') }} :</span> <a
                                href="{{ route('product', ['brand' => $campaignProduct->product->brand->slug]) }}">{{ $campaignProduct->product->brand->name }}</a>
                        </p>
                    @endif
                    <p class="brand_model"><span>{{ __('user.Category') }} :</span> <a
                            href="{{ route('product', ['category' => $campaignProduct->product->category->slug]) }}">{{ $campaignProduct->product->category->name }}</a>
                    </p>
                    <div class="wsus__pro_det_share d-none">
                        <h5>{{ __('user.share') }} :</h5>
                        <ul class="d-flex">
                            <li><a class="facebook"
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ route('product-detail', $campaignProduct->product->slug) }}&t={{ $campaignProduct->product->name }}"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a class="twitter"
                                    href="https://twitter.com/share?text={{ $campaignProduct->product->name }}&url={{ route('product-detail', $campaignProduct->product->slug) }}"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li><a class="linkedin"
                                    href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('product-detail', $campaignProduct->product->slug) }}&title={{ $campaignProduct->product->name }}"><i
                                        class="fab fa-linkedin"></i></a></li>
                            <li><a class="pinterest"
                                    href="https://www.pinterest.com/pin/create/button/?description={{ $campaignProduct->product->name }}&media=&url={{ route('product-detail', $campaignProduct->product->slug) }}"><i
                                        class="fab fa-pinterest-p"></i></a></li>
                        </ul>
                    </div>
                @endforeach
    @endif
    </div>
    </section>
    @endif
    {{-- <!--============================
        FLASH SELL END
    ==============================--> --}}


    <!--============================
            MONTHLY TOP PRODUCT START
        ==============================-->
    @php
        $popularCategoryVisible = $visibilities->where('id', 4)->first();
    @endphp
    @if ($popularCategoryVisible->status == 1)
        <section id="wsus__monthly_top">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header for_md">
                            <h3>{{ $popularCategory->title }}</h3>
                            <div class="monthly_top_filter">
                                <button class=" active click_first_cat"
                                    data-filter=".first_cat">{{ $firstCategory ? $firstCategory->name : '' }}</button>
                                <button
                                    data-filter=".second_cat">{{ $secondCategory ? $secondCategory->name : '' }}</button>
                                <button
                                    data-filter=".third_cat">{{ $thirdCategory ? $thirdCategory->name : '' }}</button>
                                <button
                                    data-filter=".fourth_cat">{{ $fourthCategory ? $fourthCategory->name : '' }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="row grid">
                            @foreach ($firstCategoryproducts as $index => $firstCategoryproduct)
                                <div class="col-xl-4 col-md-6  first_cat">
                                    <a class="wsus__hot_deals__single"
                                        href="{{ route('product-detail', $firstCategoryproduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ $firstCategoryproduct->thumb_image }}" alt="bag"
                                                class="img-fluid w-100">
                                        </div>
                                        @php
                                            $reviewQty = $firstCategoryproduct->reviews->where('status', 1)->count();
                                            $totalReview = $firstCategoryproduct->reviews
                                                ->where('status', 1)
                                                ->sum('rating');

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

                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $firstCategoryproduct->short_name }}</h5>

                                            @if ($reviewQty > 0)
                                                <p class="wsus__rating">
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
                                                    <span>({{ $reviewQty }})</span>
                                                </p>
                                            @endif

                                            @if ($reviewQty == 0)
                                                <p class="wsus__rating">
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <span>(0)</span>
                                                </p>
                                            @endif

                                            @php
                                                $variantPrice = 0;
                                                $variants = $firstCategoryproduct->variants->where('status', 1);
                                                if ($variants->count() != 0) {
                                                    foreach ($variants as $variants_key => $variant) {
                                                        if ($variant->variantItems->where('status', 1)->count() != 0) {
                                                            $item = $variant->variantItems
                                                                ->where('is_default', 1)
                                                                ->first();
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
                                                    'product_id' => $firstCategoryproduct->id,
                                                ])->first();
                                                if ($campaign) {
                                                    $campaign = $campaign->campaign;
                                                    if (
                                                        $campaign->start_date <= $today &&
                                                        $today <= $campaign->end_date
                                                    ) {
                                                        $isCampaign = true;
                                                    }
                                                    $campaignOffer = $campaign->offer;
                                                    $productPrice = $firstCategoryproduct->price;
                                                    $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                                    $totalPrice = $firstCategoryproduct->price;
                                                    $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                                }

                                                $totalPrice = $firstCategoryproduct->price;
                                                if ($firstCategoryproduct->offer_price != null) {
                                                    $offerPrice = $firstCategoryproduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp
                                            @if ($isCampaign)
                                                <p class="wsus__tk">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                                    <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                </p>
                                            @else
                                                @if ($firstCategoryproduct->offer_price == null)
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                                    </p>
                                                @else
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $firstCategoryproduct->offer_price + $variantPrice) }}
                                                        <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            @foreach ($secondCategoryproducts as $index => $secondCategoryproduct)
                                <div class="col-xl-4 col-sm-6  second_cat">
                                    <a class="wsus__hot_deals__single"
                                        href="{{ route('product-detail', $secondCategoryproduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ $secondCategoryproduct->thumb_image }}" alt="bag"
                                                class="img-fluid w-100">
                                        </div>
                                        @php
                                            $reviewQty = $secondCategoryproduct->reviews->where('status', 1)->count();
                                            $totalReview = $secondCategoryproduct->reviews
                                                ->where('status', 1)
                                                ->sum('rating');

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

                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $secondCategoryproduct->short_name }}</h5>

                                            @if ($reviewQty > 0)
                                                <p class="wsus__rating">
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
                                                    <span>({{ $reviewQty }})</span>
                                                </p>
                                            @endif

                                            @if ($reviewQty == 0)
                                                <p class="wsus__rating">
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <span>(0)</span>
                                                </p>
                                            @endif

                                            @php
                                                $variantPrice = 0;
                                                $variants = $secondCategoryproduct->variants->where('status', 1);
                                                if ($variants->count() != 0) {
                                                    foreach ($variants as $variants_key => $variant) {
                                                        if ($variant->variantItems->where('status', 1)->count() != 0) {
                                                            $item = $variant->variantItems
                                                                ->where('is_default', 1)
                                                                ->first();
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
                                                    'product_id' => $secondCategoryproduct->id,
                                                ])->first();
                                                if ($campaign) {
                                                    $campaign = $campaign->campaign;
                                                    if (
                                                        $campaign->start_date <= $today &&
                                                        $today <= $campaign->end_date
                                                    ) {
                                                        $isCampaign = true;
                                                    }
                                                    $campaignOffer = $campaign->offer;
                                                    $productPrice = $secondCategoryproduct->price;
                                                    $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                                    $totalPrice = $secondCategoryproduct->price;
                                                    $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                                }

                                                $totalPrice = $secondCategoryproduct->price;
                                                if ($secondCategoryproduct->offer_price != null) {
                                                    $offerPrice = $secondCategoryproduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp
                                            @if ($isCampaign)
                                                <p class="wsus__tk">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                                    <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                </p>
                                            @else
                                                @if ($secondCategoryproduct->offer_price == null)
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                                    </p>
                                                @else
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $secondCategoryproduct->offer_price + $variantPrice) }}
                                                        <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            @foreach ($thirdCategoryproducts as $index => $thirdCategoryproduct)
                                <div class="col-xl-4 col-sm-6  third_cat">
                                    <a class="wsus__hot_deals__single"
                                        href="{{ route('product-detail', $thirdCategoryproduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ $thirdCategoryproduct->thumb_image }}" alt="bag"
                                                class="img-fluid w-100">
                                        </div>
                                        @php
                                            $reviewQty = $thirdCategoryproduct->reviews->where('status', 1)->count();
                                            $totalReview = $thirdCategoryproduct->reviews
                                                ->where('status', 1)
                                                ->sum('rating');

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

                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $thirdCategoryproduct->short_name }}</h5>

                                            @if ($reviewQty > 0)
                                                <p class="wsus__rating">
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
                                                    <span>({{ $reviewQty }})</span>
                                                </p>
                                            @endif

                                            @if ($reviewQty == 0)
                                                <p class="wsus__rating">
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <span>(0)</span>
                                                </p>
                                            @endif

                                            @php
                                                $variantPrice = 0;
                                                $variants = $thirdCategoryproduct->variants->where('status', 1);
                                                if ($variants->count() != 0) {
                                                    foreach ($variants as $variants_key => $variant) {
                                                        if ($variant->variantItems->where('status', 1)->count() != 0) {
                                                            $item = $variant->variantItems
                                                                ->where('is_default', 1)
                                                                ->first();
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
                                                    'product_id' => $thirdCategoryproduct->id,
                                                ])->first();
                                                if ($campaign) {
                                                    $campaign = $campaign->campaign;
                                                    if (
                                                        $campaign->start_date <= $today &&
                                                        $today <= $campaign->end_date
                                                    ) {
                                                        $isCampaign = true;
                                                    }
                                                    $campaignOffer = $campaign->offer;
                                                    $productPrice = $thirdCategoryproduct->price;
                                                    $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                                    $totalPrice = $thirdCategoryproduct->price;
                                                    $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                                }

                                                $totalPrice = $thirdCategoryproduct->price;
                                                if ($thirdCategoryproduct->offer_price != null) {
                                                    $offerPrice = $thirdCategoryproduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp
                                            @if ($isCampaign)
                                                <p class="wsus__tk">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                                    <del>{{ $totalPrice }}</del>
                                                </p>
                                            @else
                                                @if ($thirdCategoryproduct->offer_price == null)
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                                    </p>
                                                @else
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $thirdCategoryproduct->offer_price + $variantPrice) }}
                                                        <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                            @foreach ($fourthCategoryproducts as $fourthCategoryproduct)
                                <div class="col-xl-4 col-sm-6  fourth_cat">
                                    <a class="wsus__hot_deals__single"
                                        href="{{ route('product-detail', $fourthCategoryproduct->slug) }}">
                                        <div class="wsus__hot_deals__single_img">
                                            <img src="{{ $fourthCategoryproduct->thumb_image }}" alt="bag"
                                                class="img-fluid w-100">
                                        </div>
                                        @php
                                            $reviewQty = $fourthCategoryproduct->reviews->where('status', 1)->count();
                                            $totalReview = $fourthCategoryproduct->reviews
                                                ->where('status', 1)
                                                ->sum('rating');

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

                                        <div class="wsus__hot_deals__single_text">
                                            <h5>{{ $fourthCategoryproduct->short_name }}</h5>

                                            @if ($reviewQty > 0)
                                                <p class="wsus__rating">
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
                                                    <span>({{ $reviewQty }})</span>
                                                </p>
                                            @endif

                                            @if ($reviewQty == 0)
                                                <p class="wsus__rating">
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <i class="fal fa-star"></i>
                                                    <span>(0)</span>
                                                </p>
                                            @endif

                                            @php
                                                $variantPrice = 0;
                                                $variants = $fourthCategoryproduct->variants->where('status', 1);
                                                if ($variants->count() != 0) {
                                                    foreach ($variants as $variants_key => $variant) {
                                                        if ($variant->variantItems->where('status', 1)->count() != 0) {
                                                            $item = $variant->variantItems
                                                                ->where('is_default', 1)
                                                                ->first();
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
                                                    'product_id' => $fourthCategoryproduct->id,
                                                ])->first();
                                                if ($campaign) {
                                                    $campaign = $campaign->campaign;
                                                    if (
                                                        $campaign->start_date <= $today &&
                                                        $today <= $campaign->end_date
                                                    ) {
                                                        $isCampaign = true;
                                                    }
                                                    $campaignOffer = $campaign->offer;
                                                    $productPrice = $fourthCategoryproduct->price;
                                                    $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                                    $totalPrice = $fourthCategoryproduct->price;
                                                    $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                                }

                                                $totalPrice = $fourthCategoryproduct->price;
                                                if ($fourthCategoryproduct->offer_price != null) {
                                                    $offerPrice = $fourthCategoryproduct->offer_price;
                                                    $offer = $totalPrice - $offerPrice;
                                                    $percentage = ($offer * 100) / $totalPrice;
                                                    $percentage = round($percentage);
                                                }
                                            @endphp
                                            @if ($isCampaign)
                                                <p class="wsus__tk">
                                                    {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                                    <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                </p>
                                            @else
                                                @if ($fourthCategoryproduct->offer_price == null)
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                                    </p>
                                                @else
                                                    <p class="wsus__tk">
                                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $fourthCategoryproduct->offer_price + $variantPrice) }}
                                                        <del>{{ sprintf('%.2f', $totalPrice) }}</del>
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
                                                               MONTHLY TOP PRODUCT END
                                                            ==============================-->


    <!--============================
                                                                SINGLE BANNER START
                                                            ==============================-->
    @php
        $bannerVisibility = $visibilities->where('id', 5)->first();
    @endphp
    @if ($bannerVisibility->status == 1)
        <section id="wsus__single_banner">
            <div class="container">
                <div class="row">
                    @php
                        $bannerOne = $banners->where('id', 3)->first();
                        $bannerTwo = $banners->where('id', 4)->first();
                    @endphp
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerOne->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerOne->description }}</h6>
                                <h3>{{ $bannerOne->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerOne->link }}">{{ __('user.shop now') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerTwo->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerTwo->description }}</h6>
                                <h3>{{ $bannerTwo->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerTwo->link }}">{{ __('user.shop now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--============================
            SINGLE BANNER END
        ==============================-->


    <!--============================
            HOT DEALS START
        ==============================-->
    <section id="wsus__hot_deals">
        <div class="container">





            @php
                $flashDealVisibility = $visibilities->where('id', 6)->first();
                $productIds = [];
                $productYears = [];
                $productMonths = [];
                $productDays = [];

                foreach ($flashDealProducts as $key => $flashDealProduct) {
                    $productIds[] = $flashDealProduct->id;
                    $productYears[] = date('Y', strtotime($flashDealProduct->flash_deal_date));
                    $productMonths[] = date('m', strtotime($flashDealProduct->flash_deal_date));
                    $productDays[] = date('d', strtotime($flashDealProduct->flash_deal_date));
                }

            @endphp
            <script>
                var productIds = <?= json_encode($productIds) ?>;
                var productYears = <?= json_encode($productYears) ?>;
                var productMonths = <?= json_encode($productMonths) ?>;
                var productDays = <?= json_encode($productDays) ?>;
            </script>
            @if ($flashDealVisibility->status == 1)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header">
                            <h3>{{ __('user.Flash Deal') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row hot_deals_slider">
                    @foreach ($flashDealProducts->take($flashDealVisibility->qty) as $flashDealProduct)
                        <div class="col-xl-6 col-lg-6">
                            <div class="wsus__hot_deals_offer">
                                <div class="wsus__hot_deals_img">
                                    <img src="{{ $flashDealProduct->thumb_image }}" alt="mobile"
                                        class="img-fluid w-100">
                                    <div class="simply-countdown flash-deal-product-{{ $flashDealProduct->id }}"></div>
                                </div>
                                <div class="wsus__hot_deals_text">
                                    <a class="wsus__hot_title"
                                        href="{{ route('product-detail', $flashDealProduct->slug) }}">{{ $flashDealProduct->short_name }}</a>
                                    @php
                                        $reviewQty = $flashDealProduct->reviews->where('status', 1)->count();
                                        $totalReview = $flashDealProduct->reviews->where('status', 1)->sum('rating');
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

                                    @if ($reviewQty > 0)
                                        <p class="wsus__rating">
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
                                        <p class="wsus__rating">
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <i class="fal fa-star"></i>
                                            <span>(0 {{ __('user.review') }})</span>
                                        </p>
                                    @endif

                                    @php
                                        $variantPrice = 0;
                                        $variants = $flashDealProduct->variants->where('status', 1);
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
                                            'product_id' => $flashDealProduct->id,
                                        ])->first();
                                        if ($campaign) {
                                            $campaign = $campaign->campaign;
                                            if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
                                                $isCampaign = true;
                                            }
                                            $campaignOffer = $campaign->offer;
                                            $productPrice = $flashDealProduct->price;
                                            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                                            $totalPrice = $productPrice;
                                            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                                        }

                                        $totalPrice = $flashDealProduct->price;
                                        if ($flashDealProduct->offer_price != null) {
                                            $offerPrice = $flashDealProduct->offer_price;
                                            $offer = $totalPrice - $offerPrice;
                                            $percentage = ($offer * 100) / $totalPrice;
                                            $percentage = round($percentage);
                                        }
                                    @endphp

                                    @if ($isCampaign)
                                        <p class="wsus__hot_deals_proce">
                                            {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                            <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                        </p>
                                    @else
                                        @if ($flashDealProduct->offer_price == null)
                                            <p class="wsus__hot_deals_proce">
                                                {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                            </p>
                                        @else
                                            <p class="wsus__hot_deals_proce">
                                                {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $flashDealProduct->offer_price + $variantPrice) }}
                                                <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                            </p>
                                        @endif
                                    @endif

                                    <P class="wsus__details">
                                        {{ $flashDealProduct->short_description }}
                                    </P>
                                    <ul>
                                        <li><a class="buy_button" onclick="addToBuyNow('{{ $flashDealProduct->id }}')"
                                                href="javascript:;">{{ __('user.Order Now') }} </a></li>
                                        <li><a class="add_cart"
                                                onclick="addToCartMainProduct('{{ $flashDealProduct->id }}')"
                                                href="javascript:;"><i class="far fa-shopping-basket"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @foreach ($flashDealProducts->take($flashDealVisibility->qty) as $flashDealProduct)
                    <section class="product_popup_modal">
                        <div class="modal fade" id="productModalView-{{ $flashDealProduct->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"><i class="far fa-times"></i></button>
                                        <div class="row">
                                            <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                                                <div class="wsus__quick_view_img">
                                                    @if ($flashDealProduct->video_link)
                                                        @php
                                                            $video_id = explode('=', $flashDealProduct->video_link);
                                                        @endphp
                                                        <a class="venobox wsus__pro_det_video" data-autoplay="true"
                                                            data-vbtype="video"
                                                            href="https://youtu.be/{{ $video_id[1] }}">
                                                            <i class="fas fa-play"></i>
                                                        </a>
                                                    @endif

                                                    <div class="row modal_slider">
                                                        @foreach ($flashDealProduct->gallery as $image)
                                                            <div class="col-xl-12">
                                                                <div class="modal_slider_img">
                                                                    <img src="{{ asset($image->image) }}" alt="product"
                                                                        class="img-fluid w-100">
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                                                <div class="wsus__pro_details_text">
                                                    <a class="title"
                                                        href="{{ route('product-detail', $flashDealProduct->slug) }}">{{ $flashDealProduct->name }}</a>

                                                    @if ($flashDealProduct->qty == 0)
                                                        <p class="wsus__stock_area"><span
                                                                class="in_stock">{{ __('user.Out of Stock') }}</span></p>
                                                    @else
                                                        <p class="wsus__stock_area"><span
                                                                class="in_stock">{{ __('user.In stock') }}
                                                                @if ($setting->show_product_qty == 1)
                                                            </span> ({{ $flashDealProduct->qty }} {{ __('user.item') }})
                                                    @endif
                                                    </p>
                @endif

                @php
                    $variantPrice = $flashDealProduct->variants
                        ->where('status', 1)
                        ->flatMap(fn($variant) => $variant->variantItems->where('status', 1)->where('is_default', 1))
                        ->sum('price');

                    $isCampaign = false;
                    $campaignOfferPrice = 0;
                    $totalPrice = $flashDealProduct->price;
                    $today = now();

                    $campaignProduct = App\Models\CampaignProduct::where([
                        'status' => 1,
                        'product_id' => $flashDealProduct->id,
                    ])->first();

                    if (
                        $campaignProduct &&
                        $campaignProduct->campaign->start_date <= $today &&
                        $today <= $campaignProduct->campaign->end_date
                    ) {
                        $isCampaign = true;
                        $campaignOffer = $campaignProduct->campaign->offer;
                        $campaignOfferPrice = $totalPrice - ($campaignOffer / 100) * $totalPrice;
                    }

                    if ($flashDealProduct->offer_price !== null) {
                        $offerPrice = $flashDealProduct->offer_price;
                        $offer = $totalPrice - $offerPrice;
                        $percentage = round(($offer * 100) / $totalPrice);
                    }
                @endphp


                @if ($isCampaign)
                    <h4>{{ $currencySetting->currency_icon }}
                        <span id="mainProductModalPrice-{{ $flashDealProduct->id }}">
                            {{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                        </span>
                        <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                    </h4>
                @else
                    @if (is_null($flashDealProduct->offer_price))
                        <h4>{{ $currencySetting->currency_icon }}
                            <span id="mainProductModalPrice-{{ $flashDealProduct->id }}">
                                {{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                            </span>
                        </h4>
                    @else
                        <h4>{{ $currencySetting->currency_icon }}
                            <span id="mainProductModalPrice-{{ $flashDealProduct->id }}">
                                {{ sprintf('%.2f', $flashDealProduct->offer_price + $variantPrice) }}
                            </span>
                            <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                        </h4>
                    @endif
                @endif

                <p class="review">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $flashDealProduct->avgReview)
                            <i class="fas fa-star"></i>
                        @elseif ($i - $flashDealProduct->avgReview == 0.5)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                    <span>({{ $flashDealProduct->totalReviews() }} {{ __('user.review') }})</span>
                </p>


                @php
                    $productPrice = $isCampaign
                        ? $campaignOfferPrice + $variantPrice
                        : ($flashDealProduct->offer_price ?? $totalPrice) + $variantPrice;
                    $productVariants = App\Models\ProductVariant::where([
                        'status' => 1,
                        'product_id' => $flashDealProduct->id,
                    ])->get();
                @endphp

                <form id="productModalFormId-{{ $flashDealProduct->id }}">
                    <div class="wsus__quentity">
                        <h5>{{ __('user.quantity') }} :</h5>
                        <div class="modal_btn">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="productModalDecrement('{{ $flashDealProduct->id }}')">-</button>
                            <input id="productModalQty-{{ $flashDealProduct->id }}" name="quantity"
                                class="form-control" type="text" min="1" max="100" value="1"
                                readonly />
                            <button type="button" class="btn btn-success btn-sm"
                                onclick="productModalIncrement('{{ $flashDealProduct->id }}', '{{ $flashDealProduct->qty }}')">+</button>
                        </div>
                        <h3 class="d-none">{{ $currencySetting->currency_icon }}<span
                                id="productModalPrice-{{ $flashDealProduct->id }}">{{ sprintf('%.2f', $productPrice) }}</span>
                        </h3>

                        <input type="hidden" name="product_id" value="{{ $flashDealProduct->id }}">
                        <input type="hidden" name="image" value="{{ $flashDealProduct->thumb_image }}">
                        <input type="hidden" name="slug" value="{{ $flashDealProduct->slug }}">
                    </div>

                    @if ($productVariants->count())
                        <div class="wsus__selectbox">
                            <div class="row">
                                @foreach ($productVariants as $productVariant)
                                    @php
                                        $items = App\Models\ProductVariantItem::orderBy('is_default', 'desc')
                                            ->where([
                                                'product_variant_id' => $productVariant->id,
                                                'product_id' => $flashDealProduct->id,
                                            ])
                                            ->get();
                                    @endphp
                                    @if ($items->count())
                                        <div class="col-xl-6 col-sm-6 mb-3">
                                            <h5 class="mb-2">{{ $productVariant->name }}:</h5>
                                            <input type="hidden" name="variants[]" value="{{ $productVariant->id }}">
                                            <input type="hidden" name="variantNames[]"
                                                value="{{ $productVariant->name }}">
                                            <select class="select_2 productModalVariant" name="items[]"
                                                data-product="{{ $flashDealProduct->id }}">
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
                        <li><button type="button" class="add_cart"
                                onclick="addToCartInProductModal('{{ $flashDealProduct->id }}')">{{ __('user.add to cart') }}</button>
                        </li>
                        <li><a class="buy_now" href="javascript:;"
                                onclick="addToBuyNow('{{ $flashDealProduct->id }}')">{{ __('user.Order Now') }}</a>
                        </li>
                    </ul>
                </form>

                @if ($flashDealProduct->sku)
                    <p class="brand_model"><span>{{ __('user.Model') }} :</span> {{ $flashDealProduct->sku }}</p>
                @endif

                @if ($flashDealProduct->brand)
                    <p class="brand_model"><span>{{ __('user.Brand') }} :</span> <a
                            href="{{ route('product', ['brand' => $flashDealProduct->brand->slug]) }}">{{ $flashDealProduct->brand->name }}</a>
                    </p>
                @endif
                <p class="brand_model"><span>{{ __('user.Category') }} :</span> <a
                        href="{{ route('product', ['category' => $flashDealProduct->category->slug]) }}">{{ $flashDealProduct->category->name }}</a>
                </p>
                <div class="wsus__pro_det_share d-none">
                    <h5>{{ __('user.share') }} :</h5>
                    <ul class="d-flex">
                        @php
                            $shareUrl = route('product-detail', $flashDealProduct->slug);
                            $shareName = urlencode($flashDealProduct->name);
                        @endphp
                        <li>
                            <a class="facebook"
                                href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}&t={{ $shareName }}">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a class="twitter"
                                href="https://twitter.com/share?text={{ $shareName }}&url={{ $shareUrl }}">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a class="linkedin"
                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareName }}">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a class="pinterest"
                                href="https://www.pinterest.com/pin/create/button/?description={{ $shareName }}&media=&url={{ $shareUrl }}">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                        </li>
                    </ul>
                </div>

        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
    </section>
    @endforeach
    @endif

    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="wsus__section_header d-flex justify-content-between">
                <h3>{{ __('user.Featured Products') }}</h3>
                <a href="{{ route('product') }}">{{ __('See All') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($featuredProducts as $featuredProduct)
            @include('components.product-cart', ['product' => $featuredProduct])
        @endforeach
    </div>
    </div>
    </section>


    <section id="wsus__hot_deals">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__section_header d-flex justify-content-between">
                        <h3>{{ __('user.Best Product') }}</h3>
                        <a href="{{ route('product') }}">{{ __('See All') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($bestProducts as $bestProduct)
                    @include('components.product-cart', ['product' => $bestProduct])
                @endforeach
            </div>
        </div>
    </section>

    <section>
        <div class="container">

            @php
                $productHighlightVisibility = $visibilities->where('id', 7)->first();
            @endphp
            @if ($productHighlightVisibility->status == 1)
                <div class="row">
                    <div class="wsus__hot_large_item">
                        @foreach ($featuredProducts as $product)
                            @include('product-modal')
                        @endforeach

                        @foreach ($bestProducts as $bestProduct)
                            @include('product-modal', ['product' => $bestProduct])
                        @endforeach

                        @foreach ($topProducts as $topProduct)
                            @include('product-modal', ['product' => $topProduct])
                        @endforeach

                        @foreach ($newProducts as $newProduct)
                            @include('product-modal', ['product' => $newProduct])
                        @endforeach
                    </div>
                </div>
            @endif


            @php
                $bannerVisiblity = $visibilities->where('id', 8)->first();
            @endphp
            @if ($bannerVisiblity->status == 1)
                <section id="wsus__single_banner" class="pt-3">
                    <div class="">
                        <div class="row">
                            @php
                                $bannerOne = $banners->where('id', 5)->first();
                                $bannerTwo = $banners->where('id', 6)->first();
                            @endphp
                            <div class="col-xl-12 col-lg-12">
                                <div class="wsus__single_banner_content banner_fluid">
                                    <div class="wsus__single_banner_img">
                                        <img src="{{ $bannerOne->image }}" alt="banner" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_banner_text">
                                        <h6>{{ $bannerOne->description }}</h6>
                                        <h3>{{ $bannerOne->title }}</h3>
                                        <a class="shop_btn"
                                            href="{{ $bannerOne->link }}">{{ __('user.shop now') }}</a>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-6 col-lg-6">
                                <div class="wsus__single_banner_content">
                                    <div class="wsus__single_banner_img">
                                        <img src="{{ $bannerTwo->image }}" alt="banner" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_banner_text">
                                        <h6>{{ $bannerTwo->description }}</h6>
                                        <h3>{{ $bannerTwo->title }}</h3>
                                        <a class="shop_btn"
                                            href="{{ $bannerTwo->link }}">{{ __('user.shop now') }}</a>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </section>

    {{-- <!--============================
        HOT DEALS END
==============================--> --}}

    {{-- Top Products --}}
    <section id="wsus__hot_deals">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__section_header d-flex justify-content-between">
                        <h3>{{ __('user.Top Rated') }}</h3>
                        <a href="{{ route('product') }}">{{ __('See All') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($topProducts as $topProduct)
                    @include('components.product-cart', ['product' => $topProduct])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Top Product End --}}


    {{-- New Product --}}
    <section id="wsus__hot_deals">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__section_header d-flex justify-content-between">
                        <h3>{{ __('user.New Arrival') }}</h3>
                        <a href="{{ route('product') }}">{{ __('See All') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($newProducts as $newProduct)
                    @include('components.product-cart', ['product' => $newProduct])
                @endforeach
            </div>
        </div>
    </section>

    {{-- end New Product --}}
    {{-- <!--============================
        WEEKLY BEST ITEM START
    ==============================--> --}}
    @php
        $threeColVisible = $visibilities->where('id', 9)->first();
    @endphp
    @if ($threeColVisible->status == 1)
        @include('product-modal')
    @endif
    {{-- <!--============================
        WEEKLY BEST ITEM END
    ==============================--> --}}

    {{-- <!--============================
        LARGE BANNER  START
    ==============================--> --}}

    @php
        $bannerVisibility = $visibilities->where('id', 10)->first();
    @endphp
    @if ($bannerVisibility->status == 1)
        <section id="wsus__single_banner">
            <div class="container">
                <div class="row">
                    @php
                        $bannerOne = $banners->where('id', 7)->first();
                        $bannerTwo = $banners->where('id', 8)->first();
                    @endphp
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerOne->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerOne->description }}</h6>
                                <h3>{{ $bannerOne->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerOne->link }}">{{ __('user.shop now') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__single_banner_content">
                            <div class="wsus__single_banner_img">
                                <img src="{{ asset($bannerTwo->image) }}" alt="banner" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_banner_text">
                                <h6>{{ $bannerTwo->description }}</h6>
                                <h3>{{ $bannerTwo->title }}</h3>
                                <a class="shop_btn" href="{{ $bannerTwo->link }}">{{ __('user.shop now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--============================
        LARGE BANNER  END
    ==============================--> --}}

    @include('components.order-modal')

    <script>
        $(document).ready(function() {

            $('.buy_button').on('click', function() {
                $("#orderModal").modal('show')
            })
            // addToBuyNow

            $('.wsus__single_slider').on('click', function() {
                const link = $(this).data('link')
                window.location.href = link;
            })
        })
    </script>
@endsection
