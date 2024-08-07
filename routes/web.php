<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\AddressCotroller;
use App\Http\Controllers\User\PaypalController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\WEB\Admin\FaqController;
use App\Http\Controllers\WEB\Admin\BlogController;
use App\Http\Controllers\WEB\Admin\CityController;
use App\Http\Controllers\WEB\Admin\AdminController;
use App\Http\Controllers\WEB\Admin\OrderController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\WEB\Admin\CouponController;
use App\Http\Controllers\WEB\Admin\FooterController;
use App\Http\Controllers\WEB\Admin\SliderController;
use App\Http\Controllers\WEB\Admin\AboutUsController;
use App\Http\Controllers\WEB\Admin\ContentController;
use App\Http\Controllers\WEB\Admin\CountryController;
use App\Http\Controllers\WEB\Admin\ProductController;
use App\Http\Controllers\WEB\Admin\ServiceController;
use App\Http\Controllers\WEB\Admin\SettingController;
use App\Http\Controllers\WEB\Admin\CustomerController;
use App\Http\Controllers\WEB\Admin\HomePageController;
use App\Http\Controllers\WEB\Admin\LanguageController;
use App\Http\Controllers\WEB\Admin\MegaMenuController;
use App\Http\Controllers\WEB\Admin\DashboardController;
use App\Http\Controllers\WEB\Admin\ErrorPageController;
use App\Http\Controllers\WEB\Admin\FlashSaleController;
use App\Http\Controllers\WEB\Admin\InventoryController;

use App\Http\Controllers\WEB\Admin\BreadcrumbController;
use App\Http\Controllers\WEB\Admin\CustomPageController;
use App\Http\Controllers\WEB\Admin\FooterLinkController;
use App\Http\Controllers\WEB\Admin\SubscriberController;
use App\Http\Controllers\WEB\Admin\BlogCommentController;
use App\Http\Controllers\WEB\Admin\ContactPageController;
use App\Http\Controllers\WEB\Admin\PopularBlogController;
use App\Http\Controllers\WEB\Admin\TestimonialController;
use App\Http\Controllers\WEB\Admin\AdminProfileController;
use App\Http\Controllers\WEB\Admin\BlogCategoryController;
use App\Http\Controllers\WEB\Admin\CountryStateController;
use App\Http\Controllers\WEB\Admin\NotificationController;
use App\Http\Controllers\WEB\Admin\ProductBrandController;
use App\Http\Controllers\WEB\Admin\AdvertisementController;
use App\Http\Controllers\WEB\Admin\EmailTemplateController;
use App\Http\Controllers\WEB\Admin\PaymentMethodController;
use App\Http\Controllers\WEB\Admin\PrivacyPolicyController;
use App\Http\Controllers\WEB\Admin\ProductReportController;
use App\Http\Controllers\WEB\Admin\ProductReviewController;
use App\Http\Controllers\WEB\Admin\ContactMessageController;
use App\Http\Controllers\WEB\Admin\MenuVisibilityController;
use App\Http\Controllers\WEB\Admin\ProductGalleryController;
use App\Http\Controllers\WEB\Admin\ProductVariantController;

use App\Http\Controllers\WEB\Admin\CurrencyController;

use App\Http\Controllers\WEB\Admin\ShippingMethodController;
use App\Http\Controllers\WEB\Admin\WithdrawMethodController;
use App\Http\Controllers\WEB\Admin\Auth\AdminLoginController;
use App\Http\Controllers\WEB\Admin\ProductCategoryController;
use App\Http\Controllers\WEB\Admin\FooterSocialLinkController;
use App\Http\Controllers\WEB\Admin\SpecificationKeyController;
use App\Http\Controllers\WEB\Admin\TermsAndConditionController;

use App\Http\Controllers\WEB\Admin\EmailConfigurationController;
use App\Http\Controllers\WEB\Admin\HomepageVisibilityController;
use App\Http\Controllers\WEB\Admin\ProductSubCategoryController;
use App\Http\Controllers\WEB\Admin\ProductVariantItemController;
use App\Http\Controllers\WEB\Admin\MegaMenuSubCategoryController;



use App\Http\Controllers\WEB\Admin\ProductChildCategoryController;
use App\Http\Controllers\WEB\Admin\PosController;
use App\Http\Controllers\WEB\Admin\Auth\AdminForgotPasswordController;

use Illuminate\Support\Facades\Broadcast;

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::get('clear-database', [SettingController::class, 'clearDatabase']);

