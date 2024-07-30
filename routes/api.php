<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CoinListController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\KycController;
use App\Http\Controllers\Api\LanguagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\PaymentGatewayController;
use App\Http\Controllers\Api\PriceAlertController;
use App\Http\Controllers\Api\ReferalController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WatchlistController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['guest:api']], function () {
    //Auth apis
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);

    Route::post('verify-email-and-phone', [LoginController::class, 'verifyEmailandPhone']);
    Route::post('social-login', [LoginController::class, 'socialLogin']);
    Route::post('forget-password', [LoginController::class, 'forgetPassword']);

    // Change Password or Create New Password
    Route::post('resend-otp', [LoginController::class, 'resendOtp']);

    Route::post('add-phone-number', [LoginController::class, 'addPhoneNumber']);

    Route::get('get-coin-list', [CoinListController::class, 'getCoinList']);

    Route::get('get-home-data', [HomeController::class, 'getHomeData']);

    Route::get('my-investments', [InvestmentController::class, 'myInvestments']);

    Route::get('test', [PriceAlertController::class, 'sendPriceAlertNotification']);

    Route::get('get-currency-listing', [LanguagesController::class, 'getCurrencyListing']);

    Route::get('get-language-listing', [LanguagesController::class, 'getLanguageListing']);
});

Route::group(['middleware' => 'auth:api'], function () {
   
    //Create or change pin
    Route::post('create-pin', [LoginController::class, 'createPin']);
    Route::post('forget-pin', [LoginController::class, 'forgetPin']);
    Route::post('verify-forget-pin', [LoginController::class, 'verifyForgetPin']);
    Route::post('reset-pin', [LoginController::class, 'resetPin']);

    Route::post('change-password', [LoginController::class, 'changePassword']);



    //User profile
    //Submit Kyc Detail
    Route::post('submit-kyc-details', [KycController::class, 'submitKycDetails']);

    Route::post('submit-kyc-selfie-details', [KycController::class, 'submitKycSelfieDetails']);

    Route::get('get-document-listing', [KycController::class, 'getDocumentList']);

    Route::get('logout', [LoginController::class, 'logout']);

    Route::get('disable', [LoginController::class, 'disable']);

    Route::get('disable-pin', [LoginController::class, 'disablePin']);

    Route::get('get-status', [LoginController::class, 'getStatus']);

    Route::get('get-notification-count', [LoginController::class, 'getNotificationCount']);

    
    //Payment gateway

    //Wallet Manager

    Route::get('transaction-listing', [WalletController::class, 'transactionListing']);

    Route::get('get-bank-account-details', [WalletController::class, 'getBankAccountDetails']);

    Route::post('add-bank-account', [WalletController::class, 'addBankAccount']);

    Route::post('create-price-alert', [PriceAlertController::class, 'createPriceAlert']);

    Route::post('delete-price-alert', [PriceAlertController::class, 'deletePriceAlert']);

    Route::get('price-alerts-listing', [PriceAlertController::class, 'priceAlertsListing']);

    Route::post('missing-deposits-search', [WalletController::class, 'missingDepositsSearch']);

    Route::post('add-to-watchlist', [WatchlistController::class, 'addToWatchlist']);

    Route::get('get-watchlist', [WatchlistController::class, 'getWatchlist']);

    Route::post('send-transaction-otp', [PaymentGatewayController::class, 'sendTransactionOtp']);

    Route::post('send-google-authenticator-otp', [LoginController::class, 'sendGoogleAuthenticatorOtp']);

    Route::post('verify-google-authenticator', [LoginController::class, 'verifyGoogleAuthenticator']);

    Route::post('verify-deposit-transaction', [PaymentGatewayController::class, 'verifyDepositTransaction']);

    Route::post('buy-coin', [OrdersController::class, 'buy']);

    Route::post('get-purchased-coin-price', [OrdersController::class, 'getPurchasedCoinPrice']);

    Route::post('sell-coin', [OrdersController::class, 'sell']);

    Route::get('order-listing', [OrdersController::class, 'orderListing']);

    Route::get('referal-listing', [ReferalController::class, 'referalListing']);

    Route::post('verify-withdraw-transaction', [PaymentGatewayController::class, 'verifyWithdrawTransaction']);

    Route::post('investment-details', [InvestmentController::class, 'investmentDetails']);

    Route::get('get-coin-details', [HomeController::class, 'getCoinDetails']);

    Route::post('create-withdraw-password', [WalletController::class, 'createWithdrawPassword']);
    Route::post('change-withdraw-password', [WalletController::class, 'changeWithdrawPassword']);

    Route::get('change-currency', [LanguagesController::class, 'changeCurrency']);

    Route::get('get-notification-listing', [PriceAlertController::class, 'getNotificationListing']);
});
