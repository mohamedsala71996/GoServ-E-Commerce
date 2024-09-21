<?php

use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\UserAuthController;
use App\Http\Controllers\Api\Dashboard\AboutOurStoreController;
use App\Http\Controllers\Api\Dashboard\ArticleController;
use App\Http\Controllers\Api\Dashboard\BrandController;
use App\Http\Controllers\Api\Dashboard\CategoryController;
use App\Http\Controllers\Api\Dashboard\ColorController;
use App\Http\Controllers\Api\Dashboard\ContactController;
use App\Http\Controllers\Api\Dashboard\ExchangeAndReturnPolicyController;
use App\Http\Controllers\Api\Dashboard\OfferController;
use App\Http\Controllers\Api\Dashboard\PreferProductController;
use App\Http\Controllers\Api\Dashboard\ProductColorPhotoController;
use App\Http\Controllers\Api\Dashboard\ProductController;
use App\Http\Controllers\Api\Dashboard\ProductFeatureController;
use App\Http\Controllers\Api\Dashboard\SectionController;
use App\Http\Controllers\Api\Dashboard\SocialMediaController;
use App\Http\Controllers\Api\Website\AboutOurStoreController as WebsiteAboutOurStoreController;
use App\Http\Controllers\Api\Website\ArticleController as WebsiteArticleController;
use App\Http\Controllers\Api\Website\BrandController as WebsiteBrandController;
use App\Http\Controllers\Api\Website\CartController;
use App\Http\Controllers\Api\Website\CategoryController as WebsiteCategoryController;
use App\Http\Controllers\Api\Website\ContactController as WebsiteContactController;
use App\Http\Controllers\Api\Website\ExchangeAndReturnPolicyController as WebsiteExchangeAndReturnPolicyController;
use App\Http\Controllers\Api\Website\PaymobController;
use App\Http\Controllers\Api\Website\PreferProductController as WebsitePreferProductController;
use App\Http\Controllers\Api\Website\ProductController as WebsiteProductController;
use App\Http\Controllers\Api\Website\ProductReviewController;
use App\Http\Controllers\Api\Website\SocialMediaController as WebsiteSocialMediaController;
use App\Http\Controllers\Api\Website\UserController;
use App\Http\Controllers\Api\Dashboard\AdminController;
use App\Http\Controllers\Api\Dashboard\AnalyticsController;
use App\Http\Controllers\Api\Dashboard\BannerController;
use App\Http\Controllers\Api\Dashboard\CityController;
use App\Http\Controllers\Api\Dashboard\CouponController;
use App\Http\Controllers\Api\Dashboard\GlobalOfferController;
use App\Http\Controllers\Api\Dashboard\OrderController as DashboardOrderController;
use App\Http\Controllers\Api\Dashboard\OrderDetailController;
use App\Http\Controllers\Api\Dashboard\OrderTaxController;
use App\Http\Controllers\Api\Dashboard\ProductReviewController as DashboardProductReviewController;
use App\Http\Controllers\Api\Dashboard\ReturnAndReplacementController as DashboardReturnAndReplacementController;
use App\Http\Controllers\Api\Dashboard\ReturnSettingController;
use App\Http\Controllers\Api\Dashboard\SalesReportController;
use App\Http\Controllers\Api\Dashboard\ShippingSettingsController;
use App\Http\Controllers\Api\Dashboard\ShippingWeightController;
use App\Http\Controllers\Api\Dashboard\UserController as DashboardUserController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\Website\BannerController as WebsiteBannerController;
use App\Http\Controllers\Api\Website\CheckoutController;
use App\Http\Controllers\Api\Website\GlobalOfferController as WebsiteGlobalOfferController;
use App\Http\Controllers\Api\Website\OrderController;
use App\Http\Controllers\Api\Website\ReturnAndReplacementController;
use App\Http\Controllers\Api\Website\SearchController;
use App\Http\Controllers\Api\Website\SectionController as WebsiteSectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('dashboard/admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware(['auth:sanctum,admin', 'type.admin']);
});