Route::get('/mpdf', [HomeController::class, 'mpdf'])->name('mpdf');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::post('/send-contact-message', [HomeController::class, 'sendContactMessage'])->name('send-contact-message');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog-detail/{slug}', [HomeController::class, 'blogDetail'])->name('blog-detail');
Route::get('/blog-by-category/{slug}', [HomeController::class, 'blogByCategory'])->name('blog-by-category');
Route::get('/search-blog', [HomeController::class, 'blogSearch'])->name('search-blog');
Route::post('/blog-comment', [HomeController::class, 'blogComment'])->name('blog-comment');
Route::get('/campaign', [HomeController::class, 'campaign'])->name('campaign');
Route::get('/campaign-detail/{slug}', [HomeController::class, 'campaignDetail'])->name('campaign-detail');
Route::get('/brand', [HomeController::class, 'brand'])->name('brand');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->name('track-order');
Route::get('/track-order-response/{id}', [HomeController::class, 'trackOrderResponse'])->name('track-order-response');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/page/{slug}', [HomeController::class, 'customPage'])->name('page');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndCondition'])->name('terms-and-conditions');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/product', [HomeController::class, 'product'])->name('product');
Route::get('/search-product', [HomeController::class, 'searchProduct'])->name('search-product');
Route::get('/product-detail/{slug}', [HomeController::class, 'productDetail'])->name('product-detail');
Route::get('/compare', [HomeController::class, 'compare'])->name('compare');
Route::get('/add-to-compare/{id}', [HomeController::class, 'addToCompare'])->name('add-to-compare');
Route::get('/remove-compare/{id}', [HomeController::class, 'removeCompare'])->name('remove-compare');
Route::get('/flash-deal', [HomeController::class, 'flashDeal'])->name('flash-deal');
Route::post('subscribe-request', [HomeController::class, 'subscribeRequest'])->name('subscribe-request');
Route::get('subscriber-verification/{token}', [HomeController::class, 'subscriberVerifcation'])->name('subscriber-verification');
Route::get('/cart-test', [CartController::class, 'calculateWholsaleDiscount'])->name('cart-test');
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('/add-to-buy', [CartController::class, 'addToBuy'])->name('add-to-buy');
Route::get('/cart-clear', [CartController::class, 'cartClear'])->name('cart-clear');
Route::get('/cart-item-remove/{id}', [CartController::class, 'cartItemRemove'])->name('cart-item-remove');
Route::get('/cart-item-increment/{id}', [CartController::class, 'cartItemIncrement'])->name('cart-item-increment');
Route::get('/cart-item-decrement/{id}', [CartController::class, 'cartItemDecrement'])->name('cart-item-decrement');
Route::post('/cart-update', [CartController::class, 'cartUpdate'])->name('cart-update');
Route::post('/update-checkout-cart-item', [CartController::class, 'update_checkout_page_cart'])->name('update-checkout-cart-item');

