<?php
\URL::forceScheme('https');

$prefix = session('applocale');

$types = ['homewear', 'bijoux'];

Route::get('/', 'PagesController@general')->name('index');
Route::get('/generate_pdf', 'PaymentController@generatePdf');
Route::get('/sitemap.xml', 'SitemapController@index');
Route::get('/payment-callback-maib', 'MaibApi\MaibPayment@callBackMaib');

Route::get('/test/swagger', 'Controller@testSwagger');

// Route::get('/test/paypal', 'PaypalController@test')->name('test-paypal');

Route::post('/payment/add-funds/paypal', 'PaypalController@testAddFunds');
Route::get('/status/{orderId}', 'PaypalController@getPaymentStatus')->name('status');

Route::any('/payment/callback', 'OrderController@paymentCollback');

// Front routes
Route::group(['prefix' => $prefix], function() use ($types)  {
    Route::get('/cart',  'CartController@index')->name('cart');
    Route::get('/wish',  'WishListController@index');

    // order
    Route::get('/order', 'OrderController@order')->name('order');
    Route::get('/order/payment/{orderId}', 'OrderController@orderPayment')->name('order-payment');

    Route::get('/order/payment/success/{orderId}', 'PaymentController@orderSuccess')->name('order-success');
    Route::get('/order/payment/fail/{orderId}', 'PaymentController@orderFail')->name('order-fail');

    Route::get('/thanks', 'OrderController@thanks')->name('thanks');

    Route::get('/login/{provider}', 'Auth\AuthController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

    //guest user settings
    Route::post('set-user-settings', 'Controller@setUserSettings');

    foreach ($types as $key => $type) {
        Route::group(['prefix' => $type], function()
        {
            Route::get('/',     'PagesController@index')->name('home');
            Route::get('/home', 'PagesController@index');

            Route::post('/contact-feed-back', 'FeedBackController@contactFeedBack');
            Route::post('/save-country-user', 'Controller@saveCountryUser');

            // product routes
            Route::get('/new',  'ProductsController@newRender');
            Route::get('/sale', 'ProductsController@saleRender');
            Route::get('/catalog/{category}', 'ProductsController@categoryRender');
            Route::get('/catalog/{category}/{product}', 'ProductsController@productRender')->name('single-product');
            Route::get('/hammer/{category}/{product}', 'ProductsController@productRenderHammer')->name('single-product');
            Route::get('/promotions', 'ProductsController@renderPromotions');

            // collections routes
            Route::get('/collections/all', 'CollectionsController@collectionRenderAll');
            Route::get('/set/{id}', 'CollectionsController@setItemRender');

            Route::get('/collection/{collection}', 'CollectionsController@collectionRender');
            Route::get('/collection/{collection}/{set}', 'CollectionsController@setRender');

            Route::get('/logout', 'Auth\AuthController@logout');
            Route::get('/login', 'Auth\AuthController@renderLogin');

            // Static Pages
            Route::get('/{pages}', 'PagesController@getPages')->name('pages');
        });
    }

    // Localization
    Cache::forget('lang.js');
    Route::get('/js/lang.js', 'LanguagesController@changeLangScript')->name('assets.lang');
});

// Personal Account routes
Route::group(['prefix' => $prefix, 'middleware' => 'auth_front'], function() {
    Route::get('/account/personal-data', 'AccountController@index')->name('account');
    Route::get('/account/promocodes', 'AccountController@getPromocodes')->name('account-promocodes');
    Route::get('/account/cart', 'AccountController@getCart')->name('account-cart');
    Route::get('/account/wishlist', 'AccountController@getWishlist')->name('account-wishlist');
    Route::post('/account/savePersonalData', 'AccountController@savePersonalData')->name('account.savePersonalData');
    Route::post('/account/changePass', 'AccountController@savePass')->name('account.savePass');
    Route::post('/account/addAddress', 'AccountController@addAddress')->name('account.addAddress');
    Route::post('/account/saveAddress/{id}', 'AccountController@saveAddress')->name('account.saveAddress');
    Route::get('/account/remove-account', 'AccountController@removeAccount')->name('account.removeAccount');

    Route::get('/account/history', 'AccountController@history')->name('account.history');
    Route::post('/account/historyCart/{id}', 'AccountController@historyCart')->name('account.historyCart');
    Route::get('/account/history/order/{order}', 'AccountController@historyOrder')->name('account.historyOrder');
    Route::post('/account/historyCartProduct/{id}', 'AccountController@historyCartProduct')->name('account.historyCartProduct');

    Route::get('/account/returns', 'AccountController@returns')->name('account.returns');
    Route::get('/account/returns/create', 'AccountController@createReturn');
    Route::get('/account/returns/create/{id}', 'AccountController@createReturnFromOrder');
    Route::post('/account/returns/store', 'AccountController@storeReturn');
    Route::get('/account/returns/{id}', 'AccountController@showReturn');

    Route::get('/account/return', 'AccountController@return')->name('account.return');
    Route::get('/account/return/order/{order}', 'AccountController@returnOrder')->name('account.returnOrder');
    Route::post('/account/return/addProductsToReturn/{order}', 'AccountController@addProductsToReturn')->name('account.addProductsToReturn');
    Route::post('/account/return/saveReturn/{return}', 'AccountController@saveReturn')->name('account.saveReturn');
});
