<?php

use App\Http\Controllers\Admin\Ext\InstallController;
use App\Http\Controllers\Admin\ExtensionController;
use App\Http\Controllers\Admin\Frontends\FrontendInstallController;
use App\Http\Controllers\Admin\ManageExchangesController;
use App\Http\Controllers\Admin\Providers\ProviderInstallController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\FrontendsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\QrCodeController;
use App\Models\GeneralSetting;
use App\Models\Page;
use App\Models\Platform;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('/apple-app-site-association', [HomeController::class, 'download']);

Route::get("/privacy", function () {
    return View::make("privacy_policy");
});

Route::get("/chat", function () {
    return View::make("chat");
});

Route::get("/refer", function () {
    $gen=GeneralSetting::first();
    $referral_bonus=$gen->referral_bonus;
    return View::make("refer", ['referral_bonus'=>$referral_bonus]);
});

Route::get("/bank", function () {
    return View::make("bank");
});

Route::get("/kyc", function () {
    return View::make("kyc");
});

Route::get("/referal", function () {
    return View::make("referal");
});



Route::get('/policy', [HomeController::class, 'privacyPolicy']);

Route::get('/terms-of-use', [HomeController::class, 'termsOfUse']);

Route::get('/about-us', [HomeController::class, 'aboutUs']);

Route::get("/graph/{symbol}", function () {
    return View::make("graph");
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('cron', 'CronController@index')->name('cron');
Route::get('cron/practice', 'CronController@practiceCron')->name('practice.cron');
Route::get('cron/schedule', 'CronController@scheduledOrdersCron')->name('schedule.cron');
Route::get('cron/crypto/price', 'CronController@store')->name('crypt.price');
Route::get('get-coins', 'HomeController@getCoins');
getRoute(1, 'cron');
getRoute(3, 'cron');
getRoute(4, 'cron');
getRoute(6, 'cron');
getRoute(8, 'cron');
Route::get('cron/provider/currencies', 'CronController@currencies')->name('provider.currencies');
Route::get('cron/provider/currenciesToTable', 'CronController@currenciesToTable')->name('provider.currenciesToTable');
Route::get('cron/provider/marketsToTable', 'CronController@marketsToTable')->name('provider.marketsToTable');
Route::get('cron/provider/pairsToTable', 'CronController@pairsToTable')->name('provider.pairsToTable');
Route::get('cron/provider/check/deposit', 'CronController@fetch_deposits')->name('provider.checkdeposit');
Route::get('cron/provider/fetch/order', 'CronController@fetch_order')->name('provider.fetchorder');
Route::get('cron/provider/marketsClean', 'CronController@marketsClean')->name('provider.marketsClean');

Route::get('/generate-qrcode', [QrCodeController::class, 'index']);

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});

Route::post('install', [UpdateController::class, 'download_update'])->name('install');

//Route::any('api/{any}', 'ViewController@app')->where('any','^(?!api).*$');

/*
|--------------------------------------------------------------------------
| Start Frontend Area
|--------------------------------------------------------------------------
*/

// Root route

$platform['frontend'] = json_decode(Platform::where('option', 'frontend')->first()->settings);
$platform['trading'] = json_decode(Platform::where('option', 'trading')->first()->settings);
$platform = arrayToObject($platform);
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('about', [HomeController::class, 'about'])->name('frontend.pages.about');
Route::get('banned', [HomeController::class, 'banned'])->name('banned');
Route::post('/subscribe', 'SiteController@subscribe')->name('subscribe');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit')->name('contact.send');
Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholderImage');
//Route::get('/{slug}', 'SiteController@pages')->name('pages');

Route::group(['prefix' => config('blogetc.blog_prefix', 'blog'), 'namespace' => 'Blog'], static function () {
    Route::get('/', 'PostsController@index')->name('blogetc.index');
    Route::get('/search', 'PostsController@search')->name('blogetc.search');
    Route::get('/feed', 'BlogEtcRssFeedController@feed')->name('blogetc.feed');
    Route::get('/category/{categorySlug}', 'PostsController@showCategory')->name('blogetc.view_category');
    Route::get('/{blogPostSlug}', 'PostsController@show')->name('blogetc.single');

    Route::group(['middleware' => 'throttle:10,3'], static function () {
        Route::post('save_comment/{blogPostSlug}', 'CommentsController@store')->name('blogetc.comments.add_new_comment');
    });
});