Route::get('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('/calculate-product-price', [CartController::class, 'calculateProductPrice'])->name('calculate-product-price');
Route::get('/calculate-wholesale-price', [CartController::class, 'calculateWholsaleDiscount'])->name('calculate-wholesale-price');
Route::get('/sidebar-cart-item-remove/{id}', [CartController::class, 'sidebarcartItemRemove'])->name('sidebar-cart-item-remove');
Route::get('/load-sidebar-cart', [CartController::class, 'loadSidebarCart'])->name('load-sidebar-cart');
Route::get('/get-cart-qty', [CartController::class, 'calculateCartQty'])->name('get-cart-qty');
Route::get('/load-main-cart', [CartController::class, 'loadMainCart'])->name('load-main-cart');

Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login-google');
Route::get('/callback/google', [LoginController::class, 'googleCallBack'])->name('callback-google');

Route::get('login/facebook', [LoginController::class, 'redirectToFacebook'])->name('login-facebook');
Route::get('/callback/facebook', [LoginController::class, 'facebookCallBack'])->name('callback-facebook');


Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
Route::post('/store-login', [LoginController::class, 'storeLogin'])->name('store-login');
Route::post('/store-register', [RegisterController::class, 'storeRegister'])->name('store-register');
Route::get('/user-verification/{token}', [RegisterController::class, 'userVerification'])->name('user-verification');
Route::get('/forget-password', [LoginController::class, 'forgetPage'])->name('forget-password');
Route::post('/send-forget-password', [LoginController::class, 'sendForgetPassword'])->name('send-forget-password');
Route::get('/reset-password/{token}', [LoginController::class, 'resetPasswordPage'])->name('reset-password');
Route::post('/store-reset-password/{token}', [LoginController::class, 'storeResetPasswordPage'])->name('store-reset-password');
Route::get('/user/logout', [LoginController::class, 'userLogout'])->name('user.logout');


Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
    Route::get('dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('order', [UserProfileController::class, 'order'])->name('order');
    Route::get('pending-order', [UserProfileController::class, 'pendingOrder'])->name('pending-order');
    Route::get('complete-order', [UserProfileController::class, 'completeOrder'])->name('complete-order');
    Route::get('declined-order', [UserProfileController::class, 'declinedOrder'])->name('declined-order');
    Route::get('order-show/{id}', [UserProfileController::class, 'orderShow'])->name('order-show');
    Route::get('review', [UserProfileController::class, 'review'])->name('review');
    Route::get('my-profile', [UserProfileController::class, 'myProfile'])->name('my-profile');
    Route::post('update-profile', [UserProfileController::class, 'updateProfile'])->name('update-profile');
    Route::post('update-order-info/{id}', [UserProfileController::class, 'update_order_info'])->name('update-order-info');

    Route::get('address', [UserProfileController::class, 'address'])->name('address');
    Route::get('change-password', [UserProfileController::class, 'changePassword'])->name('change-password');
    Route::post('update-password', [UserProfileController::class, 'updatePassword'])->name('update-password');
    Route::get('billing-address', [UserProfileController::class, 'editBillingAddress'])->name('billing-address');
    Route::post('update-billing-address', [UserProfileController::class, 'updateBillingAddress'])->name('update-billing-address');
    Route::get('shipping-address', [UserProfileController::class, 'editShippingAddress'])->name('shipping-address');
    Route::post('update-shipping-address', [UserProfileController::class, 'updateShippingAddress'])->name('update-shipping-address');
    Route::get('wishlist', [UserProfileController::class, 'wishlist'])->name('wishlist');
    Route::get('add-to-wishlist/{id}', [UserProfileController::class, 'addToWishlist'])->name('add-to-wishlist');
    Route::get('remove-wishlist/{id}', [UserProfileController::class, 'removeWishlist'])->name('remove-wishlist');
    Route::post('product-report', [UserProfileController::class, 'storeProductReport'])->name('product-report');
    Route::post('store-product-review', [UserProfileController::class, 'storeProductReview'])->name('store-product-review');
    Route::post('update-review/{id}', [UserProfileController::class, 'updateReview'])->name('update-review');

    Route::get('message', [MessageController::class, 'index'])->name('message');
    Route::get('load-chat-box/{id}', [MessageController::class, 'loadChatBox'])->name('load-chat-box');
    Route::get('load-new-message/{id}', [MessageController::class, 'loadNewMessage'])->name('load-new-message');
    Route::get('send-message', [MessageController::class, 'sendMessage'])->name('send-message');

    Route::get('/checkout', [CheckoutController::class, 'checkoutBillingAddress'])->name('checkout');
    Route::group(['as' => 'checkout.', 'prefix' => 'checkout'], function () {
        Route::get('/billing-address', [CheckoutController::class, 'checkoutBillingAddress'])->name('billing-address');
        Route::post('/update-billing-address', [CheckoutController::class, 'placeOrder'])->name('update-billing-address');
        Route::post('/update-shipping-address', [CheckoutController::class, 'updateShippingBillingAddress'])->name('update-shipping-address');
        Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');

        Route::post('/cash-on-delivery', [PaymentController::class, 'cashOnDelivery'])->name('cash-on-delivery');

        Route::post('/pay-with-bank', [PaymentController::class, 'payWithBank'])->name('pay-with-bank');
    });

    Route::get('state-by-country/{id}', [UserProfileController::class, 'stateByCountry'])->name('state-by-country');
    Route::get('city-by-state/{id}', [HomeController::class, 'cityByState'])->name('city-by-state');
});




