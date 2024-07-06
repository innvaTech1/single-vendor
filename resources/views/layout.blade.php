@php
    $setting = App\Models\Setting::first();
    $announcementModal = App\Models\AnnouncementModal::first();
    $productCategories = App\Models\Category::where(['status' => 1])->get();
    $megaMenuCategories = App\Models\MegaMenuCategory::orderBy('serial', 'asc')->where('status', 1)->get();
    $megaMenuBanner = App\Models\BannerImage::find(1);
    $modalProducts = App\Models\Product::all();
    $currencySetting = App\Models\Setting::first();
    $menus = App\Models\MenuVisibility::select('status', 'id')->get();
    $customPages = App\Models\CustomPage::where('status', 1)->get();
    $googleAnalytic = App\Models\GoogleAnalytic::first();
    $facebookPixel = App\Models\FacebookPixel::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('title')
    @yield('meta')

    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('user/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.nice-number.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/add_row_custon.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/mobile_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.exzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/multiple-image-video.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/ranger_style.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.classycountdown.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

    @if ($setting->text_direction == 'rtl')
        <link rel="stylesheet" href="{{ asset('user/css/rtl.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">


    <link rel="stylesheet" href="{{ asset('user/css/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('user/css/dev.css') }}">

    <!--jquery library js-->
    <script src="{{ asset('user/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    @if ($googleAnalytic->status == 1)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytic->analytic_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $googleAnalytic->analytic_id }}');
        </script>
    @endif

    @if ($facebookPixel->status == 1)
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $facebookPixel->app_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $facebookPixel->app_id }}&ev=PageView&noscript=1" /></noscript>
    @endif

    <script>
        var end_year = '';
        var end_month = '';
        var end_date = '';
        var capmaign_time = '';
        var campaign_end_year = ''
        var campaign_end_month = ''
        var campaign_end_date = ''
        var campaign_hour = ''
        var campaign_min = ''
        var campaign_sec = ''
        var productIds = [];
        var productYears = [];
        var productMonths = [];
        var productDays = [];
    </script>

    @include('theme_style_css')
</head>