/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/

Route::get('lang/{locale}', 'LanguageController@swap');

require_once __DIR__ . '/jetstream.php';
require_once __DIR__ . '/fortify.php';

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('trade/{symbol}/{currency}', 'HomeController@trade')->name('trade');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => ['verified', 'checkStatus'], 'role:user', 'prefix' => 'user', 'as' => 'user.'], function () {
        // Router
        Route::get('/', 'UserController@index')->name('home');
        Route::post('dashboard', 'UserController@updateProfile')->name('profile.update');
        // Wallet
        // Route::group(['prefix' => 'wallet', 'as' => 'wallet.'], function () {
        //     Route::post('/create', 'WalletController@create')->name('create');
        //     Route::post('/j/create', 'WalletController@create_json')->name('create.json');
        //     Route::post('/fetch', 'WalletController@fetchWallet')->name('fetch');
        //     Route::post('/regenerate', 'WalletController@regenerate')->name('regenerate');
        //     Route::post('/deposit', 'WalletController@deposit')->name('deposit');
        //     Route::post('/withdraw', 'WalletController@withdraw')->middleware('vue')->name('withdraw');
        //     Route::post('/transfer/trading', 'WalletController@transfer_from_trading')->name('transfer.trading');
        //     Route::post('/transfer/funding', 'WalletController@transfer_from_funding')->name('transfer.funding');
        //     Route::post('/connect', 'WalletController@connect')->name('connect');
        //     Route::post('/disconnect', 'WalletController@disconnect')->name('disconnect');
        // });


        //KYC
        // Route::get('/kyc', 'User\KycController@index')->name('kyc');
        // Route::get('/kyc/application', 'User\KycController@application')->name('kyc.application');
        // Route::get('/kyc/application/view', 'User\KycController@view')->name('kyc.application.view');
        // Route::post('/kyc/submit', 'User\KycController@submit')->name('kyc.submit');


    });

    // Admin
    Route::group(['middleware' => 'role:admin,demo', 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('market', [MarketController::class, 'index'])->name('market');
        Route::get('api-tokens', 'AdminController@api')->name('api.index');
        Route::get('/clear', 'AdminController@clean')->name('clean')->middleware('demo');
        Route::match(array('GET', 'POST'), 'update', [UpdateController::class, 'index'])->name('update')->middleware('demo');
        Route::match(array('GET', 'POST'), 'lic/activate', [UpdateController::class, 'lic_activate'])->name('lic.activate')->middleware('demo');
        Route::match(array('GET', 'POST'), 'lic/deactivate', [UpdateController::class, 'lic_deactivate'])->name('lic.deactivate')->middleware('demo');


        Route::get('cron', [CronController::class, 'view'])->name('cron');

        // Notifications
        Route::get('notification/read/{id}', 'AdminController@notificationRead')->name('notification.read');
        Route::get('notification', 'AdminController@notifications')->name('notifications');

        // Users Manager
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', 'ManageUsersController@allUsers')->name('all');
            Route::post('remove', 'ManageUsersController@remove')->name('remove');
            Route::get('active', 'ManageUsersController@activeUsers')->name('active');
            Route::get('banned', 'ManageUsersController@bannedUsers')->name('banned');
            Route::get('disabled', 'ManageUsersController@disabledUsers')->name('disabled');
            Route::get('email-verified', 'ManageUsersController@emailVerifiedUsers')->name('emailVerified');
            Route::get('email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('emailUnverified');
            Route::get('sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('smsUnverified');
            Route::get('sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('smsVerified');
            Route::get('{scope}/search', 'ManageUsersController@search')->name('search');

            // Login History
            Route::get('login/history/{id}', 'ManageUsersController@userLoginHistory')->name('login.history.single');
            Route::get('send-email', 'ManageUsersController@showEmailAllForm')->name('email.all');
            Route::post('send-email', 'ManageUsersController@sendEmailAll')->name('email.send')->middleware('demo');
            Route::get('referral/log/{id}', 'ManageUsersController@referralLog')->name('referral.log');
            Route::get('commission/log/{id}', 'ManageUsersController@commissionLog')->name('commission.log');
        });

        // User Manager
        Route::group(['prefix' => 'user', 'as' => 'users.'], function () {
            Route::get('detail/{id}', 'ManageUsersController@detail')->name('detail');
            Route::get('enable/{id}', 'ManageUsersController@enable')->name('enable');
            Route::post('update/{id}', 'ManageUsersController@update')->name('update')->middleware('demo');
            Route::post('add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('addSubBalance')->middleware('demo');
            Route::get('send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('email.single');
            Route::post('send-email/{id}', 'ManageUsersController@sendEmailSingle')->middleware('demo');
            Route::get('transactions/{id}', 'ManageUsersController@transactions')->name('transactions');
            Route::get('deposits/{id}', 'ManageUsersController@deposits')->name('deposits');
            Route::get('deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depViaMethod')->name('deposits.method');
            Route::get('withdrawals/{id}', 'ManageUsersController@withdrawals')->name('withdrawals');
            Route::get('withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('withdrawals.method');
            Route::get('practice/trade/{id}', 'ManageUsersController@practiceLog')->name('practice.log');
            Route::get('trade/traded/{id}', 'ManageUsersController@traded')->name('traded');
            Route::get('trade/wining/{id}', 'ManageUsersController@wining')->name('wining');
            Route::get('trade/losing/{id}', 'ManageUsersController@losing')->name('losing');
            Route::get('trade/draw/{id}', 'ManageUsersController@draw')->name('draw');
        });
        Route::post('/wallet/create', '\App\Http\Controllers\WalletController@admincreateWallet')->name('wallet.create');
        Route::post('/wallet/regenerate', '\App\Http\Controllers\WalletController@adminregenerateWallet')->name('wallet.regenerate');


        // Exchange Logs


        // Providers
        Route::group(['prefix' => 'provider', 'as' => 'provider.'], function () {
            Route::get('/', 'ManageThirdpartyController@index')->name('index');

            Route::get('edit/{id}', 'ManageThirdpartyController@edit')->name('edit')->middleware('demo');
            Route::get('balances/{id}', 'ManageThirdpartyController@balances')->name('balances')->middleware('demo');
            Route::post('update', 'ManageThirdpartyController@update')->name('update')->middleware('demo');
            Route::post('activate', 'ManageThirdpartyController@activate')->name('activate')->middleware('demo');
            Route::post('deactivate', 'ManageThirdpartyController@deactivate')->name('deactivate')->middleware('demo');
            Route::match(array('GET', 'POST'), 'install/{id}', [ProviderInstallController::class, 'index'])->name('install');
            Route::get('activater/{id}', [ProviderInstallController::class, 'activater'])->name('activater');
            Route::post('verify', [ProviderInstallController::class, 'activate_licenser'])->name('verify');
            Route::post('updater/{id}', [ProviderInstallController::class, 'update'])->name('updater')->middleware('demo');

            Route::get('/{provider}/currencies', 'ManageThirdpartyController@currencies')->name('currencies.index');
            Route::post('/currencies/activate', 'ManageThirdpartyController@cur_activate')->name('currency.activate')->middleware('demo');
            Route::post('/currencies/deactivate', 'ManageThirdpartyController@cur_deactivate')->name('currency.deactivate')->middleware('demo');
            Route::get('/{provider}/markets', 'ManageThirdpartyController@markets')->name('markets.index');
            Route::post('/markets/activate', 'ManageThirdpartyController@market_activate')->name('market.activate')->middleware('demo');
            Route::post('/markets/deactivate', 'ManageThirdpartyController@market_deactivate')->name('market.deactivate')->middleware('demo');
            Route::get('/refresh', 'ManageThirdpartyController@refresh')->name('refresh')->middleware('demo');
        });

        // Deposit Gateway
        Route::name('payment.')->prefix('payment')->group(function () {
            // Automatic Gateway
            Route::get('provider', 'GatewayController@index')->name('provider.index');
            Route::get('provider/edit/{alias}', 'GatewayController@edit')->name('provider.edit');
            Route::post('provider/update/{code}', 'GatewayController@update')->name('provider.update')->middleware('demo');
            Route::post('provider/remove/{code}', 'GatewayController@remove')->name('provider.remove')->middleware('demo');
            Route::post('provider/activate', 'GatewayController@activate')->name('provider.activate')->middleware('demo');
            Route::post('provider/deactivate', 'GatewayController@deactivate')->name('provider.deactivate')->middleware('demo');

            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store')->middleware('demo');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update')->middleware('demo');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate')->middleware('demo');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate')->middleware('demo');
        });


        // Deposit System
        Route::name('deposit.')->prefix('deposit')->group(function () {
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject')->middleware('demo');
            Route::post('approve', 'DepositController@approve')->name('approve')->middleware('demo');
            Route::get('via/{method}/{type?}', 'DepositController@depViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');
        });

        // Withdraw
        Route::name('withdraw.')->prefix('withdraw')->group(function () {
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve')->middleware('demo');
            Route::post('reject', 'WithdrawalController@reject')->name('reject')->middleware('demo');

            // Withdraw Method
            Route::get('', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('create', 'WithdrawMethodController@store')->name('method.store')->middleware('demo');
            Route::get('edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('edit/{id}', 'WithdrawMethodController@update')->name('method.update')->middleware('demo');
            Route::post('activate', 'WithdrawMethodController@activate')->name('method.activate')->middleware('demo');
            Route::post('deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate')->middleware('demo');
        });

        // Report
        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('/transaction', 'ReportController@transaction')->name('transaction');
            Route::get('/transaction/search', 'ReportController@transactionSearch')->name('transaction.search');

            Route::get('/wallet', 'ReportController@wallet')->name('wallet');
            Route::get('/wallet/search', 'ReportController@wallet_search')->name('wallet.search');

            Route::get('/wallet/deposit', 'ReportController@wallet_deposit')->name('wallet.deposit');
            Route::get('/wallet/deposit/search', 'ReportController@wallet_deposit_search')->name('wallet.deposit.search');

            Route::get('/wallet/withdraw', 'ReportController@wallet_withdraw')->name('wallet.withdraw');
            Route::get('/wallet/withdraw/search', 'ReportController@wallet_withdraw_search')->name('wallet.withdraw.search');

            Route::get('/wallet/transfer/trading', 'ReportController@wallet_transfer_trading')->name('wallet.transfer.trading');
            Route::get('/wallet/transfer/trading/search', 'ReportController@wallet_transfer_trading_search')->name('wallet.transfer.trading.search');

            Route::get('/wallet/transfer/funding', 'ReportController@wallet_transfer_funding')->name('wallet.transfer.funding');
            Route::get('/wallet/transfer/funding/search', 'ReportController@wallet_transfer_funding_search')->name('wallet.transfer.funding.search');
            Route::post('/wallet/transfer/funding/approve', 'ReportController@wallet_transfer_funding_approve')->name('wallet.transfer.funding.approve');
            Route::post('/wallet/transfer/funding/reject', 'ReportController@wallet_transfer_funding_reject')->name('wallet.transfer.funding.reject');

            Route::get('/commission', 'ReportController@commission')->name('commission');
            Route::get('/commission/search', 'ReportController@commissionSearch')->name('commission.search');

            Route::get('/login/history', 'ReportController@loginHistory')->name('login.history');
            Route::get('/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('login.ipHistory');
        });

        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply')->middleware('demo');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete')->middleware('demo');

        //Cms Manager
        Route::get('cms', 'CmsController@index')->name('cms');
        Route::get('cmsdelete/{id}', 'CmsController@destroy')->name('cms.delete');
        Route::get('cmsedit/{id}', 'CmsController@edit')->name('cms.edit');
        Route::Post('cmssave', 'CmsController@store')->name('cms.store');
        Route::Post('cmsupdate', 'CmsController@update')->name('cms.update');
        Route::get('cmsshow/{id}', 'CmsController@show')->name('cms.show');
        Route::get('cmsadd', 'CmsController@create')->name('cms.add');

        //Banners Manager
        Route::get('banners', 'BannersController@index')->name('banners');
        Route::get('bannersdelete/{id}', 'BannersController@destroy')->name('banners.delete');
        Route::get('bannersedit/{id}', 'BannersController@edit')->name('banners.edit');
        Route::Post('bannerssave', 'BannersController@store')->name('banners.store');
        Route::Post('bannersupdate', 'BannersController@update')->name('banners.update');
        Route::get('bannersadd', 'BannersController@create')->name('banners.add');

        //Language Manager
        Route::get('languages', 'LanguageController@index')->name('languages');
        Route::get('languagedelete/{id}', 'LanguageController@destroy')->name('language.delete');
        Route::get('languageedit/{id}', 'LanguageController@edit')->name('language.edit');
        Route::Post('languagesave', 'LanguageController@store')->name('language.store');
        Route::Post('languageupdate', 'LanguageController@update')->name('language.update');
        Route::get('languageadd', 'LanguageController@create')->name('language.add');

        //Translation Manager
        Route::get('translations', 'LanguageKeyValueController@index')->name('translations');
        Route::get('translations/export', 'LanguageKeyValueController@export')->name('translations.export');
        Route::post('translations/import', 'LanguageKeyValueController@import')->name('translations.import');
        Route::get('translations/open-import-form', 'LanguageKeyValueController@openImportForm')->name('translations.open-import-form');

        //Coupon Code Manager
        Route::get('coupons', 'CouponCodeController@index')->name('coupons');
        Route::get('coupons/delete/{id}', 'CouponCodeController@destroy')->name('coupons.delete');
        Route::get('coupons/edit/{id}', 'CouponCodeController@edit')->name('coupons.edit');
        Route::Post('coupons/save', 'CouponCodeController@store')->name('coupons.store');
        Route::Post('coupons/update', 'CouponCodeController@update')->name('coupons.update');
        Route::get('coupons/add', 'CouponCodeController@create')->name('coupons.add');

        Route::get('assets', 'CoinsController@index_active')->name('assets');
        Route::get('assets/banned', 'CoinsController@index_banned')->name('assets.banned');
        Route::get('assets/change_status/{id}/{status}', 'CoinsController@change_status');

        // General Setting
        Route::get('settings', 'GeneralSettingController@index')->name('setting.index');
        Route::post('settings', 'GeneralSettingController@update')->name('setting.update')->middleware('demo');

        // Currencies Setting
        Route::get('currencies', 'GeneralSettingController@currencies')->name('currency.index');
        Route::post('currencies', 'GeneralSettingController@currency_update')->name('currency.update')->middleware('demo');
        Route::post('activate', 'GeneralSettingController@currency_activate')->name('currency.activate')->middleware('demo');

        // Platform Setting
        Route::get('platform', 'PlatformController@index')->name('platform');
        Route::post('platform', 'PlatformController@update')->name('platform.update')->middleware('demo');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo_icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo_icon_update')->middleware('demo');

        // Logo-Icon
        Route::get('/banner', 'GeneralSettingController@banner')->name('setting.banner');
        Route::get('/create-banner', 'GeneralSettingController@createBanner')->name('setting.createBanner');
        Route::get('/banner/{id}', 'GeneralSettingController@deleteBanner')->name('setting.deleteBanner');
        Route::post('/create-banner', 'GeneralSettingController@saveBanner')->name('setting.banner_update')->middleware('demo');


        // Extensions
        Route::group(['prefix' => 'extensions', 'as' => 'extensions.'], function () {
            Route::get('/', [ExtensionController::class, 'index'])->name('index');
            Route::match(array('GET', 'POST'), 'install/{id}', [InstallController::class, 'index'])->middleware('demo')->name('install');
            Route::get('activater/{id}', [InstallController::class, 'activater'])->name('activater');
            Route::post('verify', [InstallController::class, 'activate_licenser'])->middleware('demo')->name('verify');
            Route::post('update/{id}', [ExtensionController::class, 'update'])->name('update')->middleware('demo');
            Route::post('activate', [ExtensionController::class, 'activate'])->name('activate')->middleware('demo');
            Route::post('deactivate', [ExtensionController::class, 'deactivate'])->name('deactivate')->middleware('demo');
        });

        getRoute(1, 'admin');
        getRoute(2, 'admin');
        getRoute(3, 'admin');
        getRoute(4, 'admin');
        getRoute(5, 'admin');
        getRoute(6, 'admin');
        getRoute(8, 'admin');

        // SEO
        Route::get('seo-manager', [HomeController::class, 'seoEdit'])->name('seo');
        Route::post('frontend-content/{key}', [HomeController::class, 'frontendContent'])->name('seo.content')->middleware('demo');

        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {
            Route::get('/home', [HomeController::class, 'list'])->name('home');
            Route::get('/about', [HomeController::class, 'list'])->name('about');
            Route::get('/contact', [HomeController::class, 'list'])->name('contact');
            Route::post('/update', [HomeController::class, 'update'])->name('update')->middleware('demo');
        });
        // Frontend
        Route::name('template.')->prefix('template')->group(function () {
            Route::get('/index', [FrontendsController::class, 'index'])->name('index');
            Route::post('/activate', [FrontendsController::class, 'activate'])->name('activate');
            Route::post('/deactivate', [FrontendsController::class, 'deactivate'])->name('deactivate');
            Route::get('{template_id}/pages', [FrontendsController::class, 'pages'])->name('pages');
            Route::get('{template_id}/pages/{page_id}/sections', [FrontendsController::class, 'sections'])->name('sections');
            Route::post('/page/section/activate', [FrontendsController::class, 'sectionActivate'])->name('section.activate');
            Route::post('/page/section/deactivate', [FrontendsController::class, 'sectionDeactivate'])->name('section.deactivate');
            Route::get('{template_id}/pages/{page_id}/sections/{section_id}/editor', [FrontendsController::class, 'editor'])->name('editor');
            Route::post('/page/sections/editor/update/text', [FrontendsController::class, 'editorUpdateText'])->name('editor.update.text');
            Route::post('/page/sections/editor/update/image', [FrontendsController::class, 'editorUpdateImage'])->name('editor.update.image');
            Route::match(array('GET', 'POST'), 'install/{id}', [FrontendInstallController::class, 'index'])->name('install');
            Route::get('activater/{id}', [FrontendInstallController::class, 'activater'])->name('activater');
            Route::post('verify', [FrontendInstallController::class, 'activate_licenser'])->name('verify');
            Route::post('update/{id}', [FrontendsController::class, 'update'])->name('update')->middleware('demo');
        });


        /* Admin backend routes - CRUD for posts, categories, and approving/deleting submitted comments */
        Route::group(['prefix' => config('blogetc.admin_prefix', 'blog_admin')], static function () {
            Route::get('/', 'ManagePostsController@index')->name('blogetc.admin.index');
            Route::get('/add_post', 'ManagePostsController@create')->name('blogetc.admin.create_post');
            Route::post('/add_post', 'ManagePostsController@store')->name('blogetc.admin.store_post')->middleware('demo');
            Route::get('/edit_post/{blogPostId}', 'ManagePostsController@edit')->name('blogetc.admin.edit_post');
            Route::patch('/edit_post/{blogPostId}', 'ManagePostsController@update')->name('blogetc.admin.update_post');
            Route::group(['prefix' => 'image_uploads'], static function () {
                Route::get('/', 'ManageUploadsController@index')->name('blogetc.admin.images.all');
                Route::get('/upload', 'ManageUploadsController@create')->name('blogetc.admin.images.upload');
                Route::post('/upload', 'ManageUploadsController@store')->name('blogetc.admin.images.store')->middleware('demo');
                Route::get('/post/{postId}/delete-images', 'ManageUploadsController@deletePostImage')->name('blogetc.admin.images.delete-post-image');
                Route::delete('/post/{postId}/delete-images', 'ManageUploadsController@deletePostImageConfirmed')->name('blogetc.admin.images.delete-post-image-confirmed');
            });
            Route::delete('/delete_post/{blogPostId}', 'ManagePostsController@destroy')->name('blogetc.admin.destroy_post');
            Route::group(['prefix' => 'comments'], static function () {
                Route::get('/', 'ManageCommentsController@index')->name('blogetc.admin.comments.index');
                Route::patch('/{commentId}', 'ManageCommentsController@approve')->name('blogetc.admin.comments.approve');
                Route::delete('/{commentId}', 'ManageCommentsController@destroy')->name('blogetc.admin.comments.delete');
            });
            Route::group(['prefix' => 'categories'], static function () {
                Route::get('/', 'ManageCategoriesController@index')->name('blogetc.admin.categories.index');
                Route::get('/add_category', 'ManageCategoriesController@create')->name('blogetc.admin.categories.create_category');
                Route::post('/add_category', 'ManageCategoriesController@store')->name('blogetc.admin.categories.store_category')->middleware('demo');
                Route::get('/edit_category/{categoryId}', 'ManageCategoriesController@edit')->name('blogetc.admin.categories.edit_category');
                Route::patch('/edit_category/{categoryId}', 'ManageCategoriesController@update')->name('blogetc.admin.categories.update_category');
                Route::delete('/delete_category/{categoryId}', 'ManageCategoriesController@destroy')->name('blogetc.admin.categories.destroy_category');
            });
        });

        // KYC
        Route::get('/kyc-list/{status?}', 'KycController@index')->name('kycs');
        Route::group(['prefix' => 'kyc', 'as' => 'kyc.'], function () {
            Route::get('/view/{id}/{type}', 'KycController@show')->name('view');
            Route::post('/view', 'KycController@ajax_show')->name('ajax_show');
            Route::post('/update', 'KycController@update')->name('update')->middleware('demo');
            Route::post('/delete', 'KycController@delete')->name('delete')->middleware('demo');
            Route::get('/search', 'KycController@search')->name('search');
        });

        Route::get('/settings/email', 'EmailSettingController@index')->name('settings.email');
        Route::post('/users/email/send', 'UsersController@send_email')->name('users.email')->middleware('demo');
        Route::post('/settings/email/template/view', 'EmailSettingController@show_template')->name('settings.email.template.view');
        Route::post('/settings/email/update', 'EmailSettingController@update')->name('settings.email.update')->middleware('demo');
        Route::post('/settings/email/template/update', 'EmailSettingController@update_template')->name('settings.email.template.update')->middleware('demo');

        // Sidebar Manager
        Route::group(['prefix' => 'sidebar', 'as' => 'sidebar.'], function () {
            Route::get('admin', 'AdminController@sidebar_admin')->name('admin');
            Route::get('user', 'AdminController@sidebar_user')->name('user');
            Route::post('edit/{id}', 'AdminController@sidebar_edit')->name('edit')->middleware('demo');
            Route::post('activate', 'AdminController@sidebar_activate')->name('activate')->middleware('demo');
            Route::post('deactivate', 'AdminController@sidebar_deactivate')->name('deactivate')->middleware('demo');
        });

        // Cleaners
        Route::get('/settings/database', 'DatabaseController@index')->name('settings.database')->middleware('demo');
        Route::post('/database/binary/practice/logs/clean', 'DatabaseController@clean_binary_practice_logs')->name('database.binary.practice.logs.clean')->middleware('demo');
        Route::post('/database/binary/trade/logs/clean', 'DatabaseController@clean_binary_trade_logs')->name('database.binary.trade.logs.clean')->middleware('demo');
        Route::post('/database/trade/logs/clean', 'DatabaseController@clean_trade_logs')->name('database.trade.logs.clean')->middleware('demo');
        Route::post('/database/forex/investments/logs/clean', 'DatabaseController@clean_forex_investments_logs')->name('database.forex.investments.logs.clean')->middleware('demo');
        Route::post('/database/bot/investments/logs/clean', 'DatabaseController@clean_bot_investments_logs')->name('database.bot.investments.logs.clean')->middleware('demo');
        Route::post('/database/staking/logs/clean', 'DatabaseController@clean_staking_logs')->name('database.staking.logs.clean')->middleware('demo');
        Route::post('/database/ico/logs/clean', 'DatabaseController@clean_ico_logs')->name('database.ico.logs.clean')->middleware('demo');
        Route::post('/database/wallets/clean', 'DatabaseController@clean_wallets')->name('database.wallets.clean')->middleware('demo');

        Route::get('/alerts/remove/install', 'AdminController@remove_install_file')->name('alerts.remove_install_file')->middleware('demo');
    });
});