//    -------------------------------------------  dashboard  --------------------------------------
// Route::middleware(['auth:sanctum,admin', 'type.admin'])->prefix('dashboard')->group(function () {
Route::prefix('dashboard')->group(function () {

    //admins crud
    Route::resource('admins', AdminController::class);



    //categories
    Route::apiResource('categories', CategoryController::class);
    Route::post('update-category', [CategoryController::class, 'updateCategory']);

    //products
    Route::apiResource('products', ProductController::class);
    Route::post('update-product/{id}', [ProductController::class, 'updateProduct']);

    Route::get('get-sizes', [ProductController::class, 'getSizesOrderedByType']);

    //colors
    Route::apiResource('colors', ColorController::class);

    //product-color-photos
    // Route::apiResource('product-color-photo', ProductColorPhotoController::class);
    Route::get('get-color-photos/{product_id}', [ProductColorPhotoController::class, 'getColorPhotos']);

    Route::post('create-color-photos/{product_id}', [ProductColorPhotoController::class, 'createColorPhotos']);

    Route::post('update-color-photos', [ProductColorPhotoController::class, 'updateColorPhotos']);

    Route::delete('delete-color-photos/{id}', [ProductColorPhotoController::class, 'destroy']);


    //product features
    // Route::apiResource('product-features', ProductFeatureController::class);
    Route::get('get-product-features/{product_id}', [ProductFeatureController::class, 'getProductFeatures']);

    Route::post('create-product-features/{product_id}', [ProductFeatureController::class, 'createProductFeatures']);

    Route::put('update-product-features/{id}', [ProductFeatureController::class, 'update']);

    Route::delete('delete-product-features/{id}', [ProductFeatureController::class, 'destroy']);



    //offers
    Route::apiResource('offers', OfferController::class); //store here is for one productcolorsize
    Route::get('get-by-product/{product_id}', [OfferController::class, 'getOffersByProduct']); //store here is product all sizes
    Route::post('store-by-product', [OfferController::class, 'storeByProduct']); //store here is product all sizes
    Route::post('update-by-product', [OfferController::class, 'updateByProduct']); //store here is product all sizes
    Route::post('delete-by-product/{product_id}', [OfferController::class, 'deleteByProduct']); //store here is product all sizes

    Route::apiResource('global-offers', GlobalOfferController::class);
    // Route::post('/all-products-offer', [OfferController::class, 'applyOfferToAllProducts']);

    //banners_sec_one
    Route::apiResource('banners', BannerController::class);
    Route::post('update-banner', [BannerController::class, 'updateBanner']);
    Route::get('/get-banner/{id}', [BannerController::class, 'show']);



    //banner_sec_two
    Route::apiResource('prefer-products', PreferProductController::class);
    Route::post('update-prefer-products', [PreferProductController::class, 'updatePreferProduct']);


    //articles
    Route::apiResource('articles', ArticleController::class);
    Route::post('update-article', [ArticleController::class, 'updateArticle']);

    //articles
    Route::apiResource('brands', BrandController::class);
    Route::post('update-brand', [BrandController::class, 'updateBrand']);

    Route::post('store-brands', [BrandController::class, 'storeSeveralBrands']);

    Route::post('update-brands', [BrandController::class, 'updateOrCreateSeveralBrands']);

    //contacts
    Route::apiResource('contacts', ContactController::class);

    //social media
    Route::apiResource('social-media', SocialMediaController::class);

    //about-store
    Route::apiResource('about-store', AboutOurStoreController::class);

    //exchange-policy
    Route::apiResource('exchange-policy', ExchangeAndReturnPolicyController::class);


    Route::get('/sections', [SectionController::class, 'index']);

    Route::post('/sections/order', [SectionController::class, 'updateOrder']);

    Route::get('/pending-reviews', [DashboardProductReviewController::class, 'index']);

    Route::put('/setStatus-review/{id}', [DashboardProductReviewController::class, 'setStatus']);


    // orders details
    Route::get('/completed-orders', [OrderDetailController::class, 'completedOrders']);

    Route::get('/paid-orders', [OrderDetailController::class, 'paidOrders']);

    Route::get('/pending-orders', [OrderDetailController::class, 'pendingOrders']);

    Route::get('/accepted-orders', [OrderDetailController::class, 'acceptedOrders']);

    Route::get('/cancelled-orders', [OrderDetailController::class, 'cancelledOrders']);

    Route::get('/out-for-delivery-orders', [OrderDetailController::class, 'outForDeliveryOrders']);

    Route::get('/delivered-orders', [OrderDetailController::class, 'deliveredOrders']);

    Route::get('/not-received-orders', [OrderDetailController::class, 'notReceivedOrders']);

    Route::get('/returned-orders', [OrderDetailController::class, 'returnedOrders']);

    Route::get('/failed-orders', [OrderDetailController::class, 'failedOrders']);

    Route::get('/out-for-delivery-returned-orders', [OrderDetailController::class, 'outForDeliveryReturnedOrders']);

    Route::get('/delivered-returned-orders', [OrderDetailController::class, 'deliveredReturnedOrders']);

    Route::get('/total-completed-orders-amount', [OrderDetailController::class, 'totalCompletedOrdersAmount']);


    //copouns

    Route::get('coupons/get-coupons', [CouponController::class, 'index']);

    Route::post('coupons/create', [CouponController::class, 'store']);

    Route::post('coupons/create-all', [CouponController::class, 'storeSeveralCoupons']);

    Route::post('coupons/update-all', [CouponController::class, 'updateSeveralCoupons']);

    Route::post('coupons/update/{id}', [CouponController::class, 'update']);

    Route::post('coupons/destroy/{id}', [CouponController::class, 'destroy']);


    //cities

    Route::get('cities/get-cities', [CityController::class, 'index']);

    Route::post('cities/create', [CityController::class, 'store']);

    Route::post('cities/create-all', [CityController::class, 'storeSeveralCities']);

    Route::post('cities/update-all', [CityController::class, 'updateSeveralCities']);

    Route::post('cities/update/{id}', [CityController::class, 'update']);

    Route::post('cities/destroy/{id}', [CityController::class, 'destroy']);

    //weights

    Route::get('shipping-weights/get-all', [ShippingWeightController::class, 'index']);

    Route::post('shipping-weights/create', [ShippingWeightController::class, 'store']);

    Route::post('shipping-weights/create-all', [ShippingWeightController::class, 'storeSeveral']);

    Route::post('shipping-weights/update-all', [ShippingWeightController::class, 'updateSeveral']);

    Route::post('shipping-weights/update/{id}', [ShippingWeightController::class, 'update']);

    Route::post('shipping-weights/destroy/{id}', [ShippingWeightController::class, 'destroy']);


    // default_rate to all cities
    Route::get('shipping-settings/get', [ShippingSettingsController::class, 'index']);
    Route::post('shipping-settings/create-or-update', [ShippingSettingsController::class, 'update']);
    Route::post('shipping-settings/delete', [ShippingSettingsController::class, 'destroy']);


    // analytics
    Route::get('/analytics/visitors-count', [AnalyticsController::class, 'visitorCount']);

    Route::get('/analytics/total-completed-orders-amount', [AnalyticsController::class, 'totalCompletedOrdersAmount']);

    Route::get('/analytics/orders-count', [AnalyticsController::class, 'orderCount']);

    Route::get('/analytics/monthly-sales', [AnalyticsController::class, 'monthlySales']);

    Route::get('/analytics/daily-sales', [AnalyticsController::class, 'dailySales']);

    Route::get('/analytics/cart-notifications', [AnalyticsController::class, 'getNotifications']);

    Route::get('/analytics/out-of-stock', [AnalyticsController::class, 'outOfStock']);

    Route::get('/analytics/latest-orders', [AnalyticsController::class, 'latestOrders']);

    // Return And Replacement
    Route::post('update-return-status/{id}', [DashboardReturnAndReplacementController::class, 'updateReturnStatus']);
    Route::get('return-requests', [DashboardReturnAndReplacementController::class, 'getReturnRequests']);

    // ReturnSetting
    Route::get('return-settings', [ReturnSettingController::class, 'index']);
    Route::post('return-settings', [ReturnSettingController::class, 'update']);
   //order update status
    Route::post('/order-status/{orderId}', [DashboardOrderController::class, 'updateStatus']);
    // delete order
    Route::post('/delete-order/{orderId}', [DashboardOrderController::class, 'deleteOrder']);


    // User Analytics
    Route::get('all-clients', [DashboardUserController::class, 'getUsersWithOrdersCount']);

    Route::get('first-order-clients', [DashboardUserController::class, 'getUsersWithFirstOrderCount']);

    //taxes
    Route::get('order-tax', [OrderTaxController::class, 'index']);
    Route::post('order-tax', [OrderTaxController::class,'store']);
    Route::post('order-tax/update', [OrderTaxController::class, 'update']);
    Route::post('order-tax/destroy', [OrderTaxController::class, 'destroy']);

    // reports
    Route::get('get-reports', [SalesReportController::class, 'totalCompletedOrdersAmount']);

    Route::get('return-rate', [SalesReportController::class, 'returnRate']);

    Route::get('repeat-purchase-rate', [SalesReportController::class, 'repeatPurchaseRate']);

    Route::get('carts-average', [SalesReportController::class, 'cartsAverage']);

    Route::get('product-sales', [SalesReportController::class, 'productSalesReport']);

    Route::get('product-sales-without-size', [SalesReportController::class, 'productSalesWithoutSize']);

    Route::get('category-sales', [SalesReportController::class, 'categorySalesReport']);

    Route::get('brand-sales', [SalesReportController::class, 'brandSalesReport']);

    Route::get('city-sales', [SalesReportController::class, 'citySalesReport']);

});

