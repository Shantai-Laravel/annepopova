<?php

$prefix = session('applocale');
$types = ['homewear', 'bijoux'];


Route::group(['prefix' => $prefix], function() use ($types){
    foreach ($types as $key => $type) {

        Route::group(['prefix' => $type], function(){

        // New Route
        // Product Slider:
        Route::post('/get-other-products', 'ProductsController@getOtherProducts');

        // Home Routes
        Route::post('/get-category-home', 'ProductsController@getCategoriesOnHome');

        // Auth
        Route::post('/auth-get-phone-codes-list', 'Auth\AuthController@getPhoneCodesList');

        // Home
        Route::post('/home-subcategories', 'PagesController@getHomeSubcategories');
        Route::post('/home-products', 'PagesController@getHomeProducts');
        Route::post('/home-collections', 'PagesController@getHomeCollections');

        // Categories
        Route::post('/categories', 'ProductsController@getProductsAll');
        Route::post('/simple-categories', 'ProductsController@getProductsAllSimple');

        // Categories
        Route::post('/get-sets', 'CollectionsController@getSets');

        // Outlet
        Route::post('/get-sale-products', 'ProductsController@getSaleProducts');

        // New Products
        Route::post('/get-new-products', 'ProductsController@getNewProducts');

        // Recently Products
        Route::post('/get-recently-products', 'ProductsController@getRecentlyProducts');

        // Filter
        Route::post('/filter', 'ProductsController@filter');
        Route::post('/setDefaultFilter', 'ProductsController@setDefaultFilter');

        // Subproducts
        Route::post('/get-subproduct', 'ProductsController@getSubproductVue');

        // Carts Routes
        Route::post('/get-cart-items', 'CartController@getCartItems');
        Route::post('/add-product-to-cart', 'CartController@addProductToCart');
        Route::post('/change-country', 'CartController@changeCountry');
        // Route::post('/add-set-to-cart', 'CartController@addSetToCart');
        Route::post('/add-product-to-cart-from-wish', 'CartController@addProductToCartFromWish');
        Route::post('/add-set-to-wish', 'WishListController@addSetToWish');

        Route::post('/remove-all-cart', 'CartController@removeAllCart');
        Route::post('/remove-cart-item', 'CartController@deleteProductFromCart');
        Route::post('/remove-cart-set', 'CartController@deleteSetFromCart');
        Route::post('/change-product-qty', 'CartController@changeProductQty');
        // Route::post('/change-set-qty', 'CartController@changeSetQty');
        Route::post('/move-product-to-wish', 'CartController@moveProductToWish');
        // Route::post('/move-set-to-wish', 'CartController@moveSetToWish');
        Route::post('/move-all-product-to-wish', 'CartController@moveAllProductToWish');
        // Route::post('/move-set-to-wish', 'CartController@moveSetToWish');
        Route::post('/get-countries', 'CartController@getCountries');
        Route::post('/set-country-delivery', 'CartController@setCountryDelivery');

        Route::post('/exchange-shipping-price', 'CartController@exchangeShippingPrice');

        Route::post('/check-promocode', 'CartController@checkPromocode');
        Route::post('/apply-promocode', 'CartController@applyPromocode');

        // Rent
        Route::post('/rent-product', 'FeedBackController@rentProduct');

        // Search
        Route::post('/search-product', 'ProductsController@searchProducts');

        // Favorites
        Route::post('/get-wish-items', 'WishListController@getWishItems');
        Route::post('/add-to-favorites', 'WishListController@addToFavorites');

        // Auth
        Route::post('/registration', 'Auth\RegistrationController@registration');
        Route::post('/auth-login', 'Auth\AuthController@login');
        Route::post('/auth-guest-login', 'Auth\AuthController@guestLogin');
        Route::post('/checkAuth', 'Auth\AuthController@checkAuth');

        Route::post('/reset-password-send-email', 'Auth\ResetPasswordController@sendEmailCode');
        Route::post('/reset-password-send-code', 'Auth\ResetPasswordController@confirmEmailCode');
        Route::post('/reset-password-send-password', 'Auth\ResetPasswordController@changePassword');

        // Wish
        Route::post('/moveProductToCart', 'WishListController@moveProductToCart');
        Route::post('/removeProductWish', 'WishListController@removeProductWish');
        Route::post('/removeSetWish', 'WishListController@removeSetWish');

        //Order
        Route::post('/order-get-user', 'OrderController@getUser');
        Route::post('/order-change-country', 'OrderController@changeCountry');
        Route::post('/order-shipping', 'OrderController@orderShipping');

        Route::post('/order-get-payments', 'PaymentController@getPaymentMethods');
        Route::get('/order/payment/methods/{methodId}/{amount}/{orderId}/{payment}', 'PaymentController@handlePreorder');

        // Order
        Route::get('/cart/getUserdata', 'CartController@getUserdata');
        Route::get('/cart/getAddressdata', 'CartController@getAddressdata');
        Route::post('/order', 'OrderController@orderProducts');
        Route::post('/order/userdata/create', 'OrderController@createUser');
        Route::post('/order/userdata/update', 'OrderController@updateUser');
        Route::post('/order/submitPhone', 'OrderController@submitPhone');
        Route::post('/order/sendCode', 'OrderController@sendCode');
        Route::post('/order/enterAsGuest', 'OrderController@enterAsGuest');
        Route::post('/order/address/create', 'OrderController@createAddress');
        Route::post('/order/address/update', 'OrderController@updateAddresses');
    });
    }
});
