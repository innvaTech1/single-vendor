@foreach ($newProducts as $newProduct)
                    @php
                        $reviewQty = $newProduct->reviews->where('status', 1)->count();
                        $totalReview = $newProduct->reviews->where('status', 1)->sum('rating');

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
                        $variants = $newProduct->variants->where('status', 1);
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
                            'product_id' => $newProduct->id,
                        ])->first();
                        if ($campaign) {
                            $campaign = $campaign->campaign;
                            if ($campaign->start_date <= $today && $today <= $campaign->end_date) {
                                $isCampaign = true;
                            }
                            $campaignOffer = $campaign->offer;
                            $productPrice = $newProduct->price;
                            $campaignOfferPrice = ($campaignOffer / 100) * $productPrice;
                            $totalPrice = $productPrice;
                            $campaignOfferPrice = $totalPrice - $campaignOfferPrice;
                        }

                        $totalPrice = $newProduct->price;
                        if ($newProduct->offer_price != null) {
                            $offerPrice = $newProduct->offer_price;
                            $offer = $totalPrice - $offerPrice;
                            $percentage = ($offer * 100) / $totalPrice;
                            $percentage = round($percentage);
                        }
                    @endphp
                    <div class="col-xl-3 col-sm-6 col-md-6 col-lg-4 _new">
                        <div class="wsus__product_item wsus__after">
                            @if ($isCampaign)
                                <span class="wsus__minus">-{{ $campaignOffer }}%</span>
                            @else
                                @if ($newProduct->offer_price != null)
                                    <span class="wsus__minus">-{{ $percentage }}%</span>
                                @endif
                            @endif
                            <a class="wsus__pro_link" href="{{ route('product-detail', $newProduct->slug) }}">
                                <img src="{{ asset($newProduct->thumb_image) }}" alt="product"
                                    class="img-fluid w-100 img_1" />
                                <img src="{{ asset($newProduct->thumb_image) }}" alt="product"
                                    class="img-fluid w-100 img_2" />
                            </a>

                            <ul class="wsus__single_pro_icon">

                            </ul>
                            <div class="wsus__product_details">
                                <a class="wsus__category"
                                    href="{{ route('product', ['category' => $newProduct->category->slug]) }}">{{ $newProduct->category->name }}
                                </a>

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
                                    href="{{ route('product-detail', $newProduct->slug) }}">{{ $newProduct->short_name }}</a>
                                @if ($isCampaign)
                                    <p class="wsus__price">
                                        {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $campaignOfferPrice + $variantPrice) }}
                                        <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                    </p>
                                @else
                                    @if ($newProduct->offer_price == null)
                                        <p class="wsus__price">
                                            {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice + $variantPrice) }}
                                        </p>
                                    @else
                                        <p class="wsus__price">
                                            {{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $newProduct->offer_price + $variantPrice) }}
                                            <del>{{ $currencySetting->currency_icon }}{{ sprintf('%.2f', $totalPrice) }}</del>
                                        </p>
                                    @endif
                                @endif

                                {{-- onclick="addToBuyNow('{{ $newProduct->id }}')" --}}
                                <a class="buy_button"
                                    href="javascript:;">{{ __('user.Order Now') }} </a>

                                <a class="add_cart" onclick="addToCartMainProduct('{{ $newProduct->id }}')"
                                    href="javascript:;"><i class="far fa-shopping-basket"></i></a>

                            </div>
                        </div>
                    </div>
                @endforeach