Route::group(['middleware' => ['XSS']], function () {
    // start admin routes
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

        // start auth route
        Route::get('login', [AdminLoginController::class, 'adminLoginPage'])->name('login');
        Route::post('login', [AdminLoginController::class, 'storeLogin'])->name('login');
        Route::post('logout', [AdminLoginController::class, 'adminLogout'])->name('logout');
        Route::get('forget-password', [AdminForgotPasswordController::class, 'forgetPassword'])->name('forget-password');
        Route::post('send-forget-password', [AdminForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('password-store/{token}', [AdminForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');
        // end auth route

        Route::get('/', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('profile-update', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::resource('product-category', ProductCategoryController::class);
        Route::put('product-category-status/{id}', [ProductCategoryController::class, 'changeStatus'])->name('product.category.status');

        Route::resource('product-sub-category', ProductSubCategoryController::class);
        Route::put('product-sub-category-status/{id}', [ProductSubCategoryController::class, 'changeStatus'])->name('product.sub.category.status');

        Route::resource('product-child-category', ProductChildCategoryController::class);
        Route::put('product-child-category-status/{id}', [ProductChildCategoryController::class, 'changeStatus'])->name('product.child.category.status');
        Route::get('subcategory-by-category/{id}', [ProductChildCategoryController::class, 'getSubcategoryByCategory'])->name('subcategory-by-category');
        Route::get('childcategory-by-subcategory/{id}', [ProductChildCategoryController::class, 'getChildcategoryBySubCategory'])->name('childcategory-by-subcategory');

        Route::resource('product-brand', ProductBrandController::class);
        Route::put('product-brand-status/{id}', [ProductBrandController::class, 'changeStatus'])->name('product.brand.status');

        Route::resource('specification-key', SpecificationKeyController::class);
        Route::put('specification-key-status/{id}', [SpecificationKeyController::class, 'changeStatus'])->name('specification-key.status');

        Route::resource('testimonial', TestimonialController::class);
        Route::put('testimonial-status/{id}', [TestimonialController::class, 'changeStatus'])->name('testimonial.status');

        Route::resource('product', ProductController::class);
        Route::get('create-product-info', [ProductController::class, 'create'])->name('create-product-info');
        Route::put('product-status/{id}', [ProductController::class, 'changeStatus'])->name('product.status');
        Route::put('product-approved/{id}', [ProductController::class, 'productApproved'])->name('product-approved');
        Route::put('removed-product-exist-specification/{id}', [ProductController::class, 'removedProductExistSpecification'])->name('removed-product-exist-specification');

        Route::get('stockout-product', [ProductController::class, 'stockoutProduct'])->name('stockout-product');

        Route::get('product-import-page', [ProductController::class, 'product_import_page'])->name('product-import-page');
        Route::get('product-export', [ProductController::class, 'product_export'])->name('product-export');
        Route::get('product-demo-export', [ProductController::class, 'demo_product_export'])->name('product-demo-export');
        Route::post('product-import', [ProductController::class, 'product_import'])->name('product-import');



        Route::get('product-variant/{id}', [ProductVariantController::class, 'index'])->name('product-variant');
        Route::get('create-product-variant/{id}', [ProductVariantController::class, 'create'])->name('create-product-variant');
        Route::post('store-product-variant', [ProductVariantController::class, 'store'])->name('store-product-variant');
        Route::get('get-product-variant/{id}', [ProductVariantController::class, 'show'])->name('get-product-variant');
        Route::put('update-product-variant/{id}', [ProductVariantController::class, 'update'])->name('update-product-variant');
        Route::delete('delete-product-variant/{id}', [ProductVariantController::class, 'destroy'])->name('delete-product-variant');
        Route::put('product-variant-status/{id}', [ProductVariantController::class, 'changeStatus'])->name('product-variant.status');

        Route::get('product-variant-item', [ProductVariantItemController::class, 'index'])->name('product-variant-item');
        Route::get('create-product-variant-item/{id}', [ProductVariantItemController::class, 'create'])->name('create-product-variant-item');
        Route::post('store-product-variant-item', [ProductVariantItemController::class, 'store'])->name('store-product-variant-item');
        Route::get('edit-product-variant-item/{id}', [ProductVariantItemController::class, 'edit'])->name('edit-product-variant-item');
        Route::get('get-product-variant-item/{id}', [ProductVariantItemController::class, 'show'])->name('egetdit-product-variant-item');

        Route::put('update-product-variant-item/{id}', [ProductVariantItemController::class, 'update'])->name('update-product-variant-item');
        Route::delete('delete-product-variant-item/{id}', [ProductVariantItemController::class, 'destroy'])->name('delete-product-variant-item');
        Route::put('product-variant-item-status/{id}', [ProductVariantItemController::class, 'changeStatus'])->name('product-variant-item.status');


        Route::get('product-gallery/{id}', [ProductGalleryController::class, 'index'])->name('product-gallery');
        Route::post('store-product-gallery', [ProductGalleryController::class, 'store'])->name('store-product-gallery');
        Route::delete('delete-product-image/{id}', [ProductGalleryController::class, 'destroy'])->name('delete-product-image');
        Route::put('product-gallery-status/{id}', [ProductGalleryController::class, 'changeStatus'])->name('product-gallery.status');

        Route::resource('service', ServiceController::class);
        Route::put('service-status/{id}', [ServiceController::class, 'changeStatus'])->name('service.status');

        Route::resource('about-us', AboutUsController::class);
        Route::resource('contact-us', ContactPageController::class);

        Route::resource('custom-page', CustomPageController::class);
        Route::put('custom-page-status/{id}', [CustomPageController::class, 'changeStatus'])->name('custom-page.status');

        Route::resource('terms-and-condition', TermsAndConditionController::class);
        Route::resource('privacy-policy', PrivacyPolicyController::class);

        Route::resource('blog-category', BlogCategoryController::class);
        Route::put('blog-category-status/{id}', [BlogCategoryController::class, 'changeStatus'])->name('blog.category.status');

        Route::resource('blog', BlogController::class);
        Route::put('blog-status/{id}', [BlogController::class, 'changeStatus'])->name('blog.status');

        Route::resource('popular-blog', PopularBlogController::class);
        Route::put('popular-blog-status/{id}', [PopularBlogController::class, 'changeStatus'])->name('popular-blog.status');

        Route::resource('blog-comment', BlogCommentController::class);
        Route::put('blog-comment-status/{id}', [BlogCommentController::class, 'changeStatus'])->name('blog-comment.status');



        Route::put('update-database', [SettingController::class, 'update_database'])->name('update-database');

        Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber');
        Route::delete('delete-subscriber/{id}', [SubscriberController::class, 'destroy'])->name('delete-subscriber');
        Route::post('specification-subscriber-email/{id}', [SubscriberController::class, 'specificationSubscriberEmail'])->name('specification-subscriber-email');
        Route::post('each-subscriber-email', [SubscriberController::class, 'eachSubscriberEmail'])->name('each-subscriber-email');

        Route::get('contact-message', [ContactMessageController::class, 'index'])->name('contact-message');
        Route::get('show-contact-message/{id}', [ContactMessageController::class, 'show'])->name('show-contact-message');
        Route::delete('delete-contact-message/{id}', [ContactMessageController::class, 'destroy'])->name('delete-contact-message');
        Route::put('enable-save-contact-message', [ContactMessageController::class, 'handleSaveContactMessage'])->name('enable-save-contact-message');

        Route::get('email-configuration', [EmailConfigurationController::class, 'index'])->name('email-configuration');
        Route::put('update-email-configuraion', [EmailConfigurationController::class, 'update'])->name('update-email-configuraion');

        Route::get('email-template', [EmailTemplateController::class, 'index'])->name('email-template');
        Route::get('edit-email-template/{id}', [EmailTemplateController::class, 'edit'])->name('edit-email-template');
        Route::put('update-email-template/{id}', [EmailTemplateController::class, 'update'])->name('update-email-template');

        Route::get('general-setting', [SettingController::class, 'index'])->name('general-setting');
        Route::put('update-general-setting', [SettingController::class, 'updateGeneralSetting'])->name('update-general-setting');

        Route::put('update-theme-color', [SettingController::class, 'updateThemeColor'])->name('update-theme-color');

        Route::put('update-logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');
        Route::put('update-cookie-consent', [SettingController::class, 'updateCookieConset'])->name('update-cookie-consent');
        Route::put('update-google-recaptcha', [SettingController::class, 'updateGoogleRecaptcha'])->name('update-google-recaptcha');
        Route::put('update-facebook-comment', [SettingController::class, 'updateFacebookComment'])->name('update-facebook-comment');
        Route::put('update-tawk-chat', [SettingController::class, 'updateTawkChat'])->name('update-tawk-chat');
        Route::put('update-google-analytic', [SettingController::class, 'updateGoogleAnalytic'])->name('update-google-analytic');
        Route::put('update-custom-pagination', [SettingController::class, 'updateCustomPagination'])->name('update-custom-pagination');
        Route::put('update-social-login', [SettingController::class, 'updateSocialLogin'])->name('update-social-login');
        Route::put('update-facebook-pixel', [SettingController::class, 'updateFacebookPixel'])->name('update-facebook-pixel');
        Route::put('update-pusher', [SettingController::class, 'updatePusher'])->name('update-pusher');


        Route::resource('admin', AdminController::class);
        Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin-status');

        Route::resource('faq', FaqController::class);
        Route::put('faq-status/{id}', [FaqController::class, 'changeStatus'])->name('faq-status');


        Route::get('product-review', [ProductReviewController::class, 'index'])->name('product-review');
        Route::put('product-review-status/{id}', [ProductReviewController::class, 'changeStatus'])->name('product-review-status');
        Route::get('show-product-review/{id}', [ProductReviewController::class, 'show'])->name('show-product-review');
        Route::delete('delete-product-review/{id}', [ProductReviewController::class, 'destroy'])->name('delete-product-review');

        Route::get('product-report', [ProductReportController::class, 'index'])->name('product-report');
        Route::get('show-product-report/{id}', [ProductReportController::class, 'show'])->name('show-product-report');
        Route::delete('delete-product-report/{id}', [ProductReportController::class, 'destroy'])->name('delete-product-report');
        Route::put('de-active-product/{id}', [ProductReportController::class, 'deactiveProduct'])->name('de-active-product');

        Route::get('customer-list', [CustomerController::class, 'index'])->name('customer-list');
        Route::get('customer-show/{id}', [CustomerController::class, 'show'])->name('customer-show');
        Route::put('customer-status/{id}', [CustomerController::class, 'changeStatus'])->name('customer-status');
        Route::delete('customer-delete/{id}', [CustomerController::class, 'destroy'])->name('customer-delete');
        Route::get('pending-customer-list', [CustomerController::class, 'pendingCustomerList'])->name('pending-customer-list');
        Route::get('send-email-to-all-customer', [CustomerController::class, 'sendEmailToAllUser'])->name('send-email-to-all-customer');
        Route::post('send-mail-to-all-user', [CustomerController::class, 'sendMailToAllUser'])->name('send-mail-to-all-user');
        Route::post('send-mail-to-single-user/{id}', [CustomerController::class, 'sendMailToSingleUser'])->name('send-mail-to-single-user');


        Route::resource('error-page', ErrorPageController::class);

        Route::get('announcement', [ContentController::class, 'announcementModal'])->name('announcement');
        Route::post('announcement-update', [ContentController::class, 'announcementModalUpdate'])->name('announcement-update');

        Route::get('topbar-contact', [ContentController::class, 'headerPhoneNumber'])->name('topbar-contact');
        Route::put('update-topbar-contact', [ContentController::class, 'updateHeaderPhoneNumber'])->name('update-topbar-contact');

        Route::get('product-quantity-progressbar', [ContentController::class, 'productProgressbar'])->name('product-quantity-progressbar');
        Route::put('update-product-quantity-progressbar', [ContentController::class, 'updateProductProgressbar'])->name('update-product-quantity-progressbar');

        Route::get('default-avatar', [ContentController::class, 'defaultAvatar'])->name('default-avatar');
        Route::post('update-default-avatar', [ContentController::class, 'updateDefaultAvatar'])->name('update-default-avatar');


        Route::get('subscription-banner', [ContentController::class, 'subscriptionBanner'])->name('subscription-banner');
        Route::post('update-subscription-banner', [ContentController::class, 'updatesubscriptionBanner'])->name('update-subscription-banner');




        Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');
        Route::put('update-flash-sale', [FlashSaleController::class, 'update'])->name('update-flash-sale');
        Route::get('flash-sale-product', [FlashSaleController::class, 'flash_sale_product'])->name('flash-sale-product');
        Route::post('store-flash-sale-product', [FlashSaleController::class, 'store'])->name('store-flash-sale-product');
        Route::put('flash-sale-product-status/{id}', [FlashSaleController::class, 'changeStatus'])->name('flash-sale-product-status');
        Route::delete('delete-flash-sale-product/{id}', [FlashSaleController::class, 'destroy'])->name('delete-flash-sale-product');


        Route::get('advertisement', [AdvertisementController::class, 'index'])->name('advertisement');

        Route::post('mega-menu-banner-update', [AdvertisementController::class, 'megaMenuBannerUpdate'])->name('mega-menu-banner-update');


        Route::post('slider-banner-one', [AdvertisementController::class, 'updateSliderBannerOne'])->name('slider-banner-one');

        Route::post('slider-banner-two', [AdvertisementController::class, 'updateSliderBannerTwo'])->name('slider-banner-two');

        Route::post('popular-category-sidebar', [AdvertisementController::class, 'updatePopularCategorySidebar'])->name('popular-category-sidebar');


        Route::post('homepage-two-col-first-banner', [AdvertisementController::class, 'homepageTwoColFirstBanner'])->name('homepage-two-col-first-banner');


        Route::post('homepage-two-col-second-banner', [AdvertisementController::class, 'homepageTwoColSecondBanner'])->name('homepage-two-col-second-banner');


        Route::post('homepage-single-first-banner', [AdvertisementController::class, 'homepageSinleFirstBanner'])->name('homepage-single-first-banner');

        Route::post('homepage-single-second-banner', [AdvertisementController::class, 'homepageSinleSecondBanner'])->name('homepage-single-second-banner');


        Route::post('homepage-flash-sale-sidebar-banner', [AdvertisementController::class, 'homepageFlashSaleSidebarBanner'])->name('homepage-flash-sale-sidebar-banner');


        Route::post('shop-page-center-banner', [AdvertisementController::class, 'shopPageCenterBanner'])->name('shop-page-center-banner');

        Route::post('shop-page-sidebar-banner', [AdvertisementController::class, 'shopPageSidebarBanner'])->name('shop-page-sidebar-banner');

        Route::get('login-page', [ContentController::class, 'loginPage'])->name('login-page');
        Route::post('update-login-page', [ContentController::class, 'updateloginPage'])->name('update-login-page');

        Route::get('image-content', [ContentController::class, 'image_content'])->name('image-content');
        Route::post('update-image-content', [ContentController::class, 'updateImageContent'])->name('update-image-content');

        Route::get('shop-page', [ContentController::class, 'shopPage'])->name('shop-page');
        Route::put('update-filter-price', [ContentController::class, 'updateFilterPrice'])->name('update-filter-price');

        Route::get('seo-setup', [ContentController::class, 'seoSetup'])->name('seo-setup');
        Route::put('update-seo-setup/{id}', [ContentController::class, 'updateSeoSetup'])->name('update-seo-setup');
        Route::get('get-seo-setup/{id}', [ContentController::class, 'getSeoSetup'])->name('get-seo-setup');


        Route::resource('state', CountryStateController::class);
        Route::put('state-status/{id}', [CountryStateController::class, 'changeStatus'])->name('state-status');

        Route::get('state-import-page', [CountryStateController::class, 'state_import_page'])->name('state-import-page');
        Route::get('state-export', [CountryStateController::class, 'state_export'])->name('state-export');
        Route::get('state-demo-export', [CountryStateController::class, 'demo_state_export'])->name('state-demo-export');
        Route::post('state-import', [CountryStateController::class, 'state_import'])->name('state-import');

        Route::resource('city', CityController::class);
        Route::put('city-status/{id}', [CityController::class, 'changeStatus'])->name('city-status');

        Route::get('city-import-page', [CityController::class, 'city_import_page'])->name('city-import-page');
        Route::get('city-export', [CityController::class, 'city_export'])->name('city-export');
        Route::get('city-demo-export', [CityController::class, 'demo_city_export'])->name('city-demo-export');
        Route::post('city-import', [CityController::class, 'city_import'])->name('city-import');

        Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method');

        Route::put('update-bank', [PaymentMethodController::class, 'updateBank'])->name('update-bank');
        Route::put('update-cash-on-delivery', [PaymentMethodController::class, 'updateCashOnDelivery'])->name('update-cash-on-delivery');
        Route::put('update-sslcommerz', [PaymentMethodController::class, 'updateSslcommerz'])->name('update-sslcommerz');


        Route::resource('mega-menu-category', MegaMenuController::class);
        Route::put('mega-menu-category-status/{id}', [MegaMenuController::class, 'changeStatus'])->name('mega-menu-category-status');

        Route::get('mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'index'])->name('mega-menu-sub-category');
        Route::get('create-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'create'])->name('create-mega-menu-sub-category');
        Route::get('get-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'show'])->name('get-mega-menu-sub-category');
        Route::post('store-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'store'])->name('store-mega-menu-sub-category');
        Route::get('edit-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'edit'])->name('edit-mega-menu-sub-category');
        Route::put('update-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'update'])->name('update-mega-menu-sub-category');
        Route::delete('delete-mega-menu-sub-category/{id}', [MegaMenuSubCategoryController::class, 'destroy'])->name('delete-mega-menu-sub-category');
        Route::put('mega-menu-sub-category-status/{id}', [MegaMenuSubCategoryController::class, 'changeStatus'])->name('mega-menu-sub-category-status');


        Route::resource('slider', SliderController::class);
        Route::put('slider-status/{id}', [SliderController::class, 'changeStatus'])->name('slider-status');


        Route::get('popular-category', [HomePageController::class, 'popularCategory'])->name('popular-category');
        Route::post('store-popular-category', [HomePageController::class, 'storePopularCategory'])->name('store-popular-category');
        Route::delete('destroy-popular-category/{id}', [HomePageController::class, 'destroyPopularCategory'])->name('destroy-popular-category');

        Route::put('popular-category-banner', [HomePageController::class, 'bannerPopularCategory'])->name('popular-category-banner');

        Route::put('featured-category-banner', [HomePageController::class, 'bannerFeaturedCategory'])->name('featured-category-banner');

        Route::get('featured-category', [HomePageController::class, 'featuredCategory'])->name('featured-category');
        Route::post('store-featured-category', [HomePageController::class, 'storeFeaturedCategory'])->name('store-featured-category');
        Route::delete('destroy-featured-category/{id}', [HomePageController::class, 'destroyFeaturedCategory'])->name('destroy-featured-category');


        Route::get('homepage-section-title', [HomePageController::class, 'homepage_section_content'])->name('homepage-section-title');
        Route::post('update-homepage-section-title', [HomePageController::class, 'update_homepage_section_content'])->name('update-homepage-section-title');



        Route::get('homepage-visibility', [HomepageVisibilityController::class, 'index'])->name('homepage-visibility');
        Route::put('update-homepage-visibility', [HomepageVisibilityController::class, 'update'])->name('update-homepage-visibility');

        Route::get('menu-visibility', [MenuVisibilityController::class, 'index'])->name('menu-visibility');
        Route::put('update-menu-visibility/{id}', [MenuVisibilityController::class, 'update'])->name('update-menu-visibility');

        Route::resource('shipping', ShippingMethodController::class);
        Route::get('city-wise-shipping/{city_id}', [ShippingMethodController::class, 'cityWiseShipping'])->name('city-wise-shipping');

        Route::get('shipping-import-page', [ShippingMethodController::class, 'shipping_import_page'])->name('shipping-import-page');
        Route::get('shipping-export', [ShippingMethodController::class, 'shipping_export'])->name('shipping-export');
        Route::get('shipping-demo-export', [ShippingMethodController::class, 'demo_shipping_export'])->name('shipping-demo-export');
        Route::post('shipping-import', [ShippingMethodController::class, 'shipping_import'])->name('shipping-import');

        Route::resource('withdraw-method', WithdrawMethodController::class);
        Route::put('withdraw-method-status/{id}', [WithdrawMethodController::class, 'changeStatus'])->name('withdraw-method-status');



        Route::get('all-order', [OrderController::class, 'index'])->name('all-order');
        Route::get('pending-order', [OrderController::class, 'pendingOrder'])->name('pending-order');
        Route::get('pregress-order', [OrderController::class, 'pregressOrder'])->name('pregress-order');
        Route::get('delivered-order', [OrderController::class, 'deliveredOrder'])->name('delivered-order');
        Route::get('completed-order', [OrderController::class, 'completedOrder'])->name('completed-order');
        Route::get('declined-order', [OrderController::class, 'declinedOrder'])->name('declined-order');
        Route::get('cash-on-delivery', [OrderController::class, 'cashOnDelivery'])->name('cash-on-delivery');
        Route::get('order-show/{id}', [OrderController::class, 'show'])->name('order-show');
        Route::delete('delete-order/{id}', [OrderController::class, 'destroy'])->name('delete-order');
        Route::put('update-order-status/{id}', [OrderController::class, 'updateOrderStatus'])->name('update-order-status');

        Route::resource('coupon', CouponController::class);
        Route::put('coupon-status/{id}', [CouponController::class, 'changeStatus'])->name('coupon-status');

        Route::resource('currency', CurrencyController::class);
        Route::put('currency-status/{id}', [CurrencyController::class, 'changeStatus'])->name('coupon.status');

        Route::resource('banner-image', BreadcrumbController::class);

        Route::resource('footer', FooterController::class);
        Route::resource('social-link', FooterSocialLinkController::class);
        Route::resource('footer-link', FooterLinkController::class);
        Route::get('second-col-footer-link', [FooterLinkController::class, 'secondColFooterLink'])->name('second-col-footer-link');
        Route::get('third-col-footer-link', [FooterLinkController::class, 'thirdColFooterLink'])->name('third-col-footer-link');
        Route::put('update-col-title/{id}', [FooterLinkController::class, 'updateColTitle'])->name('update-col-title');


        Route::get('admin-language', [LanguageController::class, 'adminLnagugae'])->name('admin-language');
        Route::post('update-admin-language', [LanguageController::class, 'updateAdminLanguage'])->name('update-admin-language');

        Route::get('admin-validation-language', [LanguageController::class, 'adminValidationLnagugae'])->name('admin-validation-language');
        Route::post('update-admin-validation-language', [LanguageController::class, 'updateAdminValidationLnagugae'])->name('update-admin-validation-language');


        Route::get('website-language', [LanguageController::class, 'websiteLanguage'])->name('website-language');
        Route::post('update-language', [LanguageController::class, 'updateLanguage'])->name('update-language');

        Route::get('website-validation-language', [LanguageController::class, 'websiteValidationLanguage'])->name('website-validation-language');
        Route::post('update-validation-language', [LanguageController::class, 'updateValidationLanguage'])->name('update-validation-language');


        Route::get('sms-notification', [NotificationController::class, 'twilio_sms'])->name('sms-notification');
        Route::put('update-twilio-configuration', [NotificationController::class, 'update_twilio_sms'])->name('update-twilio-configuration');
        Route::put('update-biztech-configuration', [NotificationController::class, 'update_biztech_sms'])->name('update-biztech-configuration');

        Route::get('sms-template', [NotificationController::class, 'sms_template'])->name('sms-template');
        Route::get('edit-sms-template/{id}', [NotificationController::class, 'edit_sms_template'])->name('edit-sms-template');
        Route::put('update-sms-template/{id}', [NotificationController::class, 'update_sms_template'])->name('update-sms-template');

        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::get('stock-history/{id}', [InventoryController::class, 'show_inventory'])->name('stock-history');
        Route::post('add-stock', [InventoryController::class, 'add_stock'])->name('add-stock');
        Route::delete('delete-stock/{id}', [InventoryController::class, 'delete_stock'])->name('delete-stock');


        // Pos Routes........
        Route::get('/pos', [PosController::class, 'Index'])->name('pos.index');
        Route::get('/pos/category/{id}', [PosController::class, 'categoryIndex'])->name('pos.category.index');
        Route::get('/products/search', [PosController::class, 'search'])->name('pos.product.search');
        Route::get('/pos/add/product/{id}', [PosController::class, 'AddProduct'])->name('pos.add.product');
        Route::get('/pos/product/delete/{id}', [PosController::class, 'Destroy'])->name('pos.destroy.product');
        Route::get('/pos/product/cart/increment/{id}', [PosController::class, 'cartIncremet'])->name('pos.cart.increment.product');
        Route::get('/pos/product/cart/decrement/delete/{id}', [PosController::class, 'cartDecrement'])->name('pos.cart.decrement.product');
        Route::get('/pos/product/cart/clear', [PosController::class, 'clearCart'])->name('pos.cart.clear.product');
        Route::post('/pos/add/customer', [PosController::class, 'addCustomer'])->name('pos.add.customer');
        Route::get('/pos/apply/cupon', [PosController::class, 'applyCupon'])->name('pos.apply.cupon');
        Route::post('/pos/order/submit', [PosController::class, 'orderSubmit'])->name('pos.order.submit');
        Route::get('/pos/bulk/order', [PosController::class, 'bulkOrder'])->name('pos.bulk.order');
        Route::get('/pos/bulk/order/serch', [PosController::class, 'bulkOrderSerch'])->name('pos.bulk.order.serch');
        Route::put('/pos/bulk/order/status/change', [PosController::class, 'updateOrderStatus'])->name('pos.bulk.order.status.change');
        Route::put('/pos/update/cart/product', [PosController::class, 'updatePosCart'])->name('pos.update.cart.order');
        Route::post('/pos/add/product/with/detils/{id}', [PosController::class, 'AddProductWithDetils'])->name('pos.cart.order.detils');

        Route::get('order/booking/{id}', [OrderController::class, 'booking'])->name('order.booking');
        Route::post('/add-new-product-in-order/{id}', [OrderController::class, 'addNewProduct'])->name('add-new-product-in-order');
        Route::get('/increment-order-quantity/{id}/{order_id}', [OrderController::class, 'incrementOrderQuantity'])->name('order-quantity-increment');
        Route::get('/decrement-order-quantity/{id}/{order_id}', [OrderController::class, 'decrementOrderQuantity'])->name('order-quantity-decrement');
        Route::delete('/delete-order-product/{id}/{order_id}', [OrderController::class, 'deleteOrderProduct'])->name('delete-order-product');
    });
});
