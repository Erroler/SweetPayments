<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$root_domain =  parse_url(config('app.url'), PHP_URL_HOST);
$client_area_domain = (config('app.env') === 'production') ? 'clientarea.'.$root_domain : '';

Route::post('/paypal_ipn/{sale_id}', 'PaypalIPN@notification')->name('webshop.paypal_ipn');
Route::get('/api', 'PluginApiController@main')->name('plugin_api');
Route::post('/api', 'PluginApiController@main');

Route::domain($client_area_domain)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/', 'AuthController@login')->name('auth.login');
        Route::get('auth/steam', 'AuthController@redirectToSteam')->name('auth.steam');
        Route::get('auth/steam/handle', 'AuthController@handle')->name('auth.steam.handle');
    });

    Route::middleware('auth')->group(function() {
        Route::get('auth/steam/logout', 'AuthController@logout')->name('auth.steam.logout');
        Route::get('community', 'CommunityController@view_register_community_step1')->name('community.select.step1');
        Route::post('community', 'CommunityController@submit_register_community_step1')->name('community.select.step1');
        Route::get('community/2', 'CommunityController@view_register_community_step2')->name('community.select.step2');
        Route::post('community/2', 'CommunityController@submit_register_community_step2')->name('community.select.step2');
        Route::get('logout', 'AuthController@logout')->name('auth.logout');

        // Panel.
        Route::get('dashboard', 'DashboardController@main')->name('panel.dashboard');
        Route::get('dashboard/sales/api', 'DashboardController@sales_chart_ajax')->name('panel.dashboard.sales');
        Route::get('servers', 'ServersController@main')->name('panel.servers');
        Route::get('servers/plugin/download', 'ServersController@plugin')->name('panel.servers.plugin');
        Route::get('subscriptions', 'SubscriptionsController@main')->name('panel.subscriptions');
        Route::post('subscriptions', 'SubscriptionsController@create')->name('panel.subscriptions.create');
        Route::patch('subscriptions/{subscription}', 'SubscriptionsController@edit')->name('panel.subscriptions.edit');
        Route::get('subscriptions/sales/{subscription}', 'SubscriptionsController@showSales')->name('panel.subscriptions.sales');
        Route::get('sales', 'SalesController@main')->name('panel.sales');
        Route::get('sales/data/{subscription?}', 'SalesController@datatables')->name('panel.sales.datatables');
        Route::get('sales/{sale}', 'SalesController@show')->name('panel.sales.show');
        Route::get('withdrawls', 'AccountHistoryController@main')->name('panel.withdrawls');
        Route::post('withdrawls/paypal', 'AccountHistoryController@paypalWithdraw')->name('panel.withdrawls.paypal');
        Route::get('support', 'SupportController@index')->name('panel.support');
        Route::get('support/{ticket}', 'SupportController@show')->name('panel.support.show');
        Route::get('settings', 'SettingsController@index')->name('panel.settings');
        Route::post('settings', 'SettingsController@update')->name('panel.settings');
        Route::post('settings/callback_url', 'SettingsController@updateCallbackUrl')->name('panel.settings.callback');

        // Admin only.
        Route::middleware(['auth', 'admin'])->prefix('admin')->group(function() {
            Route::get('users', 'AdminController@users')->name('panel.admin.users');
            Route::get('community/{community}/servers', 'AdminController@servers')->name('panel.admin.servers');
            Route::get('community/{community}/subscriptions', 'AdminController@subscriptions')->name('panel.admin.subscriptions');
        });
    });
});


if (config('app.env') === 'production') {
    $webshop_url = '{webshop_name}.'.$root_domain;
    Route::domain($webshop_url)->group(function() {
        Route::get('/success', 'WebShopController@success')->name('webshop.success');
        Route::get('/', 'WebShopController@index')->name('webshop.index');
        Route::get('/{subscription}', 'WebShopController@buySubscription')->name('webshop.buy');
        Route::get('/{subscription}/login', 'WebShopController@login')->name('webshop.auth');
        Route::get('/{subscription}/login/handle', 'WebShopController@loginHandle')->name('webshop.auth.handle');
        Route::get('/{subscription}/checkout', 'WebShopController@checkout')->name('webshop.checkout');
        Route::get('/{subscription}/paypal', 'WebShopController@buyPaypal')->name('webshop.buy.paypal');
        Route::get('/{subscription}/paysafecard', 'WebShopController@buyPaysafecard')->name('webshop.buy.paysafecard');
        Route::post('/{subscription}/paysafecard', 'WebShopController@buyPaysafecardSubmit');
    });
}
else {
    Route::get('/shop/{webshop_name}/success', 'WebShopController@success')->name('webshop.success');
    Route::get('/shop/{webshop_name}/', 'WebShopController@index')->name('webshop.index');
    Route::get('/shop/{webshop_name}/{subscription}', 'WebShopController@buySubscription')->name('webshop.buy');
    Route::get('/shop/{webshop_name}/{subscription}/login', 'WebShopController@login')->name('webshop.auth');
    Route::get('/shop/{webshop_name}/{subscription}/login/handle', 'WebShopController@loginHandle')->name('webshop.auth.handle');
    Route::get('/shop/{webshop_name}/{subscription}/checkout', 'WebShopController@checkout')->name('webshop.checkout');
    Route::get('/shop/{webshop_name}/{subscription}/paypal', 'WebShopController@buyPaypal')->name('webshop.buy.paypal');
    Route::get('/shop/{webshop_name}/{subscription}/paysafecard', 'WebShopController@buyPaysafecard')->name('webshop.buy.paysafecard');
    Route::post('/shop/{webshop_name}/{subscription}/paysafecard', 'WebShopController@buyPaysafecardSubmit');
}

if (config('app.env') === 'production') {
    Route::get('/', 'LandingPageController@index')->name('landing_page');
    Route::get('clientarea', 'LandingPageController@clientarea');
    Route::get('{file}', 'LandingPageController@missing');
} else {
    Route::get('/landing', 'LandingPageController@index')->name('landing_page');
}