<body>

    <!--============================
        TOP BAR START
    ==============================-->
    <section id="wsus__topbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-sm-5 col-md-6 col-lg-6 d-none d-lg-block">
                    <ul class="wsus__topbar_left">
                        @if ($menus->where('id', 22)->first()->status == 1)
                            <li><a href="callto:{{ $setting->topbar_phone }}"><i class="fal fa-phone-square-alt"></i>
                                    {{ $setting->topbar_phone }}</a></li>
                        @endif
                        <li><a href="mailto:{{ $setting->topbar_email }}"><i class="fal fa-envelope"></i>
                                {{ $setting->topbar_email }}</a></li>
                    </ul>
                </div>
                <div class="col-xl-6 col-sm-12 col-md-8 col-lg-6">
                    <ul class="wsus__topbar_right">
                        @if ($menus->where('id', 9)->first()->status == 1)
                            <li><a href="{{ route('contact-us') }}"><i class="fal fa-address-card"></i>
                                    {{ __('user.Contact Us') }}</a></li>
                        @endif
                        @if ($menus->where('id', 17)->first()->status == 1)
                            <li><a href="{{ route('user.dashboard') }}"><i class="fal fa-user-circle"></i>
                                    {{ __('user.My Account') }}</a></li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        TOP BAR END
    ==============================-->


    <!--============================
        HEADER START
    ==============================-->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-2 d-lg-none">
                    <div class="wsus__mobile_menu_area">
                        <span class="wsus__mobile_menu_icon"><i class="fal fa-bars"></i></span>
                    </div>
                </div>
                <div class="col-xl-2 col-7 col-lg-2">
                    <div class="wsus_logo_area">
                        <a class="wsus__header_logo" href="{{ route('home') }}">
                            <img src="{{ asset($setting->logo) }}" alt="logo" class="img-fluid w-100">
                        </a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-5 d-none d-lg-block">
                    @if ($menus->where('id', 25)->first()->status == 1)
                        <div class="wsus__search">
                            <form action="{{ route('product') }}">
                                <div class="wsus__category_search">
                                    <select class="select_2" name="category">
                                        <option value="">{{ __('user.All Category') }}</option>
                                        @if (request()->has('category'))
                                            @foreach ($productCategories as $productCategory)
                                                <option
                                                    {{ request()->get('category') == $productCategory->slug ? 'selected' : '' }}
                                                    value="{{ $productCategory->slug }}">{{ $productCategory->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($productCategories as $productCategory)
                                                <option value="{{ $productCategory->slug }}">
                                                    {{ $productCategory->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <input type="text" placeholder="{{ __('user.Search...') }}" name="search"
                                    value="{{ request()->has('search') ? request()->get('search') : '' }}">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="col-xl-4 col-3 col-lg-5">
                    <div class="wsus__call_icon_area">
                        @if ($menus->where('id', 23)->first()->status == 1)
                            <div class="wsus__call_area">
                                <div class="wsus__call">
                                    <i class="far fa-phone-alt"></i>
                                </div>
                                <div class="wsus__call_text">
                                    <p>{{ __('user.Call Us Now') }} :</p>
                                    <a href="callto:+{{ $setting->menu_phone }}">{{ $setting->menu_phone }}</a>
                                </div>
                            </div>
                        @endif
                        <ul class="wsus__icon_area">


                            @if ($menus->where('id', 19)->first()->status == 1)
                                <li><a class="wsus__cart_icon" href="javascript:;"><i
                                            class="fal fa-shopping-bag"></i><span
                                            id="cartQty">{{ Cart::instance('default')->count() }}</span></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="wsus__mini_cart">
            <h4>{{ __('user.SHOPPING CART') }} <span class="wsus_close_mini_cart"><i class="far fa-times"></i></span>
            </h4>
            <div id="load_sidebar_cart">
                @if (Cart::instance('default')->count() == 0)
                    <h5 class="text-danger text-center">{{ 'Your Cart is empty' }}</h5>
                @else
                    <ul>
                        @php
                            $sidebarCartSubTotal = 0;
                            $sidebar_cart_contents = Cart::instance('default')->content();
                        @endphp
                        @foreach ($sidebar_cart_contents as $sidebar_cart_content)
                            <li>
                                <div class="wsus__cart_img">
                                    <a href="#"><img src="{{ asset($sidebar_cart_content->options->image) }}"
                                            alt="product" class="img-fluid w-100"></a>
                                    <a class="wsis__del_icon"
                                        onclick="sidebarCartItemRemove('{{ $sidebar_cart_content->rowId }}')"
                                        href="javascript:;"><i class="fas fa-minus-circle"></i></a>
                                </div>
                                <div class="wsus__cart_text">
                                    <a class="wsus__cart_title"
                                        href="{{ route('product-detail', $sidebar_cart_content->options->slug) }}">{{ $sidebar_cart_content->name }}</a>
                                    <p><span>{{ $sidebar_cart_content->qty }} x</span>
                                        {{ $currencySetting->currency_icon }}{{ $sidebar_cart_content->price }}</p>
                                </div>
                            </li>

                            @php
                                $productPrice = $sidebar_cart_content->price;
                                $total = $productPrice * $sidebar_cart_content->qty;
                                $sidebarCartSubTotal += $total;
                            @endphp
                        @endforeach




                    </ul>
                    <h5>{{ __('user.Sub Total') }}
                        <span>{{ $currencySetting->currency_icon }}{{ $sidebarCartSubTotal }}</span>
                    </h5>
                    <div class="wsus__minicart_btn_area">
                        <a class="common_btn" href="{{ route('cart') }}">কার্ট পেইজ</a>
                        <a class="common_btn" href="{{ route('user.checkout.billing-address') }}">চেক আউট করুন </a>
                    </div>
                @endif
            </div>
        </div>

    </header>
    <!--============================
        HEADER END
    ==============================-->


    <!--============================
        MAIN MENU START
    ==============================-->
    <nav class="wsus__main_menu d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <ul class="wsus__menu_item">
                        
                        <li>
                            <a class="menu_shop" href="{{ route('product') }}">Shop</a>
                        </li>
                        @foreach ($productCategories as $index => $productCategory)
                            @if ($index == 6)
                            @break
                        @endif
                        <li><a
                                href="{{ route('product', ['category' => $productCategory->slug]) }}">{{ $productCategory->name }}</a>
                        </li>
                    @endforeach
                    @if ($productCategories->count() > 6)
                        <li class="wsus__relative_li"><a href="javascript:;">{{ __('More') }} <i
                                    class="fas fa-caret-down"></i></a>
                            <ul class="wsus__menu_droapdown">
                                @foreach ($productCategories as $index => $productCategory)
                                    @if ($index > 5)
                                        <li><a
                                                href="{{ route('product', ['category' => $productCategory->slug]) }}">{{ $productCategory->name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
                <ul class="wsus__menu_item wsus__menu_item_right ms-auto">
                    @if ($menus->where('id', 15)->first()->status == 1)
                        <li><a href="{{ route('track-order') }}">{{ __('user.Track Order') }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
<!--============================
MAIN MENU END
==============================-->


<!--============================
MOBILE MENU START
==============================-->
<section id="wsus__mobile_menu">
    <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
    <ul class="wsus__mobile_menu_header_icon d-inline-flex">
        @if ($menus->where('id', 21)->first()->status == 1)
            <li><a href="{{ route('user.wishlist') }}"><i class="far fa-heart"></i>
                    @auth
                        @php
                            $user = Auth::guard('web')->user();
                            $wishlist = App\Models\Wishlist::where('user_id', $user->id)->count();
                        @endphp
                        <span id="mobileMenuwishlistQty">{{ $wishlist }}</span>
                    @endauth
                </a></li>
        @endif

        @if ($menus->where('id', 20)->first()->status == 1)
            <li><a href="{{ route('compare') }}"><i class="far fa-random"></i><span
                        id="mobileMenuCompareQty">{{ Cart::instance('compare')->count() }}</span></a></li>
        @endif
    </ul>
    @if ($menus->where('id', 25)->first()->status == 1)
        <form action="{{ route('product') }}">
            <input type="text" placeholder="{{ __('user.Search') }}" name="search">
            <button type="submit"><i class="far fa-search"></i></button>
        </form>
    @endif

    @php
        $categoryFalse = $menus->where('id', 24)->first()->status == 1 ? false : true;
    @endphp
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if ($menus->where('id', 24)->first()->status == 1)
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-home" role="tab" aria-controls="pills-home"
                    aria-selected="true">{{ __('user.Categories') }}</button>
            </li> --}}
        @endif
    </ul>
    <div class="tab-content" id="pills-tabContent">
        @if ($menus->where('id', 24)->first()->status == 1)
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                aria-labelledby="pills-home-tab">
                <div class="wsus__mobile_menu_main_menu">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <ul class="wsus_mobile_menu_category">
                            <li>
                                <a href="{{ route('product') }}">Shop</a>
                            </li>
                            @foreach ($productCategories as $productCategory)
                                @if ($productCategory->subCategories->count() == 0)
                                    <li><a href="{{ route('product', ['category' => $productCategory->slug]) }}">
                                            {{ $productCategory->name }}</a></li>
                                @else
                                    {{-- <li>
                                        <a href="{{ route('product') }}">Shop</a>
                                    </li> --}}
                                    <li><a href="{{ route('product', ['category' => $productCategory->slug]) }}"
                                            class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseThreew-{{ $productCategory->id }}"
                                            aria-expanded="false"
                                            aria-controls="flush-collapseThreew-{{ $productCategory->id }}"><i
                                                class="{{ $productCategory->icon }}"></i>
                                            {{ $productCategory->name }}</a>
                                        <div id="flush-collapseThreew-{{ $productCategory->id }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <ul>
                                                    @foreach ($productCategory->subCategories as $subCategory)
                                                        <li>
                                                            <a href="{{ route('product', ['sub_category' => $subCategory->slug]) }}">{{ $subCategory->name }}
                                                                </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!--============================
MOBILE MENU END
==============================-->


<!--==========================
   POP UP START
===========================-->

@if ($announcementModal->status)
    <section id="wsus__pop_up">
        <div class="wsus__pop_up_center" style="background-image:url({{ asset($announcementModal->image) }})">
            <div class="wsus__pop_up_text">
                <span id="cross"><i class="fas fa-times"></i></span>
                <h5>{{ $announcementModal->title }}</h5>
                <p>{{ $announcementModal->description }}</p>
                <form id="modalSubscribeForm">
                    @csrf
                    <input type="email" name="email" placeholder="{{ __('user.Your Email') }}"
                        class="news_input">
                    <button type="submit" class="common_btn" id="modalSubscribeBtn"><i
                            id="modal-subscribe-spinner"
                            class="loading-icon fa fa-spin fa-spinner mr-3 d-none"></i>
                        {{ __('user.Subscribe') }}</button>
                    <div class="wsus__pop_up_check_box"></div>
            </div>
            </form>
            <div class="form-check">
                <input type="hidden" id="announcement_expired_date"
                    value="{{ $announcementModal->expired_date }}">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
                <label class="form-check-label" for="flexCheckDefault11">
                    {{ $announcementModal->footer_text }}
                </label>
            </div>
        </div>
        </div>
    </section>

    <script>
        let isannouncementModal = sessionStorage.getItem("announcementModal");
        let expirationDate = sessionStorage.getItem("announcementModalExpiration");
        if (isannouncementModal && expirationDate) {
            let today = new Date();
            today = today.toISOString().slice(0, 10)
            if (today < expirationDate) {
                $("#wsus__pop_up").addClass("d-none");
            }
        }
    </script>
@endif
<!--==========================
   POP UP END
===========================-->



@yield('public-content')

@php
    $setting = App\Models\Setting::first();
    $footer = App\Models\Footer::first();
    $links = App\Models\FooterSocialLink::all();
    $footerLinks = App\Models\FooterLink::all();
@endphp




<!--============================
FOOTER PART START
==============================-->
<footer>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-3 col-sm-7 col-md-6 col-lg-3">
                <div class="wsus__footer_content">
                    <img src="{{ asset($setting->logo) }}" alt="logo">
                    <a class="action" href="callto:{{ $footer->phone }}"><i class="fas fa-phone-alt"></i>
                        {{ $footer->phone }}</a>
                    <a class="action" href="mailto:{{ $footer->email }}"><i class="far fa-envelope"></i>
                        {{ $footer->email }}</a>
                    <p><i class="fal fa-map-marker-alt"></i> {{ $footer->address }}</p>
                    <ul class="wsus__footer_social">
                        @foreach ($links as $link)
                            <li><a class="facebook" href="{{ $link->link }}"><i
                                        class="{{ $link->icon }}"></i></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-2 col-sm-5 col-md-6 col-lg-2">
                <div class="wsus__footer_content">

                    <h5>{{ $footer->first_column }}</h5>
                    <ul class="wsus__footer_menu">
                        @foreach ($footerLinks->where('column', 1) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i>
                                    {{ $footerLink->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-2 col-sm-7 col-md-6 col-lg-2">
                <div class="wsus__footer_content">
                    <h5>{{ $footer->second_column }}</h5>
                    <ul class="wsus__footer_menu">
                        @foreach ($footerLinks->where('column', 2) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i>
                                    {{ $footerLink->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xl-2 col-sm-5 col-md-6 col-lg-3">
                <div class="wsus__footer_content">
                    <h5>{{ $footer->third_column }}</h5>
                    <ul class="wsus__footer_menu">
                        @foreach ($footerLinks->where('column', 3) as $footerLink)
                            <li><a href="{{ $footerLink->link }}"><i class="fas fa-caret-right"></i>
                                    {{ $footerLink->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="wsus__footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__copyright">
                        <p>{{ $footer->copyright }}</p>
                        <p>{{ $footer->image_title }} :
                            <img src="{{ asset($footer->payment_image) }}" alt="card" class="img-fluid">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--============================
FOOTER PART END
==============================-->


<!--============================
SCROLL BUTTON START
==============================-->
<div class="wsus__scroll_btn">
    <i class="fas fa-chevron-up"></i>
</div>
<!--============================
SCROLL BUTTON  END
==============================-->

@php
    $isAuth = false;
    if (Auth::check()) {
        $isAuth = true;
    }
    $shop_page = App\Models\ShopPage::first();
    $max_val = $shop_page->filter_price_range;
    $currencySetting = App\Models\Setting::first();
    $currency_icon = $currencySetting->currency_icon;
    $tawk_setting = App\Models\TawkChat::first();
    $cookie_consent = App\Models\CookieConsent::first();
    $setting = App\Models\Setting::first();

@endphp
<script>
    let filter_max_val = "{{ $max_val }}";
    let currency_icon = "{{ $currency_icon }}";
    let themeColor = "{{ $setting->theme_one }}";
</script>

@if ($tawk_setting->status == 1)
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $tawk_setting->chat_link }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif

<!--bootstrap js-->
<script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
<!--font-awesome js-->
<script src="{{ asset('user/js/Font-Awesome.js') }}"></script>
<!--select2 js-->
<script src="{{ asset('user/js/select2.min.js') }}"></script>
<!--slick slider js-->
<script src="{{ asset('user/js/slick.min.js') }}"></script>
<!--simplyCountdown js-->
<script src="{{ asset('user/js/simplyCountdown.js') }}"></script>
<!--product zoomer js-->
<script src="{{ asset('user/js/jquery.exzoom.js') }}"></script>
<!--nice-number js-->
<script src="{{ asset('user/js/jquery.nice-number.min.js') }}"></script>
<!--counter js-->
<script src="{{ asset('user/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.countup.min.js') }}"></script>
<script src="{{ asset('global/custom-hook.js') }}"></script>
<!--calender js-->
<script src="{{ asset('user/js/jquery.calendar.js') }}"></script>
<!--add row js-->
<script src="{{ asset('user/js/add_row_custon.js') }}"></script>
<!--multiple-image-video js-->
<script src="{{ asset('user/js/multiple-image-video.js') }}"></script>
<!--sticky sidebar js-->
<script src="{{ asset('user/js/sticky_sidebar.js') }}"></script>
<!--price ranger js-->
<script src="{{ asset('user/js/ranger_jquery-ui.min.js') }}"></script>
<script src="{{ asset('user/js/ranger_slider.js') }}"></script>
<!--isotope js-->
<script src="{{ asset('user/js/isotope.pkgd.min.js') }}"></script>
<!--venobox js-->
<script src="{{ asset('user/js/venobox.min.js') }}"></script>
<!--classycountdown js-->
<script src="{{ asset('user/js/jquery.classycountdown.js') }}"></script>

<!--main/custom js-->
<script src="{{ asset('user/js/main.js') }}"></script>


<script src="{{ asset('toastr/toastr.min.js') }}"></script>

<script>
    @if (Session::has('messege'))
        var type = "{{ Session::get('alert-type', 'info') }}"
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('messege') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('messege') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('messege') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('messege') }}");
                break;
        }
    @endif
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif



<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            $(".click_first_cat").click();
            $(".click_featured_product").click();

            $("#modalSubscribeForm").on('submit', function(e) {
                e.preventDefault();

                $("#modal-subscribe-spinner").removeClass('d-none')
                $("#modalSubscribeBtn").addClass('after_subscribe')
                $("#modalSubscribeBtn").attr('disabled', true);

                $.ajax({
                    type: 'POST',
                    data: $('#modalSubscribeForm').serialize(),
                    url: "{{ route('subscribe-request') }}",
                    success: function(response) {
                        if (response.status == 1) {
                            toastr.success(response.message);
                            $("#modal-subscribe-spinner").addClass('d-none')
                            $("#modalSubscribeBtn").removeClass('after_subscribe')
                            $("#modalSubscribeBtn").attr('disabled', false);
                            let expiredDate = $("#announcement_expired_date").val();
                            expiredDate = expiredDate * 1;
                            let date = new Date();
                            date.setDate(date.getDate() + expiredDate);
                            let nextDate = date.toISOString().slice(0, 10);
                            sessionStorage.setItem("announcementModal", "yes");
                            sessionStorage.setItem("announcementModalExpiration",
                                nextDate);
                            $("#cross").click();

                        }

                        if (response.status == 0) {
                            toastr.error(response.message);
                            $("#modal-subscribe-spinner").addClass('d-none')
                            $("#modalSubscribeBtn").removeClass('after_subscribe')
                            $("#modalSubscribeBtn").attr('disabled', false);
                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong');
                        $("#modal-subscribe-spinner").addClass('d-none')
                        $("#modalSubscribeBtn").removeClass('after_subscribe')
                        $("#modalSubscribeBtn").attr('disabled', false);
                    }
                });
            })

            $("#flexCheckDefault11").on("click", function() {
                let expiredDate = $("#announcement_expired_date").val();
                expiredDate = expiredDate * 1;
                let date = new Date();
                date.setDate(date.getDate() + expiredDate);
                let nextDate = date.toISOString().slice(0, 10);
                sessionStorage.setItem("announcementModal", "yes");
                sessionStorage.setItem("announcementModalExpiration", nextDate);
                $("#cross").click();
            })

            $("#subscriberForm").on('submit', function(e) {
                e.preventDefault();

                $("#subscribe-spinner").removeClass('d-none')
                $("#SubscribeBtn").addClass('after_subscribe')
                $("#SubscribeBtn").attr('disabled', true);

                $.ajax({
                    type: 'POST',
                    data: $('#subscriberForm').serialize(),
                    url: "{{ route('subscribe-request') }}",
                    success: function(response) {
                        if (response.status == 1) {
                            toastr.success(response.message);
                            $("#subscribe-spinner").addClass('d-none')
                            $("#SubscribeBtn").removeClass('after_subscribe')
                            $("#SubscribeBtn").attr('disabled', false);
                            $("#subscriberForm").trigger("reset");
                        }

                        if (response.status == 0) {
                            toastr.error(response.message);
                            $("#subscribe-spinner").addClass('d-none')
                            $("#SubscribeBtn").removeClass('after_subscribe')
                            $("#SubscribeBtn").attr('disabled', false);
                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong');
                        $("#subscribe-spinner").addClass('d-none')
                        $("#SubscribeBtn").removeClass('after_subscribe')
                        $("#SubscribeBtn").attr('disabled', false);
                    }
                });
            })

            $(".productModalVariant").on("change", function() {
                let id = $(this).data("product");
                calculateProductModalPrice(id);

            })



        });
    })(jQuery);

    function addToWishlist(id) {

        let isAuth = "{{ $isAuth }}";
        if (!isAuth) {
            toastr.error("{{ __('user.Please Login First') }}");
            return;
        }
        $.ajax({
            type: 'get',
            url: "{{ url('user/add-to-wishlist/') }}" + "/" + id,
            success: function(response) {
                if (response.status == 1) {
                    toastr.success(response.message)

                    let currentQty = $("#wishlistQty").text();
                    currentQty = currentQty * 1 + 1 * 1;
                    $("#wishlistQty").text(currentQty);

                    let mobileMenuCurrentQty = $("#mobileMenuwishlistQty").text();
                    mobileMenuCurrentQty = mobileMenuCurrentQty * 1 + 1 * 1;
                    $("#mobileMenuwishlistQty").text(mobileMenuCurrentQty);
                }
                if (response.status == 0) {
                    toastr.error(response.message)
                }
            },
            error: function(response) {
                alert('error');
            }
        });
    }


    function calculateProductModalPrice(productId) {
        $.ajax({
            type: 'get',
            data: $('#productModalFormId-' + productId).serialize(),
            url: "{{ route('calculate-product-price') }}",
            success: function(response) {
                let qty = $("#productModalQty-" + productId).val();
                let price = response.productPrice * qty;
                price = price.toFixed(2);
                $("#productModalPrice-" + productId).text(price);
                $("#mainProductModalPrice-" + productId).text(price);
            },
            error: function(err) {
                alert('error')
            }
        });

    }

    function productModalIncrement(id, current_stock) {
        let qty = $("#productModalQty-" + id).val();
        if (parseInt(qty) < parseInt(current_stock)) {
            qty = qty * 1 + 1 * 1;
            $("#productModalQty-" + id).val(qty);
            calculateProductModalPrice(id)
        }

    }

    function productModalDecrement(id) {
        let qty = $("#productModalQty-" + id).val();
        if (qty > 1) {
            qty = qty - 1;
            $("#productModalQty-" + id).val(qty);
            calculateProductModalPrice(id)
        }

    }

    function addToCartMainProduct(productId) {
        addToCartInProductModal(productId);
    }


    function addToCartInProductModal(productId) {
        $.ajax({
            type: 'get',
            data: $('#productModalFormId-' + productId).serialize(),
            url: "{{ route('add-to-cart') }}",
            success: function(response) {
                if (response.status == 0) {
                    toastr.error(response.message)
                }
                if (response.status == 1) {
                    toastr.success(response.message)
                    $.ajax({
                        type: 'get',
                        url: "{{ route('load-sidebar-cart') }}",
                        success: function(response) {
                            $("#load_sidebar_cart").html(response)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('get-cart-qty') }}",
                                success: function(response) {
                                    console.log(response);
                                    $("#cartQty").text(response.qty);
                                    $("#productModalView-" + productId).modal(
                                        'hide');
                                },
                            });
                        },
                    });
                }
            },
            error: function(response) {

            }
        });
    }

    function addToBuyNow(id) {
        $.ajax({
            type: 'get',
            data: $('#productModalFormId-' + id).serialize(),
            url: "{{ route('add-to-cart') }}",
            success: function(response) {
                if (response.status == 0) {
                    toastr.error(response.message)
                }
                if (response.status == 1) {
                    window.location.href = "{{ route('user.checkout.billing-address') }}";
                    toastr.success(response.message)
                    $.ajax({
                        type: 'get',
                        url: "{{ route('load-sidebar-cart') }}",
                        success: function(response) {
                            $("#load_sidebar_cart").html(response)
                            $.ajax({
                                type: 'get',
                                url: "{{ route('get-cart-qty') }}",
                                success: function(response) {
                                    $("#cartQty").text(response.qty);
                                },
                            });
                        },
                    });
                }
            },
            error: function(response) {

            }
        });
    }

    function sidebarCartItemRemove(id) {
        $.ajax({
            type: 'get',
            url: "{{ url('sidebar-cart-item-remove') }}" + "/" + id,
            success: function(response) {
                toastr.success(response.message)
                let ifCheckoutPage =
                    "{{ Route::is('user.checkout.payment') || Route::is('user.checkout.checkout') || Route::is('user.checkout.billing-address') ? 'yes' : 'no' }}";
                if (ifCheckoutPage == 'yes') {
                    window.location.reload();
                }
                $.ajax({
                    type: 'get',
                    url: "{{ route('load-sidebar-cart') }}",
                    success: function(response) {
                        $("#load_sidebar_cart").html(response)
                        $.ajax({
                            type: 'get',
                            url: "{{ route('get-cart-qty') }}",
                            success: function(response) {
                                $("#cartQty").text(response.qty);
                            },
                        });
                    },
                });

                $.ajax({
                    type: 'get',
                    url: "{{ route('load-main-cart') }}",
                    success: function(response) {
                        $("#CartResponse").html(response)
                    },
                });
            },
        });

    }

    function addToCompare(id) {
        $.ajax({
            type: 'get',
            url: "{{ url('add-to-compare') }}" + "/" + id,
            success: function(response) {
                if (response.status == 1) {
                    toastr.success(response.message)
                    let currentQty = $("#compareQty").text();
                    currentQty = currentQty * 1 + 1 * 1;
                    $("#compareQty").text(currentQty);

                    let mobileMenuCurrentQty = $("#mobileMenuCompareQty").text();
                    mobileMenuCurrentQty = mobileMenuCurrentQty * 1 + 1 * 1;
                    $("#mobileMenuCompareQty").text(mobileMenuCurrentQty);

                } else {
                    toastr.error(response.message)
                }
            },
        });

    }
</script>


@stack('js')
</body>

</html>