//    -------------------------------------------  website  --------------------------------------

Route::prefix('website/user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::prefix('website')->middleware(['track.visitors'])->group(function () {

    //categories
    Route::get('/categories', [WebsiteCategoryController::class, 'index']);
    Route::get('/categories/{id}', [WebsiteCategoryController::class, 'show']);

    //products
    Route::get('/products', [WebsiteProductController::class, 'index']);
    Route::get('/new-products', [WebsiteProductController::class, 'newProducts']);

    Route::get('/products/offers', [WebsiteProductController::class, 'productsWithOffers']);
    Route::get('/products/{id}', [WebsiteProductController::class, 'show']);

    Route::get('/prefer-product', [WebsitePreferProductController::class, 'index']);
    Route::get('/products-search', [WebsiteProductController::class, 'search']);

    //banners_sec_one
    Route::get('/get-banners', [WebsiteBannerController::class, 'index']);

    Route::get('/get-banner/{id}', [WebsiteBannerController::class, 'show']);



    //product-review
    Route::apiResource('/product-review', ProductReviewController::class);
    Route::get('/product-review/product/{productId}', [ProductReviewController::class, 'getReviewsByProduct']);


    //article
    Route::get('/articles', [WebsiteArticleController::class, 'index']);
    Route::get('/articles/{id}', [WebsiteArticleController::class, 'show']);

    //brands
    Route::get('/brands', [WebsiteBrandController::class, 'index']);
    Route::get('/brands/{id}', [WebsiteBrandController::class, 'show']);

    //contacts
    Route::get('/contacts', [WebsiteContactController::class, 'index']);

    //social-media
    Route::get('/social-media', [WebsiteSocialMediaController::class, 'index']);

    //about-store
    Route::get('/about-store', [WebsiteAboutOurStoreController::class, 'index']);

    //about-store
    Route::get('/exchange-policy', [WebsiteExchangeAndReturnPolicyController::class, 'index']);

    // users count
    Route::get('/users-count', [UserController::class, 'usersCount']);

    Route::get('/sections', [WebsiteSectionController::class, 'index']);

    // search
    Route::get('/search-products', [SearchController::class, 'searchProducts']);

    Route::get('/all-categories', [SearchController::class, 'allCategories']);

    Route::get('/all-brands', [SearchController::class, 'allBrands']);

    Route::get('/all-colors', [SearchController::class, 'allColors']);

    Route::get('/rating-counts', [SearchController::class, 'getProductsCountByRating']);

    // global-offer
    Route::get('/global-offer', [WebsiteGlobalOfferController::class, 'index']);



    //cart
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('cart/add', [CartController::class, 'add']);
        Route::get('cart', [CartController::class, 'view']);
        Route::put('cart/{id}', [CartController::class, 'update']);
        Route::delete('cart/{id}', [CartController::class, 'remove']);
        Route::post('/checkout', [CheckoutController::class, 'checkout']);
        Route::post('/user-details', [CheckoutController::class, 'store']);

        // Paymob Routes
        Route::post('/paymob/credit', [PaymobController::class, 'credit']);
        Route::post('/paymob/callback', [PaymobController::class, 'callback']);

        // order return
        Route::post('request-return', [ReturnAndReplacementController::class, 'requestReturn']);

        // get orders for auth user
        Route::get('/user-orders', [OrderController::class, 'getUserOrders']);


    });



});



Route::get('/set-language/{lang}', [LanguageController::class, 'setLanguage']);
