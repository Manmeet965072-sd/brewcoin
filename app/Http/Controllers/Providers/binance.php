<?php

namespace App\Http\Controllers\Providers;

use App\Http\Controllers\Controller;

use Exception;
use \ccxt\ExchangeError;
use \ccxt\AuthenticationError;
use \ccxt\ArgumentsRequired;
use \ccxt\BadRequest;
use \ccxt\MarginModeAlreadySet;
use \ccxt\InvalidOrder;
use \ccxt\NotSupported;
use \ccxt\DDoSProtection;

class binance extends Controller
{

    public function describe()
    {
        return $this->deep_extend(parent::describe(), array(
            'id' => 'binance',
            'name' => 'Binance',
            'countries' => array('JP', 'MT'), // Japan, Malta
            'rateLimit' => 50,
            'certified' => true,
            'pro' => true,

            'has' => array(
                'CORS' => null,
                'spot' => true,
                'margin' => true,
                'swap' => true,
                'future' => true,
                'option' => null,
                'addMargin' => true,
                'borrowMargin' => true,
                'cancelAllOrders' => true,
                'cancelOrder' => true,
                'cancelOrders' => null,
                'createDepositAddress' => false,
                'createOrder' => true,
                'createReduceOnlyOrder' => true,
                'createStopLimitOrder' => true,
                'createStopMarketOrder' => false,
                'createStopOrder' => true,
                'fetchAccounts' => null,
                'fetchBalance' => true,
                'fetchBidsAsks' => true,
                'fetchBorrowInterest' => true,
                'fetchBorrowRate' => true,
                'fetchBorrowRateHistories' => null,
                'fetchBorrowRateHistory' => true,
                'fetchBorrowRates' => false,
                'fetchBorrowRatesPerSymbol' => false,
                'fetchCanceledOrders' => false,
                'fetchClosedOrder' => null,
                'fetchClosedOrders' => 'emulated',
                'fetchCurrencies' => true,
                'fetchDeposit' => false,
                'fetchDepositAddress' => true,
                'fetchDepositAddresses' => false,
                'fetchDepositAddressesByNetwork' => false,
                'fetchDeposits' => true,
                'fetchFundingHistory' => true,
                'fetchFundingRate' => true,
                'fetchFundingRateHistory' => true,
                'fetchFundingRates' => true,
                'fetchIndexOHLCV' => true,
                'fetchL3OrderBook' => null,
                'fetchLedger' => null,
                'fetchLeverage' => false,
                'fetchLeverageTiers' => true,
                'fetchMarketLeverageTiers' => 'emulated',
                'fetchMarkets' => true,
                'fetchMarkOHLCV' => true,
                'fetchMyTrades' => true,
                'fetchOHLCV' => true,
                'fetchOpenInterestHistory' => true,
                'fetchOpenOrder' => false,
                'fetchOpenOrders' => true,
                'fetchOrder' => true,
                'fetchOrderBook' => true,
                'fetchOrderBooks' => false,
                'fetchOrders' => true,
                'fetchOrderTrades' => true,
                'fetchPosition' => null,
                'fetchPositions' => true,
                'fetchPositionsRisk' => true,
                'fetchPremiumIndexOHLCV' => false,
                'fetchStatus' => true,
                'fetchTicker' => true,
                'fetchTickers' => true,
                'fetchTime' => true,
                'fetchTrades' => true,
                'fetchTradingFee' => true,
                'fetchTradingFees' => true,
                'fetchTradingLimits' => null,
                'fetchTransactionFee' => null,
                'fetchTransactionFees' => true,
                'fetchTransactions' => false,
                'fetchTransfers' => true,
                'fetchWithdrawal' => false,
                'fetchWithdrawals' => true,
                'fetchWithdrawalWhitelist' => false,
                'reduceMargin' => true,
                'repayMargin' => true,
                'setLeverage' => true,
                'setMarginMode' => true,
                'setPositionMode' => true,
                'signIn' => false,
                'transfer' => true,
                'withdraw' => true,
            ),
            'timeframes' => array(
                '1m' => '1m',
                '3m' => '3m',
                '5m' => '5m',
                '15m' => '15m',
                '30m' => '30m',
                '1h' => '1h',
                '2h' => '2h',
                '4h' => '4h',
                '6h' => '6h',
                '8h' => '8h',
                '12h' => '12h',
                '1d' => '1d',
                '3d' => '3d',
                '1w' => '1w',
                '1M' => '1M',
            ),
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/1294454/29604020-d5483cdc-87ee-11e7-94c7-d1a8d9169293.jpg',
                'test' => array(
                    'dapiPublic' => 'https://testnet.binancefuture.com/dapi/v1',
                    'dapiPrivate' => 'https://testnet.binancefuture.com/dapi/v1',
                    'vapiPublic' => 'https://testnet.binanceops.com/vapi/v1',
                    'vapiPrivate' => 'https://testnet.binanceops.com/vapi/v1',
                    'fapiPublic' => 'https://testnet.binancefuture.com/fapi/v1',
                    'fapiPrivate' => 'https://testnet.binancefuture.com/fapi/v1',
                    'fapiPrivateV2' => 'https://testnet.binancefuture.com/fapi/v2',
                    'public' => 'https://testnet.binance.vision/api/v3',
                    'private' => 'https://testnet.binance.vision/api/v3',
                    'v1' => 'https://testnet.binance.vision/api/v1',
                ),
                'api' => array(
                    'wapi' => 'https://api.binance.com/wapi/v3',
                    'sapi' => 'https://api.binance.com/sapi/v1',
                    'sapiV3' => 'https://api.binance.com/sapi/v3',
                    'dapiPublic' => 'https://dapi.binance.com/dapi/v1',
                    'dapiPrivate' => 'https://dapi.binance.com/dapi/v1',
                    'vapiPublic' => 'https://vapi.binance.com/vapi/v1',
                    'vapiPrivate' => 'https://vapi.binance.com/vapi/v1',
                    'dapiPrivateV2' => 'https://dapi.binance.com/dapi/v2',
                    'dapiData' => 'https://dapi.binance.com/futures/data',
                    'fapiPublic' => 'https://fapi.binance.com/fapi/v1',
                    'fapiPrivate' => 'https://fapi.binance.com/fapi/v1',
                    'fapiData' => 'https://fapi.binance.com/futures/data',
                    'fapiPrivateV2' => 'https://fapi.binance.com/fapi/v2',
                    'public' => 'https://api.binance.com/api/v3',
                    'private' => 'https://api.binance.com/api/v3',
                    'v1' => 'https://api.binance.com/api/v1',
                ),
                'www' => 'https://www.binance.com',
                'referral' => array(
                    'url' => 'https://www.binance.com/en/register?ref=D7YA7CLY',
                    'discount' => 0.1,
                ),
                'doc' => array(
                    'https://binance-docs.github.io/apidocs/spot/en',
                ),
                'api_management' => 'https://www.binance.com/en/usercenter/settings/api-management',
                'fees' => 'https://www.binance.com/en/fee/schedule',
            ),
            'depth' => 1,
            'api' => array(

                'sapi' => array(
                    'get' => array(
                        'system/status' => 0.1,

                        'accountSnapshot' => 240, // Weight(IP) => 2400 => cost = 0.1 * 2400 = 240
                        'margin/asset' => 1, // Weight(IP) => 10 => cost = 0.1 * 10 = 1
                        'margin/pair' => 1,
                        'margin/allAssets' => 0.1,
                        'margin/allPairs' => 0.1,
                        'margin/priceIndex' => 1,

                        'asset/assetDividend' => 1,
                        'asset/dribblet' => 0.1,
                        'asset/transfer' => 0.1,
                        'asset/assetDetail' => 0.1,
                        'asset/tradeFee' => 0.1,
                        'margin/loan' => 1,
                        'margin/repay' => 1,
                        'margin/account' => 1,
                        'margin/transfer' => 0.1,
                        'margin/interestHistory' => 0.1,
                        'margin/forceLiquidationRec' => 0.1,
                        'margin/order' => 1,
                        'margin/openOrders' => 1,
                        'margin/allOrders' => 20, // Weight(IP) => 200 => cost = 0.1 * 200 = 20
                        'margin/myTrades' => 1,
                        'margin/maxBorrowable' => 5, // Weight(IP) => 50 => cost = 0.1 * 50 = 5
                        'margin/maxTransferable' => 5,
                        'margin/isolated/transfer' => 0.1,
                        'margin/isolated/account' => 1,
                        'margin/isolated/pair' => 1,
                        'margin/isolated/allPairs' => 1,
                        'margin/isolated/accountLimit' => 0.1,
                        'margin/interestRateHistory' => 0.1,
                        'margin/orderList' => 1,
                        'margin/allOrderList' => 20, // Weight(IP) => 200 => cost = 0.1 * 200 = 20
                        'margin/openOrderList' => 1,
                        'margin/crossMarginData' => array('cost' => 0.1, 'noCoin' => 0.5),
                        'margin/isolatedMarginData' => array('cost' => 0.1, 'noCoin' => 1),
                        'margin/isolatedMarginTier' => 0.1,
                        'margin/rateLimit/order' => 2,
                        'loan/income' => 40, // Weight(UID) => 6000 => cost = 0.006667 * 6000 = 40
                        'fiat/orders' => 600.03, // Weight(UID) => 90000 => cost = 0.006667 * 90000 = 600.03
                        'fiat/payments' => 0.1,
                        'futures/transfer' => 1,
                        'futures/loan/borrow/history' => 1,
                        'futures/loan/repay/history' => 1,
                        'futures/loan/wallet' => 1,
                        'futures/loan/configs' => 1,
                        'futures/loan/calcAdjustLevel' => 5, // Weight(IP) => 50 => cost = 0.1 * 50 = 5
                        'futures/loan/calcMaxAdjustAmount' => 5,
                        'futures/loan/adjustCollateral/history' => 1,
                        'futures/loan/liquidationHistory' => 1,
                        'rebate/taxQuery' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001

                        'capital/config/getall' => 1, // get networks for withdrawing USDT ERC20 vs USDT Omni
                        'capital/deposit/address' => 1,
                        'capital/deposit/hisrec' => 0.1,
                        'capital/deposit/subAddress' => 0.1,
                        'capital/deposit/subHisrec' => 0.1,
                        'capital/withdraw/history' => 0.1,
                        'convert/tradeFlow' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'account/status' => 0.1,
                        'account/apiTradingStatus' => 0.1,
                        'account/apiRestrictions/ipRestriction' => 0.1,
                        'bnbBurn' => 0.1,
                        'sub-account/futures/account' => 1,
                        'sub-account/futures/accountSummary' => 0.1,
                        'sub-account/futures/positionRisk' => 1,
                        'sub-account/futures/internalTransfer' => 0.1,
                        'sub-account/list' => 0.1,
                        'sub-account/margin/account' => 1,
                        'sub-account/margin/accountSummary' => 1,
                        'sub-account/spotSummary' => 0.1,
                        'sub-account/status' => 1,
                        'sub-account/sub/transfer/history' => 0.1,
                        'sub-account/transfer/subUserHistory' => 0.1,
                        'sub-account/universalTransfer' => 0.1,
                        'managed-subaccount/asset' => 0.1,
                        'managed-subaccount/accountSnapshot' => 240,

                        'lending/daily/product/list' => 0.1,
                        'lending/daily/userLeftQuota' => 0.1,
                        'lending/daily/userRedemptionQuota' => 0.1,
                        'lending/daily/token/position' => 0.1,
                        'lending/union/account' => 0.1,
                        'lending/union/purchaseRecord' => 0.1,
                        'lending/union/redemptionRecord' => 0.1,
                        'lending/union/interestHistory' => 0.1,
                        'lending/project/list' => 0.1,
                        'lending/project/position/list' => 0.1,

                        'mining/pub/coinList' => 0.1,
                        'mining/worker/detail' => 0.5, // Weight(IP) => 5 => cost = 0.1 * 5 = 0.5
                        'mining/worker/list' => 0.5,
                        'mining/payment/list' => 0.5,
                        'mining/statistics/user/status' => 0.5,
                        'mining/statistics/user/list' => 0.5,
                        'mining/payment/uid' => 0.5,

                        'bswap/pools' => 0.1,
                        'bswap/liquidity' => array('cost' => 0.1, 'noPoolId' => 1),
                        'bswap/liquidityOps' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'bswap/quote' => 1.00005, // Weight(UID) => 150 => cost = 0.006667 * 150 = 1.00005
                        'bswap/swap' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'bswap/poolConfigure' => 1.00005, // Weight(UID) => 150 => cost = 0.006667 * 150 = 1.00005
                        'bswap/addLiquidityPreview' => 1.00005, // Weight(UID) => 150 => cost = 0.006667 * 150 = 1.00005
                        'bswap/removeLiquidityPreview' => 1.00005, // Weight(UID) => 150 => cost = 0.006667 * 150 = 1.00005
                        'bswap/unclaimedRewards' => 6.667, // Weight(UID) => 1000 => cost = 0.006667 * 1000 = 6.667
                        'bswap/claimedHistory' => 6.667, // Weight(UID) => 1000 => cost = 0.006667 * 1000 = 6.667

                        'blvt/tokenInfo' => 0.1,
                        'blvt/subscribe/record' => 0.1,
                        'blvt/redeem/record' => 0.1,
                        'blvt/userLimit' => 0.1,

                        'apiReferral/ifNewUser' => 1,
                        'apiReferral/customization' => 1,
                        'apiReferral/userCustomization' => 1,
                        'apiReferral/rebate/recentRecord' => 1,
                        'apiReferral/rebate/historicalRecord' => 1,
                        'apiReferral/kickback/recentRecord' => 1,
                        'apiReferral/kickback/historicalRecord' => 1,
                        'broker/subAccountApi' => 1,
                        'broker/subAccount' => 1,
                        'broker/subAccountApi/commission/futures' => 1,
                        'broker/subAccountApi/commission/coinFutures' => 1,
                        'broker/info' => 1,
                        'broker/transfer' => 1,
                        'broker/transfer/futures' => 1,
                        'broker/rebate/recentRecord' => 1,
                        'broker/rebate/historicalRecord' => 1,
                        'broker/subAccount/bnbBurn/status' => 1,
                        'broker/subAccount/depositHist' => 1,
                        'broker/subAccount/spotSummary' => 1,
                        'broker/subAccount/marginSummary' => 1,
                        'broker/subAccount/futuresSummary' => 1,
                        'broker/rebate/futures/recentRecord' => 1,
                        'broker/subAccountApi/ipRestriction' => 1,
                        'broker/universalTransfer' => 1,

                        'account/apiRestrictions' => 0.1,

                        'c2c/orderMatch/listUserOrderHistory' => 0.1,

                        'nft/history/transactions' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'nft/history/deposit' => 20.001,
                        'nft/history/withdraw' => 20.001,
                        'nft/user/getAsset' => 20.001,
                        'pay/transactions' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'giftcard/verify' => 0.1,
                        'giftcard/cryptography/rsa-public-key' => 0.1,
                        'algo/futures/openOrders' => 0.1,
                        'algo/futures/historicalOrders' => 0.1,
                        'algo/futures/subOrders' => 0.1,
                        'portfolio/account' => 0.1,

                        'staking/productList' => 0.1,
                        'staking/position' => 0.1,
                        'staking/stakingRecord' => 0.1,
                        'staking/personalLeftQuota' => 0.1,
                    ),
                    'post' => array(
                        'asset/dust' => 1,
                        'asset/dust-btc' => 0.1,
                        'asset/transfer' => 0.1,
                        'asset/get-funding-asset' => 0.1,
                        'account/disableFastWithdrawSwitch' => 0.1,
                        'account/enableFastWithdrawSwitch' => 0.1,

                        'capital/withdraw/apply' => 0.1,
                        'margin/transfer' => 1, // Weight(IP) => 600 => cost = 0.1 * 600 = 60
                        'margin/loan' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'margin/repay' => 20.001,
                        'margin/order' => 0.040002, // Weight(UID) => 6 => cost = 0.006667 * 6 = 0.040002
                        'margin/order/oco' => 0.040002,

                        'margin/isolated/transfer' => 4.0002, // Weight(UID) => 600 => cost = 0.006667 * 600 = 4.0002
                        'margin/isolated/account' => 2.0001, // Weight(UID) => 300 => cost = 0.006667 * 300 = 2.0001
                        'bnbBurn' => 0.1,
                        'sub-account/margin/transfer' => 4.0002, // Weight(UID) => 600 => cost =  0.006667 * 600 = 4.0002
                        'sub-account/margin/enable' => 0.1,
                        'sub-account/futures/enable' => 0.1,
                        'sub-account/futures/transfer' => 0.1,
                        'sub-account/futures/internalTransfer' => 0.1,
                        'sub-account/transfer/subToSub' => 0.1,
                        'sub-account/transfer/subToMaster' => 0.1,
                        'sub-account/universalTransfer' => 0.1,
                        'managed-subaccount/deposit' => 0.1,
                        'managed-subaccount/withdraw' => 0.1,
                        'userDataStream' => 0.1,
                        'userDataStream/isolated' => 0.1,
                        'futures/transfer' => 0.1,
                        'futures/loan/borrow' => 20.001, // Weight(UID) => 3000 => cost = 0.006667 * 3000 = 20.001
                        'futures/loan/repay' => 20.001,
                        'futures/loan/adjustCollateral' => 20.001,

                        'lending/customizedFixed/purchase' => 0.1,
                        'lending/daily/purchase' => 0.1,
                        'lending/daily/redeem' => 0.1,

                        'bswap/liquidityAdd' => 60, // Weight(UID) => 1000 . (Additional => 1 request every 3 seconds =  0.333 requests per second) => cost = ( 1000 / rateLimit ) / 0.333 = 60.0000006
                        'bswap/liquidityRemove' => 60, // Weight(UID) => 1000 . (Additional => 1 request every three seconds)
                        'bswap/swap' => 60, // Weight(UID) => 1000 . (Additional => 1 request every three seconds)
                        'bswap/claimRewards' => 6.667, // Weight(UID) => 1000 => cost = 0.006667 * 1000 = 6.667

                        'blvt/subscribe' => 0.1,
                        'blvt/redeem' => 0.1,

                        'apiReferral/customization' => 1,
                        'apiReferral/userCustomization' => 1,
                        'apiReferral/rebate/historicalRecord' => 1,
                        'apiReferral/kickback/historicalRecord' => 1,
                        'broker/subAccount' => 1,
                        'broker/subAccount/margin' => 1,
                        'broker/subAccount/futures' => 1,
                        'broker/subAccountApi' => 1,
                        'broker/subAccountApi/permission' => 1,
                        'broker/subAccountApi/commission' => 1,
                        'broker/subAccountApi/commission/futures' => 1,
                        'broker/subAccountApi/commission/coinFutures' => 1,
                        'broker/transfer' => 1,
                        'broker/transfer/futures' => 1,
                        'broker/rebate/historicalRecord' => 1,
                        'broker/subAccount/bnbBurn/spot' => 1,
                        'broker/subAccount/bnbBurn/marginInterest' => 1,
                        'broker/subAccount/blvt' => 1,
                        'broker/subAccountApi/ipRestriction' => 1,
                        'broker/subAccountApi/ipRestriction/ipList' => 1,
                        'broker/universalTransfer' => 1,
                        'broker/subAccountApi/permission/universalTransfer' => 1,
                        'broker/subAccountApi/permission/vanillaOptions' => 1,

                        'giftcard/createCode' => 0.1,
                        'giftcard/redeemCode' => 0.1,
                        'algo/futures/newOrderVp' => 20.001,
                        'algo/futures/newOrderTwap' => 20.001,

                        'staking/purchase' => 0.1,
                        'staking/redeem' => 0.1,
                        'staking/setAutoStaking' => 0.1,
                    ),
                    'put' => array(
                        'userDataStream' => 0.1,
                        'userDataStream/isolated' => 0.1,
                    ),
                    'delete' => array(

                        'margin/openOrders' => 0.1,
                        'margin/order' => 0.0066667, // Weight(UID) => 1 => cost = 0.006667
                        'margin/orderList' => 0.0066667,
                        'margin/isolated/account' => 2.0001, // Weight(UID) => 300 => cost =  0.006667 * 300 = 2.0001
                        'userDataStream' => 0.1,
                        'userDataStream/isolated' => 0.1,

                        'broker/subAccountApi' => 1,
                        'broker/subAccountApi/ipRestriction/ipList' => 1,
                        'algo/futures/order' => 0.1,
                    ),
                ),
                'sapiV3' => array(
                    'get' => array(
                        'sub-account/assets' => 1,
                    ),
                ),

                'wapi' => array(
                    'post' => array(
                        'withdraw' => 1,
                        'sub-account/transfer' => 1,
                    ),
                    'get' => array(
                        'depositHistory' => 1,
                        'withdrawHistory' => 1,
                        'depositAddress' => 1,
                        'accountStatus' => 1,
                        'systemStatus' => 1,
                        'apiTradingStatus' => 1,
                        'userAssetDribbletLog' => 1,
                        'tradeFee' => 1,
                        'assetDetail' => 1,
                        'sub-account/list' => 1,
                        'sub-account/transfer/history' => 1,
                        'sub-account/assets' => 1,
                    ),
                ),
                'dapiPublic' => array(
                    'get' => array(
                        'ping' => 1,
                        'time' => 1,
                        'exchangeInfo' => 1,
                        'depth' => array('cost' => 2, 'byLimit' => array(array(50, 2), array(100, 5), array(500, 10), array(1000, 20))),
                        'trades' => 1,
                        'historicalTrades' => 20,
                        'aggTrades' => 20,
                        'premiumIndex' => 10,
                        'fundingRate' => 1,
                        'klines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'continuousKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'indexPriceKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'markPriceKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'ticker/24hr' => array('cost' => 1, 'noSymbol' => 40),
                        'ticker/price' => array('cost' => 1, 'noSymbol' => 2),
                        'ticker/bookTicker' => array('cost' => 1, 'noSymbol' => 2),
                        'openInterest' => 1,
                    ),
                ),
                'dapiData' => array(
                    'get' => array(
                        'openInterestHist' => 1,
                        'topLongShortAccountRatio' => 1,
                        'topLongShortPositionRatio' => 1,
                        'globalLongShortAccountRatio' => 1,
                        'takerBuySellVol' => 1,
                        'basis' => 1,
                    ),
                ),
                'dapiPrivate' => array(
                    'get' => array(
                        'positionSide/dual' => 30,
                        'order' => 1,
                        'openOrder' => 1,
                        'openOrders' => array('cost' => 1, 'noSymbol' => 5),
                        'allOrders' => array('cost' => 20, 'noSymbol' => 40),
                        'balance' => 1,
                        'account' => 5,
                        'positionMargin/history' => 1,
                        'positionRisk' => 1,
                        'userTrades' => array('cost' => 20, 'noSymbol' => 40),
                        'income' => 20,
                        'leverageBracket' => 1,
                        'forceOrders' => array('cost' => 20, 'noSymbol' => 50),
                        'adlQuantile' => 5,
                        'orderAmendment' => 1,
                    ),
                    'post' => array(
                        'positionSide/dual' => 1,
                        'order' => 4,
                        'batchOrders' => 5,
                        'countdownCancelAll' => 10,
                        'leverage' => 1,
                        'marginType' => 1,
                        'positionMargin' => 1,
                        'listenKey' => 1,
                    ),
                    'put' => array(
                        'listenKey' => 1,
                        'order' => 1,
                        'batchOrders' => 5,
                    ),
                    'delete' => array(
                        'order' => 1,
                        'allOpenOrders' => 1,
                        'batchOrders' => 5,
                        'listenKey' => 1,
                    ),
                ),
                'dapiPrivateV2' => array(
                    'get' => array(
                        'leverageBracket' => 1,
                    ),
                ),
                'fapiPublic' => array(
                    'get' => array(
                        'ping' => 1,
                        'time' => 1,
                        'exchangeInfo' => 1,
                        'depth' => array('cost' => 2, 'byLimit' => array(array(50, 2), array(100, 5), array(500, 10), array(1000, 20))),
                        'trades' => 1,
                        'historicalTrades' => 20,
                        'aggTrades' => 20,
                        'klines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'continuousKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'markPriceKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'indexPriceKlines' => array('cost' => 1, 'byLimit' => array(array(99, 1), array(499, 2), array(1000, 5), array(10000, 10))),
                        'fundingRate' => 1,
                        'premiumIndex' => 1,
                        'ticker/24hr' => array('cost' => 1, 'noSymbol' => 40),
                        'ticker/price' => array('cost' => 1, 'noSymbol' => 2),
                        'ticker/bookTicker' => array('cost' => 1, 'noSymbol' => 2),
                        'openInterest' => 1,
                        'indexInfo' => 1,
                        'apiTradingStatus' => array('cost' => 1, 'noSymbol' => 10),
                        'lvtKlines' => 1,
                    ),
                ),
                'fapiData' => array(
                    'get' => array(
                        'openInterestHist' => 1,
                        'topLongShortAccountRatio' => 1,
                        'topLongShortPositionRatio' => 1,
                        'globalLongShortAccountRatio' => 1,
                        'takerlongshortRatio' => 1,
                    ),
                ),
                'fapiPrivate' => array(
                    'get' => array(
                        'forceOrders' => array('cost' => 20, 'noSymbol' => 50),
                        'allOrders' => 5,
                        'openOrder' => 1,
                        'openOrders' => 1,
                        'order' => 1,
                        'account' => 5,
                        'balance' => 5,
                        'leverageBracket' => 1,
                        'positionMargin/history' => 1,
                        'positionRisk' => 5,
                        'positionSide/dual' => 30,
                        'userTrades' => 5,
                        'income' => 30,
                        'commissionRate' => 20,
                        'apiTradingStatus' => 1,
                        'multiAssetsMargin' => 30,

                        'apiReferral/ifNewUser' => 1,
                        'apiReferral/customization' => 1,
                        'apiReferral/userCustomization' => 1,
                        'apiReferral/traderNum' => 1,
                        'apiReferral/overview' => 1,
                        'apiReferral/tradeVol' => 1,
                        'apiReferral/rebateVol' => 1,
                        'apiReferral/traderSummary' => 1,
                        'adlQuantile' => 5,
                    ),
                    'post' => array(
                        'batchOrders' => 5,
                        'positionSide/dual' => 1,
                        'positionMargin' => 1,
                        'marginType' => 1,
                        'order' => 4,
                        'leverage' => 1,
                        'listenKey' => 1,
                        'countdownCancelAll' => 10,
                        'multiAssetsMargin' => 1,

                        'apiReferral/customization' => 1,
                        'apiReferral/userCustomization' => 1,
                    ),
                    'put' => array(
                        'listenKey' => 1,
                    ),
                    'delete' => array(
                        'batchOrders' => 1,
                        'order' => 1,
                        'allOpenOrders' => 1,
                        'listenKey' => 1,
                    ),
                ),
                'fapiPrivateV2' => array(
                    'get' => array(
                        'account' => 1,
                        'balance' => 1,
                        'positionRisk' => 1,
                    ),
                ),
                'vapiPublic' => array(
                    'get' => array(
                        'ping',
                        'time',
                        'optionInfo',
                        'exchangeInfo',
                        'index',
                        'ticker',
                        'mark',
                        'depth',
                        'klines',
                        'trades',
                        'historicalTrades',
                    ),
                ),
                'vapiPrivate' => array(
                    'get' => array(
                        'account',
                        'position',
                        'order',
                        'openOrders',
                        'historyOrders',
                        'userTrades',
                    ),
                    'post' => array(
                        'transfer',
                        'bill',
                        'order',
                        'batchOrders',
                        'userDataStream',
                        'openAccount',
                    ),
                    'put' => array(
                        'userDataStream',
                    ),
                    'delete' => array(
                        'order',
                        'batchOrders',
                        'allOpenOrders',
                        'userDataStream',
                    ),
                ),
                'public' => array(
                    'get' => array(
                        'ping' => 1,
                        'time' => 1,
                        'depth' => array('cost' => 1, 'byLimit' => array(array(100, 1), array(500, 5), array(1000, 10), array(5000, 50))),
                        'trades' => 1,
                        'aggTrades' => 1,
                        'historicalTrades' => 5,
                        'klines' => 1,
                        'ticker/24hr' => array('cost' => 1, 'noSymbol' => 40),
                        'ticker/price' => array('cost' => 1, 'noSymbol' => 2),
                        'ticker/bookTicker' => array('cost' => 1, 'noSymbol' => 2),
                        'exchangeInfo' => 10,
                    ),
                    'put' => array(
                        'userDataStream' => 1,
                    ),
                    'post' => array(
                        'userDataStream' => 1,
                    ),
                    'delete' => array(
                        'userDataStream' => 1,
                    ),
                ),
                'private' => array(
                    'get' => array(
                        'allOrderList' => 10, // oco
                        'openOrderList' => 3, // oco
                        'orderList' => 2, // oco
                        'order' => 2,
                        'openOrders' => array('cost' => 3, 'noSymbol' => 40),
                        'allOrders' => 10,
                        'account' => 10,
                        'myTrades' => 10,
                        'rateLimit/order' => 20,
                    ),
                    'post' => array(
                        'order/oco' => 1,
                        'order' => 1,
                        'order/test' => 1,
                    ),
                    'delete' => array(
                        'openOrders' => 1, // added on 2020-04-25 for canceling all open orders per symbol
                        'orderList' => 1, // oco
                        'order' => 1,
                    ),
                ),
            ),
            'fees' => array(
                'trading' => array(
                    'feeSide' => 'get',
                    'tierBased' => false,
                    'percentage' => true,
                    'taker' => $this->parse_number('0.001'),
                    'maker' => $this->parse_number('0.001'),
                ),
                'future' => array(
                    'trading' => array(
                        'feeSide' => 'quote',
                        'tierBased' => true,
                        'percentage' => true,
                        'taker' => $this->parse_number('0.000400'),
                        'maker' => $this->parse_number('0.000200'),
                        'tiers' => array(
                            'taker' => array(
                                array($this->parse_number('0'), $this->parse_number('0.000400')),
                                array($this->parse_number('250'), $this->parse_number('0.000400')),
                                array($this->parse_number('2500'), $this->parse_number('0.000350')),
                                array($this->parse_number('7500'), $this->parse_number('0.000320')),
                                array($this->parse_number('22500'), $this->parse_number('0.000300')),
                                array($this->parse_number('50000'), $this->parse_number('0.000270')),
                                array($this->parse_number('100000'), $this->parse_number('0.000250')),
                                array($this->parse_number('200000'), $this->parse_number('0.000220')),
                                array($this->parse_number('400000'), $this->parse_number('0.000200')),
                                array($this->parse_number('750000'), $this->parse_number('0.000170')),
                            ),
                            'maker' => array(
                                array($this->parse_number('0'), $this->parse_number('0.000200')),
                                array($this->parse_number('250'), $this->parse_number('0.000160')),
                                array($this->parse_number('2500'), $this->parse_number('0.000140')),
                                array($this->parse_number('7500'), $this->parse_number('0.000120')),
                                array($this->parse_number('22500'), $this->parse_number('0.000100')),
                                array($this->parse_number('50000'), $this->parse_number('0.000080')),
                                array($this->parse_number('100000'), $this->parse_number('0.000060')),
                                array($this->parse_number('200000'), $this->parse_number('0.000040')),
                                array($this->parse_number('400000'), $this->parse_number('0.000020')),
                                array($this->parse_number('750000'), $this->parse_number('0')),
                            ),
                        ),
                    ),
                ),
                'delivery' => array(
                    'trading' => array(
                        'feeSide' => 'base',
                        'tierBased' => true,
                        'percentage' => true,
                        'taker' => $this->parse_number('0.000500'),
                        'maker' => $this->parse_number('0.000100'),
                        'tiers' => array(
                            'taker' => array(
                                array($this->parse_number('0'), $this->parse_number('0.000500')),
                                array($this->parse_number('250'), $this->parse_number('0.000450')),
                                array($this->parse_number('2500'), $this->parse_number('0.000400')),
                                array($this->parse_number('7500'), $this->parse_number('0.000300')),
                                array($this->parse_number('22500'), $this->parse_number('0.000250')),
                                array($this->parse_number('50000'), $this->parse_number('0.000240')),
                                array($this->parse_number('100000'), $this->parse_number('0.000240')),
                                array($this->parse_number('200000'), $this->parse_number('0.000240')),
                                array($this->parse_number('400000'), $this->parse_number('0.000240')),
                                array($this->parse_number('750000'), $this->parse_number('0.000240')),
                            ),
                            'maker' => array(
                                array($this->parse_number('0'), $this->parse_number('0.000100')),
                                array($this->parse_number('250'), $this->parse_number('0.000080')),
                                array($this->parse_number('2500'), $this->parse_number('0.000050')),
                                array($this->parse_number('7500'), $this->parse_number('0.0000030')),
                                array($this->parse_number('22500'), $this->parse_number('0')),
                                array($this->parse_number('50000'), $this->parse_number('-0.000050')),
                                array($this->parse_number('100000'), $this->parse_number('-0.000060')),
                                array($this->parse_number('200000'), $this->parse_number('-0.000070')),
                                array($this->parse_number('400000'), $this->parse_number('-0.000080')),
                                array($this->parse_number('750000'), $this->parse_number('-0.000090')),
                            ),
                        ),
                    ),
                ),
                'option' => array(),
            ),
            'commonCurrencies' => array(
                'BCC' => 'BCC', // kept for backward-compatibility https://github.com/ccxt/ccxt/issues/4848
                'YOYO' => 'YOYOW',
            ),
            'options' => array(
                'fetchCurrencies' => true, // this is a private call and it requires API keys

                'defaultTimeInForce' => 'GTC', // 'GTC' = Good To Cancel (default), 'IOC' = Immediate Or Cancel
                'defaultType' => 'spot', // 'spot', 'future', 'margin', 'delivery'
                'defaultMarginMode' => 'cross', // cross, isolated
                'hasAlreadyAuthenticatedSuccessfully' => false,
                'warnOnFetchOpenOrdersWithoutSymbol' => true,

                'throwMarginModeAlreadySet' => false,
                'fetchPositions' => 'positionRisk', // or 'account'
                'recvWindow' => 5 * 1000, // 5 sec, binance default
                'timeDifference' => 0, // the difference between system clock and Binance clock
                'adjustForTimeDifference' => false, // controls the adjustment logic upon instantiation
                'newOrderRespType' => array(
                    'market' => 'FULL', // 'ACK' for order id, 'RESULT' for full order or 'FULL' for order with fills
                    'limit' => 'FULL', // we change it from 'ACK' by default to 'FULL' (returns immediately if limit is not hit)
                ),
                'settle' => array(
                    'USDT' => 'linear',
                    'BUSD' => 'linear',
                ),
                'quoteOrderQty' => true, // whether market orders support amounts in quote currency
                'broker' => array(
                    'spot' => 'x-R4BD3S82',
                    'margin' => 'x-R4BD3S82',
                    'future' => 'x-xcKtGhcu',
                    'delivery' => 'x-xcKtGhcu',
                ),
                'accountsByType' => array(
                    'main' => 'MAIN',
                    'spot' => 'MAIN',
                    'funding' => 'FUNDING',
                    'margin' => 'MARGIN',
                    'future' => 'UMFUTURE',
                    'delivery' => 'CMFUTURE',
                    'mining' => 'MINING',
                ),
                'accountsById' => array(
                    'MAIN' => 'spot',
                    'FUNDING' => 'funding',
                    'MARGIN' => 'margin',
                    'UMFUTURE' => 'future',
                    'CMFUTURE' => 'delivery',
                    'MINING' => 'mining',
                ),
                'networks' => array(
                    'ERC20' => 'ETH',
                    'TRC20' => 'TRX',
                    'BEP2' => 'BNB',
                    'BEP20' => 'BSC',
                    'OMNI' => 'OMNI',
                    'EOS' => 'EOS',
                    'SPL' => 'SOL',
                ),
                'reverseNetworks' => array(
                    'tronscan.org' => 'TRC20',
                    'etherscan.io' => 'ERC20',
                    'bscscan.com' => 'BSC',
                    'explorer.binance.org' => 'BEP2',
                    'bithomp.com' => 'XRP',
                    'bloks.io' => 'EOS',
                    'stellar.expert' => 'XLM',
                    'blockchair.com/bitcoin' => 'BTC',
                    'blockchair.com/bitcoin-cash' => 'BCH',
                    'blockchair.com/ecash' => 'XEC',
                    'explorer.litecoin.net' => 'LTC',
                    'explorer.avax.network' => 'AVAX',
                    'solscan.io' => 'SOL',
                    'polkadot.subscan.io' => 'DOT',
                    'dashboard.internetcomputer.org' => 'ICP',
                    'explorer.chiliz.com' => 'CHZ',
                    'cardanoscan.io' => 'ADA',
                    'mainnet.theoan.com' => 'AION',
                    'algoexplorer.io' => 'ALGO',
                    'explorer.ambrosus.com' => 'AMB',
                    'viewblock.io/zilliqa' => 'ZIL',
                    'viewblock.io/arweave' => 'AR',
                    'explorer.ark.io' => 'ARK',
                    'atomscan.com' => 'ATOM',
                    'www.mintscan.io' => 'CTK',
                    'explorer.bitcoindiamond.org' => 'BCD',
                    'btgexplorer.com' => 'BTG',
                    'bts.ai' => 'BTS',
                    'explorer.celo.org' => 'CELO',
                    'explorer.nervos.org' => 'CKB',
                    'cerebro.cortexlabs.ai' => 'CTXC',
                    'chainz.cryptoid.info' => 'VIA',
                    'explorer.dcrdata.org' => 'DCR',
                    'digiexplorer.info' => 'DGB',
                    'dock.subscan.io' => 'DOCK',
                    'dogechain.info' => 'DOGE',
                    'explorer.elrond.com' => 'EGLD',
                    'blockscout.com' => 'ETC',
                    'explore-fetchhub.fetch.ai' => 'FET',
                    'filfox.info' => 'FIL',
                    'fio.bloks.io' => 'FIO',
                    'explorer.firo.org' => 'FIRO',
                    'neoscan.io' => 'NEO',
                    'ftmscan.com' => 'FTM',
                    'explorer.gochain.io' => 'GO',
                    'block.gxb.io' => 'GXS',
                    'hash-hash.info' => 'HBAR',
                    'www.hiveblockexplorer.com' => 'HIVE',
                    'explorer.helium.com' => 'HNT',
                    'tracker.icon.foundation' => 'ICX',
                    'www.iostabc.com' => 'IOST',
                    'explorer.iota.org' => 'IOTA',
                    'iotexscan.io' => 'IOTX',
                    'irishub.iobscan.io' => 'IRIS',
                    'kava.mintscan.io' => 'KAVA',
                    'scope.klaytn.com' => 'KLAY',
                    'kmdexplorer.io' => 'KMD',
                    'kusama.subscan.io' => 'KSM',
                    'explorer.lto.network' => 'LTO',
                    'polygonscan.com' => 'POLYGON',
                    'explorer.ont.io' => 'ONT',
                    'minaexplorer.com' => 'MINA',
                    'nanolooker.com' => 'NANO',
                    'explorer.nebulas.io' => 'NAS',
                    'explorer.nbs.plus' => 'NBS',
                    'explorer.nebl.io' => 'NEBL',
                    'nulscan.io' => 'NULS',
                    'nxscan.com' => 'NXS',
                    'explorer.harmony.one' => 'ONE',
                    'explorer.poa.network' => 'POA',
                    'qtum.info' => 'QTUM',
                    'explorer.rsk.co' => 'RSK',
                    'www.oasisscan.com' => 'ROSE',
                    'ravencoin.network' => 'RVN',
                    'sc.tokenview.com' => 'SC',
                    'secretnodes.com' => 'SCRT',
                    'explorer.skycoin.com' => 'SKY',
                    'steemscan.com' => 'STEEM',
                    'explorer.stacks.co' => 'STX',
                    'www.thetascan.io' => 'THETA',
                    'scan.tomochain.com' => 'TOMO',
                    'explore.vechain.org' => 'VET',
                    'explorer.vite.net' => 'VITE',
                    'www.wanscan.org' => 'WAN',
                    'wavesexplorer.com' => 'WAVES',
                    'wax.eosx.io' => 'WAXP',
                    'waltonchain.pro' => 'WTC',
                    'chain.nem.ninja' => 'XEM',
                    'verge-blockchain.info' => 'XVG',
                    'explorer.yoyow.org' => 'YOYOW',
                    'explorer.zcha.in' => 'ZEC',
                    'explorer.zensystem.io' => 'ZEN',
                ),
                'impliedNetworks' => array(
                    'ETH' => array('ERC20' => 'ETH'),
                    'TRX' => array('TRC20' => 'TRX'),
                ),
                'legalMoney' => array(
                    'MXN' => true,
                    'UGX' => true,
                    'SEK' => true,
                    'CHF' => true,
                    'VND' => true,
                    'AED' => true,
                    'DKK' => true,
                    'KZT' => true,
                    'HUF' => true,
                    'PEN' => true,
                    'PHP' => true,
                    'USD' => true,
                    'TRY' => true,
                    'EUR' => true,
                    'NGN' => true,
                    'PLN' => true,
                    'BRL' => true,
                    'ZAR' => true,
                    'KES' => true,
                    'ARS' => true,
                    'RUB' => true,
                    'AUD' => true,
                    'NOK' => true,
                    'CZK' => true,
                    'GBP' => true,
                    'UAH' => true,
                    'GHS' => true,
                    'HKD' => true,
                    'CAD' => true,
                    'INR' => true,
                    'JPY' => true,
                    'NZD' => true,
                ),
            ),

            'exceptions' => array(
                'exact' => array(
                    'System is under maintenance.' => '\\ccxt\\OnMaintenance', // array("code":1,"msg":"System is under maintenance.")
                    'System abnormality' => '\\ccxt\\ExchangeError', // array("code":-1000,"msg":"System abnormality")
                    'You are not authorized to execute this request.' => '\\ccxt\\PermissionDenied', // array("msg":"You are not authorized to execute this request.")
                    'API key does not exist' => '\\ccxt\\AuthenticationError',
                    'Order would trigger immediately.' => '\\ccxt\\OrderImmediatelyFillable',
                    'Stop price would trigger immediately.' => '\\ccxt\\OrderImmediatelyFillable', // array("code":-2010,"msg":"Stop price would trigger immediately.")
                    'Order would immediately match and take.' => '\\ccxt\\OrderImmediatelyFillable', // array("code":-2010,"msg":"Order would immediately match and take.")
                    'Account has insufficient balance for requested action.' => '\\ccxt\\InsufficientFunds',
                    'Rest API trading is not enabled.' => '\\ccxt\\ExchangeNotAvailable',
                    "You don't have permission." => '\\ccxt\\PermissionDenied', // array("msg":"You don't have permission.","success":false)
                    'Market is closed.' => '\\ccxt\\ExchangeNotAvailable', // array("code":-1013,"msg":"Market is closed.")
                    'Too many requests. Please try again later.' => '\\ccxt\\DDoSProtection', // array("msg":"Too many requests. Please try again later.","success":false)
                    'This action disabled is on this account.' => '\\ccxt\\AccountSuspended', // array("code":-2010,"msg":"This action disabled is on this account.")
                    '-1000' => '\\ccxt\\ExchangeNotAvailable', // array("code":-1000,"msg":"An unknown error occured while processing the request.")
                    '-1001' => '\\ccxt\\ExchangeNotAvailable', // array("code":-1001,"msg":"'Internal error; unable to process your request. Please try again.'")
                    '-1002' => '\\ccxt\\AuthenticationError', // array("code":-1002,"msg":"'You are not authorized to execute this request.'")
                    '-1003' => '\\ccxt\\RateLimitExceeded', // array("code":-1003,"msg":"Too much request weight used, current limit is 1200 request weight per 1 MINUTE. Please use the websocket for live updates to avoid polling the API.")
                    '-1004' => '\\ccxt\\DDoSProtection', // array("code":-1004,"msg":"Server is busy, please wait and try again")
                    '-1005' => '\\ccxt\\PermissionDenied', // array("code":-1005,"msg":"No such IP has been white listed")
                    '-1006' => '\\ccxt\\BadResponse', // array("code":-1006,"msg":"An unexpected response was received from the message bus. Execution status unknown.")
                    '-1007' => '\\ccxt\\RequestTimeout', // array("code":-1007,"msg":"Timeout waiting for response from backend server. Send status unknown; execution status unknown.")
                    '-1010' => '\\ccxt\\BadResponse', // array("code":-1010,"msg":"ERROR_MSG_RECEIVED.")
                    '-1011' => '\\ccxt\\PermissionDenied', // array("code":-1011,"msg":"This IP cannot access this route.")
                    '-1013' => '\\ccxt\\InvalidOrder', // array("code":-1013,"msg":"createOrder -> 'invalid quantity'/'invalid price'/MIN_NOTIONAL")
                    '-1014' => '\\ccxt\\InvalidOrder', // array("code":-1014,"msg":"Unsupported order combination.")
                    '-1015' => '\\ccxt\\RateLimitExceeded', // array("code":-1015,"msg":"'Too many new orders; current limit is %s orders per %s.'")
                    '-1016' => '\\ccxt\\ExchangeNotAvailable', // array("code":-1016,"msg":"'This service is no longer available.',")
                    '-1020' => '\\ccxt\\BadRequest', // array("code":-1020,"msg":"'This operation is not supported.'")
                    '-1021' => '\\ccxt\\InvalidNonce', // array("code":-1021,"msg":"'your time is ahead of server'")
                    '-1022' => '\\ccxt\\AuthenticationError', // array("code":-1022,"msg":"Signature for this request is not valid.")
                    '-1023' => '\\ccxt\\BadRequest', // array("code":-1023,"msg":"Start time is greater than end time.")
                    '-1099' => '\\ccxt\\AuthenticationError', // array("code":-1099,"msg":"Not found, authenticated, or authorized")
                    '-1100' => '\\ccxt\\BadRequest', // array("code":-1100,"msg":"createOrder(symbol, 1, asdf) -> 'Illegal characters found in parameter 'price'")
                    '-1101' => '\\ccxt\\BadRequest', // array("code":-1101,"msg":"Too many parameters; expected %s and received %s.")
                    '-1102' => '\\ccxt\\BadRequest', // array("code":-1102,"msg":"Param %s or %s must be sent, but both were empty")
                    '-1103' => '\\ccxt\\BadRequest', // array("code":-1103,"msg":"An unknown parameter was sent.")
                    '-1104' => '\\ccxt\\BadRequest', // array("code":-1104,"msg":"Not all sent parameters were read, read 8 parameters but was sent 9")
                    '-1105' => '\\ccxt\\BadRequest', // array("code":-1105,"msg":"Parameter %s was empty.")
                    '-1106' => '\\ccxt\\BadRequest', // array("code":-1106,"msg":"Parameter %s sent when not required.")
                    '-1108' => '\\ccxt\\BadRequest', // array("code":-1108,"msg":"Invalid asset.")
                    '-1109' => '\\ccxt\\AuthenticationError', // array("code":-1109,"msg":"Invalid account.")
                    '-1110' => '\\ccxt\\BadRequest', // array("code":-1110,"msg":"Invalid symbolType.")
                    '-1111' => '\\ccxt\\BadRequest', // array("code":-1111,"msg":"Precision is over the maximum defined for this asset.")
                    '-1112' => '\\ccxt\\InvalidOrder', // array("code":-1112,"msg":"No orders on book for symbol.")
                    '-1113' => '\\ccxt\\BadRequest', // array("code":-1113,"msg":"Withdrawal amount must be negative.")
                    '-1114' => '\\ccxt\\BadRequest', // array("code":-1114,"msg":"TimeInForce parameter sent when not required.")
                    '-1115' => '\\ccxt\\BadRequest', // array("code":-1115,"msg":"Invalid timeInForce.")
                    '-1116' => '\\ccxt\\BadRequest', // array("code":-1116,"msg":"Invalid orderType.")
                    '-1117' => '\\ccxt\\BadRequest', // array("code":-1117,"msg":"Invalid side.")
                    '-1118' => '\\ccxt\\BadRequest', // array("code":-1118,"msg":"New client order ID was empty.")
                    '-1119' => '\\ccxt\\BadRequest', // array("code":-1119,"msg":"Original client order ID was empty.")
                    '-1120' => '\\ccxt\\BadRequest', // array("code":-1120,"msg":"Invalid interval.")
                    '-1121' => '\\ccxt\\BadSymbol', // array("code":-1121,"msg":"Invalid symbol.")
                    '-1125' => '\\ccxt\\AuthenticationError', // array("code":-1125,"msg":"This listenKey does not exist.")
                    '-1127' => '\\ccxt\\BadRequest', // array("code":-1127,"msg":"More than %s hours between startTime and endTime.")
                    '-1128' => '\\ccxt\\BadRequest', // array("code":-1128,"msg":"array("code":-1128,"msg":"Combination of optional parameters invalid.")")
                    '-1130' => '\\ccxt\\BadRequest', // array("code":-1130,"msg":"Data sent for paramter %s is not valid.")
                    '-1131' => '\\ccxt\\BadRequest', // array("code":-1131,"msg":"recvWindow must be less than 60000")
                    '-1136' => '\\ccxt\\BadRequest', // array("code":-1136,"msg":"Invalid newOrderRespType")
                    '-2008' => '\\ccxt\\AuthenticationError', // array("code":-2008,"msg":"Invalid Api-Key ID.")
                    '-2010' => '\\ccxt\\ExchangeError', // array("code":-2010,"msg":"generic error code for createOrder -> 'Account has insufficient balance for requested action.', array("code":-2010,"msg":"Rest API trading is not enabled."), etc...")
                    '-2011' => '\\ccxt\\OrderNotFound', // array("code":-2011,"msg":"cancelOrder(1, 'BTC/USDT') -> 'UNKNOWN_ORDER'")
                    '-2013' => '\\ccxt\\OrderNotFound', // array("code":-2013,"msg":"fetchOrder (1, 'BTC/USDT') -> 'Order does not exist'")
                    '-2014' => '\\ccxt\\AuthenticationError', // array("code":-2014,"msg":"API-key format invalid.")
                    '-2015' => '\\ccxt\\AuthenticationError', // array("code":-2015,"msg":"Invalid API-key, IP, or permissions for action.")
                    '-2016' => '\\ccxt\\BadRequest', // array("code":-2016,"msg":"No trading window could be found for the symbol. Try ticker/24hrs instead.")
                    '-2018' => '\\ccxt\\InsufficientFunds', // array("code":-2018,"msg":"Balance is insufficient")
                    '-2019' => '\\ccxt\\InsufficientFunds', // array("code":-2019,"msg":"Margin is insufficient.")
                    '-2020' => '\\ccxt\\OrderNotFillable', // array("code":-2020,"msg":"Unable to fill.")
                    '-2021' => '\\ccxt\\OrderImmediatelyFillable', // array("code":-2021,"msg":"Order would immediately trigger.")
                    '-2022' => '\\ccxt\\InvalidOrder', // array("code":-2022,"msg":"ReduceOnly Order is rejected.")
                    '-2023' => '\\ccxt\\InsufficientFunds', // array(is_array(liquidation mode now.") && array_key_exists("code":-2023,"msg":"User, liquidation mode now."))
                    '-2024' => '\\ccxt\\InsufficientFunds', // array("code":-2024,"msg":"Position is not sufficient.")
                    '-2025' => '\\ccxt\\InvalidOrder', // array("code":-2025,"msg":"Reach max open order limit.")
                    '-2026' => '\\ccxt\\InvalidOrder', // array("code":-2026,"msg":"This OrderType is not supported when reduceOnly.")
                    '-2027' => '\\ccxt\\InvalidOrder', // array("code":-2027,"msg":"Exceeded the maximum allowable position at current leverage.")
                    '-2028' => '\\ccxt\\InsufficientFunds', // array("code":-2028,"msg":"Leverage is smaller than permitted => insufficient margin balance")
                    '-3000' => '\\ccxt\\ExchangeError', // array("code":-3000,"msg":"Internal server error.")
                    '-3001' => '\\ccxt\\AuthenticationError', // array("code":-3001,"msg":"Please enable 2FA first.")
                    '-3002' => '\\ccxt\\BadSymbol', // array("code":-3002,"msg":"We don't have this asset.")
                    '-3003' => '\\ccxt\\BadRequest', // array("code":-3003,"msg":"Margin account does not exist.")
                    '-3004' => '\\ccxt\\ExchangeError', // array("code":-3004,"msg":"Trade not allowed.")
                    '-3005' => '\\ccxt\\InsufficientFunds', // array("code":-3005,"msg":"Transferring out not allowed. Transfer out amount exceeds max amount.")
                    '-3006' => '\\ccxt\\InsufficientFunds', // array("code":-3006,"msg":"Your borrow amount has exceed maximum borrow amount.")
                    '-3007' => '\\ccxt\\ExchangeError', // array("code":-3007,"msg":"You have pending transaction, please try again later..")
                    '-3008' => '\\ccxt\\InsufficientFunds', // array("code":-3008,"msg":"Borrow not allowed. Your borrow amount has exceed maximum borrow amount.")
                    '-3009' => '\\ccxt\\BadRequest', // array("code":-3009,"msg":"This asset are not allowed to transfer into margin account currently.")
                    '-3010' => '\\ccxt\\ExchangeError', // array("code":-3010,"msg":"Repay not allowed. Repay amount exceeds borrow amount.")
                    '-3011' => '\\ccxt\\BadRequest', // array("code":-3011,"msg":"Your input date is invalid.")
                    '-3012' => '\\ccxt\\ExchangeError', // array("code":-3012,"msg":"Borrow is banned for this asset.")
                    '-3013' => '\\ccxt\\BadRequest', // array("code":-3013,"msg":"Borrow amount less than minimum borrow amount.")
                    '-3014' => '\\ccxt\\AccountSuspended', // array("code":-3014,"msg":"Borrow is banned for this account.")
                    '-3015' => '\\ccxt\\ExchangeError', // array("code":-3015,"msg":"Repay amount exceeds borrow amount.")
                    '-3016' => '\\ccxt\\BadRequest', // array("code":-3016,"msg":"Repay amount less than minimum repay amount.")
                    '-3017' => '\\ccxt\\ExchangeError', // array("code":-3017,"msg":"This asset are not allowed to transfer into margin account currently.")
                    '-3018' => '\\ccxt\\AccountSuspended', // array(is_array(has been banned for this account.") && array_key_exists("code":-3018,"msg":"Transferring, has been banned for this account."))
                    '-3019' => '\\ccxt\\AccountSuspended', // array("code":-3019,"msg":"Transferring out has been banned for this account.")
                    '-3020' => '\\ccxt\\InsufficientFunds', // array("code":-3020,"msg":"Transfer out amount exceeds max amount.")
                    '-3021' => '\\ccxt\\BadRequest', // array("code":-3021,"msg":"Margin account are not allowed to trade this trading pair.")
                    '-3022' => '\\ccxt\\AccountSuspended', // array("code":-3022,"msg":"You account's trading is banned.")
                    '-3023' => '\\ccxt\\BadRequest', // array("code":-3023,"msg":"You can't transfer out/place order under current margin level.")
                    '-3024' => '\\ccxt\\ExchangeError', // array("code":-3024,"msg":"The unpaid debt is too small after this repayment.")
                    '-3025' => '\\ccxt\\BadRequest', // array("code":-3025,"msg":"Your input date is invalid.")
                    '-3026' => '\\ccxt\\BadRequest', // array("code":-3026,"msg":"Your input param is invalid.")
                    '-3027' => '\\ccxt\\BadSymbol', // array("code":-3027,"msg":"Not a valid margin asset.")
                    '-3028' => '\\ccxt\\BadSymbol', // array("code":-3028,"msg":"Not a valid margin pair.")
                    '-3029' => '\\ccxt\\ExchangeError', // array("code":-3029,"msg":"Transfer failed.")
                    '-3036' => '\\ccxt\\AccountSuspended', // array("code":-3036,"msg":"This account is not allowed to repay.")
                    '-3037' => '\\ccxt\\ExchangeError', // array("code":-3037,"msg":"PNL is clearing. Wait a second.")
                    '-3038' => '\\ccxt\\BadRequest', // array("code":-3038,"msg":"Listen key not found.")
                    '-3041' => '\\ccxt\\InsufficientFunds', // array("code":-3041,"msg":"Balance is not enough")
                    '-3042' => '\\ccxt\\BadRequest', // array("code":-3042,"msg":"PriceIndex not available for this margin pair.")
                    '-3043' => '\\ccxt\\BadRequest', // array(is_array(not allowed.") && array_key_exists("code":-3043,"msg":"Transferring, not allowed."))
                    '-3044' => '\\ccxt\\DDoSProtection', // array("code":-3044,"msg":"System busy.")
                    '-3045' => '\\ccxt\\ExchangeError', // array("code":-3045,"msg":"The system doesn't have enough asset now.")
                    '-3999' => '\\ccxt\\ExchangeError', // array("code":-3999,"msg":"This function is only available for invited users.")
                    '-4001' => '\\ccxt\\BadRequest', // array("code":-4001 ,"msg":"Invalid operation.")
                    '-4002' => '\\ccxt\\BadRequest', // array("code":-4002 ,"msg":"Invalid get.")
                    '-4003' => '\\ccxt\\BadRequest', // array("code":-4003 ,"msg":"Your input email is invalid.")
                    '-4004' => '\\ccxt\\AuthenticationError', // array("code":-4004,"msg":"You don't login or auth.")
                    '-4005' => '\\ccxt\\RateLimitExceeded', // array("code":-4005 ,"msg":"Too many new requests.")
                    '-4006' => '\\ccxt\\BadRequest', // array("code":-4006 ,"msg":"Support main account only.")
                    '-4007' => '\\ccxt\\BadRequest', // array("code":-4007 ,"msg":"Address validation is not passed.")
                    '-4008' => '\\ccxt\\BadRequest', // array("code":-4008 ,"msg":"Address tag validation is not passed.")
                    '-4010' => '\\ccxt\\BadRequest', // array("code":-4010 ,"msg":"White list mail has been confirmed.") // [TODO] possible bug => it should probably be "has not been confirmed"
                    '-4011' => '\\ccxt\\BadRequest', // array("code":-4011 ,"msg":"White list mail is invalid.")
                    '-4012' => '\\ccxt\\BadRequest', // array("code":-4012 ,"msg":"White list is not opened.")
                    '-4013' => '\\ccxt\\AuthenticationError', // array("code":-4013 ,"msg":"2FA is not opened.")
                    '-4014' => '\\ccxt\\PermissionDenied', // array("code":-4014 ,"msg":"Withdraw is not allowed within 2 min login.")
                    '-4015' => '\\ccxt\\ExchangeError', // array("code":-4015 ,"msg":"Withdraw is limited.")
                    '-4016' => '\\ccxt\\PermissionDenied', // array("code":-4016 ,"msg":"Within 24 hours after password modification, withdrawal is prohibited.")
                    '-4017' => '\\ccxt\\PermissionDenied', // array("code":-4017 ,"msg":"Within 24 hours after the release of 2FA, withdrawal is prohibited.")
                    '-4018' => '\\ccxt\\BadSymbol', // array("code":-4018,"msg":"We don't have this asset.")
                    '-4019' => '\\ccxt\\BadSymbol', // array("code":-4019,"msg":"Current asset is not open for withdrawal.")
                    '-4021' => '\\ccxt\\BadRequest', // array("code":-4021,"msg":"Asset withdrawal must be an %s multiple of %s.")
                    '-4022' => '\\ccxt\\BadRequest', // array("code":-4022,"msg":"Not less than the minimum pick-up quantity %s.")
                    '-4023' => '\\ccxt\\ExchangeError', // array("code":-4023,"msg":"Within 24 hours, the withdrawal exceeds the maximum amount.")
                    '-4024' => '\\ccxt\\InsufficientFunds', // array("code":-4024,"msg":"You don't have this asset.")
                    '-4025' => '\\ccxt\\InsufficientFunds', // array("code":-4025,"msg":"The number of hold asset is less than zero.")
                    '-4026' => '\\ccxt\\InsufficientFunds', // array("code":-4026,"msg":"You have insufficient balance.")
                    '-4027' => '\\ccxt\\ExchangeError', // array("code":-4027,"msg":"Failed to obtain tranId.")
                    '-4028' => '\\ccxt\\BadRequest', // array("code":-4028,"msg":"The amount of withdrawal must be greater than the Commission.")
                    '-4029' => '\\ccxt\\BadRequest', // array("code":-4029,"msg":"The withdrawal record does not exist.")
                    '-4030' => '\\ccxt\\ExchangeError', // array("code":-4030,"msg":"Confirmation of successful asset withdrawal. [TODO] possible bug in docs")
                    '-4031' => '\\ccxt\\ExchangeError', // array("code":-4031,"msg":"Cancellation failed.")
                    '-4032' => '\\ccxt\\ExchangeError', // array("code":-4032,"msg":"Withdraw verification exception.")
                    '-4033' => '\\ccxt\\BadRequest', // array("code":-4033,"msg":"Illegal address.")
                    '-4034' => '\\ccxt\\ExchangeError', // array("code":-4034,"msg":"The address is suspected of fake.")
                    '-4035' => '\\ccxt\\PermissionDenied', // array("code":-4035,"msg":"This address is not on the whitelist. Please join and try again.")
                    '-4036' => '\\ccxt\\BadRequest', // array("code":-4036,"msg":"The new address needs to be withdrawn in {0} hours.")
                    '-4037' => '\\ccxt\\ExchangeError', // array("code":-4037,"msg":"Re-sending Mail failed.")
                    '-4038' => '\\ccxt\\ExchangeError', // array("code":-4038,"msg":"Please try again in 5 minutes.")
                    '-4039' => '\\ccxt\\BadRequest', // array("code":-4039,"msg":"The user does not exist.")
                    '-4040' => '\\ccxt\\BadRequest', // array("code":-4040,"msg":"This address not charged.")
                    '-4041' => '\\ccxt\\ExchangeError', // array("code":-4041,"msg":"Please try again in one minute.")
                    '-4042' => '\\ccxt\\ExchangeError', // array("code":-4042,"msg":"This asset cannot get deposit address again.")
                    '-4043' => '\\ccxt\\BadRequest', // array("code":-4043,"msg":"More than 100 recharge addresses were used in 24 hours.")
                    '-4044' => '\\ccxt\\BadRequest', // array("code":-4044,"msg":"This is a blacklist country.")
                    '-4045' => '\\ccxt\\ExchangeError', // array("code":-4045,"msg":"Failure to acquire assets.")
                    '-4046' => '\\ccxt\\AuthenticationError', // array("code":-4046,"msg":"Agreement not confirmed.")
                    '-4047' => '\\ccxt\\BadRequest', // array("code":-4047,"msg":"Time interval must be within 0-90 days")
                    '-5001' => '\\ccxt\\BadRequest', // array("code":-5001,"msg":"Don't allow transfer to micro assets.")
                    '-5002' => '\\ccxt\\InsufficientFunds', // array("code":-5002,"msg":"You have insufficient balance.")
                    '-5003' => '\\ccxt\\InsufficientFunds', // array("code":-5003,"msg":"You don't have this asset.")
                    '-5004' => '\\ccxt\\BadRequest', // array("code":-5004,"msg":"The residual balances of %s have exceeded 0.001BTC, Please re-choose.")
                    '-5005' => '\\ccxt\\InsufficientFunds', // array("code":-5005,"msg":"The residual balances of %s is too low, Please re-choose.")
                    '-5006' => '\\ccxt\\BadRequest', // array("code":-5006,"msg":"Only transfer once in 24 hours.")
                    '-5007' => '\\ccxt\\BadRequest', // array("code":-5007,"msg":"Quantity must be greater than zero.")
                    '-5008' => '\\ccxt\\InsufficientFunds', // array("code":-5008,"msg":"Insufficient amount of returnable assets.")
                    '-5009' => '\\ccxt\\BadRequest', // array("code":-5009,"msg":"Product does not exist.")
                    '-5010' => '\\ccxt\\ExchangeError', // array("code":-5010,"msg":"Asset transfer fail.")
                    '-5011' => '\\ccxt\\BadRequest', // array("code":-5011,"msg":"future account not exists.")
                    '-5012' => '\\ccxt\\ExchangeError', // array("code":-5012,"msg":"Asset transfer is in pending.")
                    '-5013' => '\\ccxt\\InsufficientFunds', // array("code":-5013,"msg":"Asset transfer failed => insufficient balance"") // undocumented
                    '-5021' => '\\ccxt\\BadRequest', // array("code":-5021,"msg":"This parent sub have no relation")
                    '-6001' => '\\ccxt\\BadRequest', // array("code":-6001,"msg":"Daily product not exists.")
                    '-6003' => '\\ccxt\\BadRequest', // array("code":-6003,"msg":"Product not exist or you don't have permission")
                    '-6004' => '\\ccxt\\ExchangeError', // array("code":-6004,"msg":"Product not in purchase status")
                    '-6005' => '\\ccxt\\InvalidOrder', // array("code":-6005,"msg":"Smaller than min purchase limit")
                    '-6006' => '\\ccxt\\BadRequest', // array("code":-6006,"msg":"Redeem amount error")
                    '-6007' => '\\ccxt\\BadRequest', // array(is_array(redeem time") && array_key_exists("code":-6007,"msg":"Not, redeem time"))
                    '-6008' => '\\ccxt\\BadRequest', // array("code":-6008,"msg":"Product not in redeem status")
                    '-6009' => '\\ccxt\\RateLimitExceeded', // array("code":-6009,"msg":"Request frequency too high")
                    '-6011' => '\\ccxt\\BadRequest', // array("code":-6011,"msg":"Exceeding the maximum num allowed to purchase per user")
                    '-6012' => '\\ccxt\\InsufficientFunds', // array("code":-6012,"msg":"Balance not enough")
                    '-6013' => '\\ccxt\\ExchangeError', // array("code":-6013,"msg":"Purchasing failed")
                    '-6014' => '\\ccxt\\BadRequest', // array("code":-6014,"msg":"Exceed up-limit allowed to purchased")
                    '-6015' => '\\ccxt\\BadRequest', // array("code":-6015,"msg":"Empty request body")
                    '-6016' => '\\ccxt\\BadRequest', // array("code":-6016,"msg":"Parameter err")
                    '-6017' => '\\ccxt\\BadRequest', // array(is_array(whitelist") && array_key_exists("code":-6017,"msg":"Not, whitelist"))
                    '-6018' => '\\ccxt\\BadRequest', // array("code":-6018,"msg":"Asset not enough")
                    '-6019' => '\\ccxt\\AuthenticationError', // array("code":-6019,"msg":"Need confirm")
                    '-6020' => '\\ccxt\\BadRequest', // array("code":-6020,"msg":"Project not exists")
                    '-7001' => '\\ccxt\\BadRequest', // array("code":-7001,"msg":"Date range is not supported.")
                    '-7002' => '\\ccxt\\BadRequest', // array("code":-7002,"msg":"Data request type is not supported.")
                    '-9000' => '\\ccxt\\InsufficientFunds', // array("code":-9000,"msg":"user have no avaliable amount")"
                    '-10017' => '\\ccxt\\BadRequest', // array("code":-10017,"msg":"Repay amount should not be larger than liability.")
                    '-11008' => '\\ccxt\\InsufficientFunds', // array("code":-11008,"msg":"Exceeding the account's maximum borrowable limit.") // undocumented
                    '-12014' => '\\ccxt\\RateLimitExceeded', // array("code":-12014,"msg":"More than 1 request in 3 seconds")
                    '-13000' => '\\ccxt\\BadRequest', // array("code":-13000,"msg":"Redeption of the token is forbiden now")
                    '-13001' => '\\ccxt\\BadRequest', // array("code":-13001,"msg":"Exceeds individual 24h redemption limit of the token")
                    '-13002' => '\\ccxt\\BadRequest', // array("code":-13002,"msg":"Exceeds total 24h redemption limit of the token")
                    '-13003' => '\\ccxt\\BadRequest', // array("code":-13003,"msg":"Subscription of the token is forbiden now")
                    '-13004' => '\\ccxt\\BadRequest', // array("code":-13004,"msg":"Exceeds individual 24h subscription limit of the token")
                    '-13005' => '\\ccxt\\BadRequest', // array("code":-13005,"msg":"Exceeds total 24h subscription limit of the token")
                    '-13006' => '\\ccxt\\InvalidOrder', // array("code":-13006,"msg":"Subscription amount is too small")
                    '-13007' => '\\ccxt\\AuthenticationError', // array("code":-13007,"msg":"The Agreement is not signed")
                    '-21001' => '\\ccxt\\BadRequest', // array("code":-21001,"msg":"USER_IS_NOT_UNIACCOUNT")
                    '-21002' => '\\ccxt\\BadRequest', // array("code":-21002,"msg":"UNI_ACCOUNT_CANT_TRANSFER_FUTURE")
                    '-21003' => '\\ccxt\\BadRequest', // array("code":-21003,"msg":"NET_ASSET_MUST_LTE_RATIO")
                    '100001003' => '\\ccxt\\BadRequest', // array("code":100001003,"msg":"Verification failed") // undocumented
                ),
                'broad' => array(
                    'has no operation privilege' => '\\ccxt\\PermissionDenied',
                    'MAX_POSITION' => '\\ccxt\\InvalidOrder', // array("code":-2010,"msg":"Filter failure => MAX_POSITION")
                ),
            ),
        ));
    }

    public function cost_to_precision($symbol, $cost)
    {
        return $this->decimal_to_precision($cost, $this->markets[$symbol]['precision']['quote'], $this->precisionMode, $this->paddingMode);
    }

    public function currency_to_precision($code, $fee, $networkCode = null)
    {

        if ($this->safe_value($this->currencies[$code], 'precision') !== null) {
            return $this->decimal_to_precision($fee, $this->currencies[$code]['precision'], $this->precisionMode, $this->paddingMode);
        } else {
            return $this->number_to_string($fee);
        }
    }

    public function nonce()
    {
        return $this->milliseconds() - $this->options['timeDifference'];
    }

    public function fetch_time($params = array())
    {

        $defaultType = $this->safe_string_2($this->options, 'fetchTime', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        $method = 'publicGetTime';
        if ($type === 'future') {
            $method = 'fapiPublicGetTime';
        } elseif ($type === 'delivery') {
            $method = 'dapiPublicGetTime';
        }
        $response = $this->$method($query);
        return $this->safe_integer($response, 'serverTime');
    }

    public function fetch_currencies($params = array())
    {

        $fetchCurrenciesEnabled = $this->safe_value($this->options, 'fetchCurrencies');
        if (!$fetchCurrenciesEnabled) {
            return null;
        }
        if (!$this->check_required_credentials(false)) {
            return null;
        }
        // sandbox/testnet does not support sapi endpoints
        $apiBackup = $this->safe_string($this->urls, 'apiBackup');
        if ($apiBackup !== null) {
            return null;
        }
        $response = $this->sapiGetCapitalConfigGetall($params);
        $result = array();
        for ($i = 0; $i < count($response); $i++) {

            $entry = $response[$i];
            $id = $this->safe_string($entry, 'coin');
            $name = $this->safe_string($entry, 'name');
            $code = $this->safe_currency_code($id);
            $precision = null;
            $isWithdrawEnabled = true;
            $isDepositEnabled = true;
            $networkList = $this->safe_value($entry, 'networkList', array());
            $fees = array();
            $fee = null;
            for ($j = 0; $j < count($networkList); $j++) {
                $networkItem = $networkList[$j];
                $network = $this->safe_string($networkItem, 'network');
                // $name = $this->safe_string($networkItem, 'name');
                $withdrawFee = $this->safe_number($networkItem, 'withdrawFee');
                $depositEnable = $this->safe_value($networkItem, 'depositEnable');
                $withdrawEnable = $this->safe_value($networkItem, 'withdrawEnable');
                $isDepositEnabled = $isDepositEnabled || $depositEnable;
                $isWithdrawEnabled = $isWithdrawEnabled || $withdrawEnable;
                $fees[$network] = $withdrawFee;
                $isDefault = $this->safe_value($networkItem, 'isDefault');
                if ($isDefault || ($fee === null)) {
                    $fee = $withdrawFee;
                }
            }
            $trading = $this->safe_value($entry, 'trading');
            $active = ($isWithdrawEnabled && $isDepositEnabled && $trading);
            $result[$code] = array(
                'id' => $id,
                'name' => $name,
                'code' => $code,
                'precision' => $precision,
                'info' => $entry,
                'active' => $active,
                'deposit' => $isDepositEnabled,
                'withdraw' => $isWithdrawEnabled,
                'networks' => $networkList,
                'fee' => $fee,
                'fees' => $fees,
                'limits' => $this->limits,
            );
        }
        return $result;
    }

    public function fetch_markets($params = array())
    {

        $defaultType = $this->safe_string_2($this->options, 'fetchMarkets', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        $spot = ($type === 'spot');
        $margin = ($type === 'margin');
        $future = ($type === 'future');
        $delivery = ($type === 'delivery');
        if ((!$spot) && (!$margin) && (!$future) && (!$delivery)) {
            throw new ExchangeError($this->id . " does not support '" . $type . "' $type, set exchange.options['defaultType'] to 'spot', 'margin', 'delivery' or 'future'"); // eslint-disable-line quotes
        }
        $method = 'publicGetExchangeInfo';
        if ($future) {
            $method = 'fapiPublicGetExchangeInfo';
        } elseif ($delivery) {
            $method = 'dapiPublicGetExchangeInfo';
        }
        $response = $this->$method($query);

        if ($this->options['adjustForTimeDifference']) {
            $this->load_time_difference();
        }
        $markets = $this->safe_value($response, 'symbols', array());
        $settleCurrencies = $this->safe_value($this->options, 'settle', array());
        $result = array();
        for ($i = 0; $i < count($markets); $i++) {
            $market = $markets[$i];
            $id = $this->safe_string($market, 'symbol');
            $lowercaseId = $this->safe_string_lower($market, 'symbol');
            $baseId = $this->safe_string($market, 'baseAsset');
            $quoteId = $this->safe_string($market, 'quoteAsset');
            $settleId = $this->safe_string($market, 'marginAsset');
            $base = $this->safe_currency_code($baseId);
            $quote = $this->safe_currency_code($quoteId);
            $settle = $this->safe_currency_code($settleId);
            $contract = $future || $delivery;
            $contractType = $this->safe_string($market, 'contractType');
            $idSymbol = $contract && ($contractType !== 'PERPETUAL');
            $symbol = null;
            $expiry = null;
            if ($idSymbol) {
                $symbol = $id;
                $expiry = $this->safe_integer($market, 'deliveryDate');
            } else {
                $symbol = $base . '/' . $quote;
            }
            $filters = $this->safe_value($market, 'filters', array());
            $filtersByType = $this->index_by($filters, 'filterType');
            $status = $this->safe_string_2($market, 'status', 'contractStatus');
            $contractSize = null;
            $fees = $this->fees;
            if ($contract) {
                $contractSize = $this->safe_number($market, 'contractSize', $this->parse_number('1'));
                $fees = $this->fees[$type];
            }
            $active = ($status === 'TRADING');
            if ($spot) {
                $permissions = $this->safe_value($market, 'permissions', array());
                for ($j = 0; $j < count($permissions); $j++) {
                    if ($permissions[$j] === 'TRD_GRP_003') {
                        $active = false;
                        break;
                    }
                }
            }
            $isMarginTradingAllowed = $this->safe_value($market, 'isMarginTradingAllowed', false);
            $linearOrInverse = $this->safe_string($settleCurrencies, $settle, 'inverse');
            $linear = ($linearOrInverse === 'linear');
            $inverse = ($linearOrInverse === 'inverse');
            $entry = array(
                'id' => $id,
                'lowercaseId' => $lowercaseId,
                'symbol' => $symbol,
                'base' => $base,
                'quote' => $quote,
                'settle' => $settle,
                'baseId' => $baseId,
                'quoteId' => $quoteId,
                'settleId' => $settleId,
                'type' => $type,
                'spot' => $spot,
                'margin' => $spot && $isMarginTradingAllowed,
                'swap' => $future,
                'future' => $future,
                'delivery' => $delivery,
                'option' => false,
                'active' => $active,
                'contract' => $contract,
                'linear' => $contract ? $linear : null,
                'inverse' => $contract ? $inverse : null,
                'taker' => $fees['trading']['taker'],
                'maker' => $fees['trading']['maker'],
                'contractSize' => $contractSize,
                'expiry' => $expiry,
                'expiryDatetime' => $this->iso8601($expiry),
                'strike' => null,
                'optionType' => null,
                'precision' => array(
                    'amount' => $this->safe_integer($market, 'quantityPrecision'),
                    'price' => $this->safe_integer($market, 'pricePrecision'),
                    'base' => $this->safe_integer($market, 'baseAssetPrecision'),
                    'quote' => $this->safe_integer($market, 'quotePrecision'),
                ),
                'limits' => array(
                    'leverage' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'amount' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'price' => array(
                        'min' => null,
                        'max' => null,
                    ),
                    'cost' => array(
                        'min' => null,
                        'max' => null,
                    ),
                ),
                'info' => $market,
            );
            if (is_array($filtersByType) && array_key_exists('PRICE_FILTER', $filtersByType)) {
                $filter = $this->safe_value($filtersByType, 'PRICE_FILTER', array());
                $tickSize = $this->safe_string($filter, 'tickSize');
                $entry['precision']['price'] = $this->precision_from_string($tickSize);

                $entry['limits']['price'] = array(
                    'min' => $this->safe_number($filter, 'minPrice'),
                    'max' => $this->safe_number($filter, 'maxPrice'),
                );
                $entry['precision']['price'] = $this->precision_from_string($filter['tickSize']);
            }
            if (is_array($filtersByType) && array_key_exists('LOT_SIZE', $filtersByType)) {
                $filter = $this->safe_value($filtersByType, 'LOT_SIZE', array());
                $stepSize = $this->safe_string($filter, 'stepSize');
                $entry['precision']['amount'] = $this->precision_from_string($stepSize);
                $entry['limits']['amount'] = array(
                    'min' => $this->safe_number($filter, 'minQty'),
                    'max' => $this->safe_number($filter, 'maxQty'),
                );
            }
            if (is_array($filtersByType) && array_key_exists('MARKET_LOT_SIZE', $filtersByType)) {
                $filter = $this->safe_value($filtersByType, 'MARKET_LOT_SIZE', array());
                $entry['limits']['market'] = array(
                    'min' => $this->safe_number($filter, 'minQty'),
                    'max' => $this->safe_number($filter, 'maxQty'),
                );
            }
            if (is_array($filtersByType) && array_key_exists('MIN_NOTIONAL', $filtersByType)) {
                $filter = $this->safe_value($filtersByType, 'MIN_NOTIONAL', array());
                $entry['limits']['cost']['min'] = $this->safe_number_2($filter, 'minNotional', 'notional');
            }
            $result[] = $entry;
        }
        return $result;
    }

    public function parse_balance($response, $type = null)
    {
        $result = array(
            'info' => $response,
        );
        $timestamp = null;
        if (($type === 'spot') || ($type === 'margin')) {
            $timestamp = $this->safe_integer($response, 'updateTime');
            $balances = $this->safe_value_2($response, 'balances', 'userAssets', array());
            for ($i = 0; $i < count($balances); $i++) {
                $balance = $balances[$i];
                $currencyId = $this->safe_string($balance, 'asset');
                $code = $this->safe_currency_code($currencyId);
                $account = $this->account();
                $account['free'] = $this->safe_string($balance, 'free');
                $account['used'] = $this->safe_string($balance, 'locked');
                $result[$code] = $account;
            }
        } elseif ($type === 'savings') {
            $positionAmountVos = $this->safe_value($response, 'positionAmountVos', array());
            for ($i = 0; $i < count($positionAmountVos); $i++) {
                $entry = $positionAmountVos[$i];
                $currencyId = $this->safe_string($entry, 'asset');
                $code = $this->safe_currency_code($currencyId);
                $account = $this->account();
                $usedAndTotal = $this->safe_string($entry, 'amount');
                $account['total'] = $usedAndTotal;
                $account['used'] = $usedAndTotal;
                $result[$code] = $account;
            }
        } elseif ($type === 'funding') {
            for ($i = 0; $i < count($response); $i++) {
                $entry = $response[$i];
                $account = $this->account();
                $currencyId = $this->safe_string($entry, 'asset');
                $code = $this->safe_currency_code($currencyId);
                $account['free'] = $this->safe_string($entry, 'free');
                $frozen = $this->safe_string($entry, 'freeze');
                $withdrawing = $this->safe_string($entry, 'withdrawing');
                $locked = $this->safe_string($entry, 'locked');
                $account['used'] = Precise::string_add($frozen, Precise::string_add($locked, $withdrawing));
                $result[$code] = $account;
            }
        } else {
            $balances = $response;
            if (gettype($response) !== 'array' || array_keys($response) !== array_keys(array_keys($response))) {
                $balances = $this->safe_value($response, 'assets', array());
            }
            for ($i = 0; $i < count($balances); $i++) {
                $balance = $balances[$i];
                $currencyId = $this->safe_string($balance, 'asset');
                $code = $this->safe_currency_code($currencyId);
                $account = $this->account();
                $account['free'] = $this->safe_string($balance, 'availableBalance');
                $account['used'] = $this->safe_string($balance, 'initialMargin');
                $account['total'] = $this->safe_string_2($balance, 'marginBalance', 'balance');
                $result[$code] = $account;
            }
        }
        $result['timestamp'] = $timestamp;
        $result['datetime'] = $this->iso8601($timestamp);
        return $this->safe_balance($result);
    }

    public function fetch_balance($params = array())
    {

        $this->load_markets();
        $defaultType = $this->safe_string_2($this->options, 'fetchBalance', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $method = 'privateGetAccount';
        if ($type === 'future') {
            $options = $this->safe_value($this->options, $type, array());
            $fetchBalanceOptions = $this->safe_value($options, 'fetchBalance', array());
            $method = $this->safe_string($fetchBalanceOptions, 'method', 'fapiPrivateV2GetAccount');
        } elseif ($type === 'delivery') {
            $options = $this->safe_value($this->options, $type, array());
            $fetchBalanceOptions = $this->safe_value($options, 'fetchBalance', array());
            $method = $this->safe_string($fetchBalanceOptions, 'method', 'dapiPrivateGetAccount');
        } elseif ($type === 'margin') {
            $method = 'sapiGetMarginAccount';
        } elseif ($type === 'savings') {
            $method = 'sapiGetLendingUnionAccount';
        } elseif ($type === 'funding') {
            $method = 'sapiPostAssetGetFundingAsset';
        }
        $query = $this->omit($params, 'type');
        $response = $this->$method($query);

        return $this->parse_balance($response, $type);
    }

    public function fetch_order_book($symbol, $limit = null, $params = array())
    {

        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'symbol' => $market['id'],
        );
        if ($limit !== null) {
            $request['limit'] = $limit; // default 100, max 5000, see https://github.com/binance/binance-spot-api-docs/blob/master/rest-api.md#order-book
        }
        $method = 'publicGetDepth';
        if ($market['linear']) {
            $method = 'fapiPublicGetDepth';
        } elseif ($market['inverse']) {
            $method = 'dapiPublicGetDepth';
        }
        $response = $this->$method(array_merge($request, $params));

        $timestamp = $this->safe_integer($response, 'T');
        $orderbook = $this->parse_order_book($response, $symbol, $timestamp);
        $orderbook['nonce'] = $this->safe_integer($response, 'lastUpdateId');
        return $orderbook;
    }

    public function parse_ticker($ticker, $market = null)
    {

        $timestamp = $this->safe_integer($ticker, 'closeTime');
        $marketId = $this->safe_string($ticker, 'symbol');
        $symbol = $this->safe_symbol($marketId, $market);
        $last = $this->safe_string($ticker, 'lastPrice');
        $isCoinm = (is_array($ticker) && array_key_exists('baseVolume', $ticker));
        $baseVolume = null;
        $quoteVolume = null;
        if ($isCoinm) {
            $baseVolume = $this->safe_string($ticker, 'baseVolume');
            $quoteVolume = $this->safe_string($ticker, 'volume');
        } else {
            $baseVolume = $this->safe_string($ticker, 'volume');
            $quoteVolume = $this->safe_string($ticker, 'quoteVolume');
        }
        return $this->safe_ticker(array(
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'high' => $this->safe_string($ticker, 'highPrice'),
            'low' => $this->safe_string($ticker, 'lowPrice'),
            'bid' => $this->safe_string($ticker, 'bidPrice'),
            'bidVolume' => $this->safe_string($ticker, 'bidQty'),
            'ask' => $this->safe_string($ticker, 'askPrice'),
            'askVolume' => $this->safe_string($ticker, 'askQty'),
            'vwap' => $this->safe_string($ticker, 'weightedAvgPrice'),
            'open' => $this->safe_string($ticker, 'openPrice'),
            'close' => $last,
            'last' => $last,
            'previousClose' => $this->safe_string($ticker, 'prevClosePrice'), // previous day close
            'change' => $this->safe_string($ticker, 'priceChange'),
            'percentage' => $this->safe_string($ticker, 'priceChangePercent'),
            'average' => null,
            'baseVolume' => $baseVolume,
            'quoteVolume' => $quoteVolume,
            'info' => $ticker,
        ), $market);
    }

    public function fetch_status($params = array())
    {

        $response = $this->sapiGetSystemStatus($params);

        $statusRaw = $this->safe_string($response, 'status');
        return array(
            'status' => $this->safe_string(array('0' => 'ok', '1' => 'maintenance'), $statusRaw, $statusRaw),
            'updated' => null,
            'eta' => null,
            'url' => null,
            'info' => $response,
        );
    }

    public function fetch_ticker($symbol, $params = array())
    {

        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'symbol' => $market['id'],
        );
        $method = 'publicGetTicker24hr';
        if ($market['linear']) {
            $method = 'fapiPublicGetTicker24hr';
        } elseif ($market['inverse']) {
            $method = 'dapiPublicGetTicker24hr';
        }
        $response = $this->$method(array_merge($request, $params));
        if (gettype($response) === 'array' && array_keys($response) === array_keys(array_keys($response))) {
            $firstTicker = $this->safe_value($response, 0, array());
            return $this->parse_ticker($firstTicker, $market);
        }
        return $this->parse_ticker($response, $market);
    }

    public function fetch_bids_asks($symbols = null, $params = array())
    {

        $this->load_markets();
        $defaultType = $this->safe_string_2($this->options, 'fetchBidsAsks', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        $method = null;
        if ($type === 'future') {
            $method = 'fapiPublicGetTickerBookTicker';
        } elseif ($type === 'delivery') {
            $method = 'dapiPublicGetTickerBookTicker';
        } else {
            $method = 'publicGetTickerBookTicker';
        }
        $response = $this->$method($query);
        return $this->parse_tickers($response, $symbols);
    }

    public function fetch_tickers($symbols = null, $params = array())
    {

        $this->load_markets();
        $defaultType = $this->safe_string_2($this->options, 'fetchTickers', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        $defaultMethod = null;
        if ($type === 'future') {
            $defaultMethod = 'fapiPublicGetTicker24hr';
        } elseif ($type === 'delivery') {
            $defaultMethod = 'dapiPublicGetTicker24hr';
        } else {
            $defaultMethod = 'publicGetTicker24hr';
        }
        $method = $this->safe_string($this->options, 'fetchTickersMethod', $defaultMethod);
        $response = $this->$method($query);
        return $this->parse_tickers($response, $symbols);
    }

    public function parse_ohlcv($ohlcv, $market = null)
    {

        return array(
            $this->safe_integer($ohlcv, 0),
            $this->safe_number($ohlcv, 1),
            $this->safe_number($ohlcv, 2),
            $this->safe_number($ohlcv, 3),
            $this->safe_number($ohlcv, 4),
            $this->safe_number($ohlcv, 5),
        );
    }

    public function fetch_ohlcv($symbol, $timeframe = '1m', $since = null, $limit = null, $params = array())
    {

        $this->load_markets();
        $market = $this->market($symbol);
        $defaultLimit = 500;
        $maxLimit = 1500;
        $price = $this->safe_string($params, 'price');
        $until = $this->safe_integer($params, 'until');
        $params = $this->omit($params, array('price', 'until'));
        $limit = ($limit === null) ? $defaultLimit : min($limit, $maxLimit);
        $request = array(
            'interval' => $this->timeframes[$timeframe],
            'limit' => $limit,
        );
        if ($price === 'index') {
            $request['pair'] = $market['id'];   // Index $price takes this argument instead of $symbol
        } else {
            $request['symbol'] = $market['id'];
        }

        if ($since !== null) {
            $request['startTime'] = $since;

            if ($market['inverse']) {
                if ($since > 0) {
                    $duration = $this->parse_timeframe($timeframe);
                    $endTime = $this->sum($since, $limit * $duration * 1000 - 1);
                    $now = $this->milliseconds();
                    $request['endTime'] = min($now, $endTime);
                }
            }
        }
        if ($until !== null) {
            $request['endTime'] = $until;
        }
        $method = 'publicGetKlines';
        if ($price === 'mark') {
            if ($market['inverse']) {
                $method = 'dapiPublicGetMarkPriceKlines';
            } else {
                $method = 'fapiPublicGetMarkPriceKlines';
            }
        } elseif ($price === 'index') {
            if ($market['inverse']) {
                $method = 'dapiPublicGetIndexPriceKlines';
            } else {
                $method = 'fapiPublicGetIndexPriceKlines';
            }
        } elseif ($market['linear']) {
            $method = 'fapiPublicGetKlines';
        } elseif ($market['inverse']) {
            $method = 'dapiPublicGetKlines';
        }
        $response = $this->$method(array_merge($request, $params));

        return $this->parse_ohlcvs($response, $market, $timeframe, $since, $limit);
    }

    public function parse_trade($trade, $market = null)
    {
        if (is_array($trade) && array_key_exists('isDustTrade', $trade)) {
            return $this->parse_dust_trade($trade, $market);
        }
        $timestamp = $this->safe_integer_2($trade, 'T', 'time');
        $price = $this->safe_string_2($trade, 'p', 'price');
        $amount = $this->safe_string_2($trade, 'q', 'qty');
        $cost = $this->safe_string_2($trade, 'quoteQty', 'baseQty');  // inverse futures
        $marketId = $this->safe_string($trade, 'symbol');
        $symbol = $this->safe_symbol($marketId, $market);
        $id = $this->safe_string_2($trade, 't', 'a');
        $id = $this->safe_string_2($trade, 'id', 'tradeId', $id);
        $side = null;
        $orderId = $this->safe_string($trade, 'orderId');
        $buyerMaker = $this->safe_value_2($trade, 'm', 'isBuyerMaker');
        $takerOrMaker = null;
        if ($buyerMaker !== null) {
            $side = $buyerMaker ? 'sell' : 'buy'; // this is reversed intentionally
            $takerOrMaker = 'taker';
        } elseif (is_array($trade) && array_key_exists('side', $trade)) {
            $side = $this->safe_string_lower($trade, 'side');
        } else {
            if (is_array($trade) && array_key_exists('isBuyer', $trade)) {
                $side = $trade['isBuyer'] ? 'buy' : 'sell'; // this is a true $side
            }
        }
        $fee = null;
        if (is_array($trade) && array_key_exists('commission', $trade)) {
            $fee = array(
                'cost' => $this->safe_string($trade, 'commission'),
                'currency' => $this->safe_currency_code($this->safe_string($trade, 'commissionAsset')),
            );
        }
        if (is_array($trade) && array_key_exists('isMaker', $trade)) {
            $takerOrMaker = $trade['isMaker'] ? 'maker' : 'taker';
        }
        if (is_array($trade) && array_key_exists('maker', $trade)) {
            $takerOrMaker = $trade['maker'] ? 'maker' : 'taker';
        }
        return $this->safe_trade(array(
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $symbol,
            'id' => $id,
            'order' => $orderId,
            'type' => null,
            'side' => $side,
            'takerOrMaker' => $takerOrMaker,
            'price' => $price,
            'amount' => $amount,
            'cost' => $cost,
            'fee' => $fee,
        ), $market);
    }

    public function fetch_trades($symbol, $since = null, $limit = null, $params = array())
    {
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'symbol' => $market['id'],
        );
        list($type, $query) = $this->handle_market_type_and_params('fetchTrades', $market, $params);
        $defaultMethod = null;
        if ($type === 'future') {
            if ($market['linear']) {
                $defaultMethod = 'fapiPublicGetAggTrades';
            } elseif ($market['inverse']) {
                $defaultMethod = 'dapiPublicGetAggTrades';
            }
        } elseif ($type === 'delivery') {
            $defaultMethod = 'dapiPublicGetAggTrades';
        } else {
            $defaultMethod = 'publicGetAggTrades';
        }
        $method = $this->safe_string($this->options, 'fetchTradesMethod', $defaultMethod);
        if ($method === 'publicGetAggTrades') {
            if ($type === 'future') {
                if ($market['linear']) {
                    $method = 'fapiPublicGetAggTrades';
                } elseif ($market['inverse']) {
                    $method = 'dapiPublicGetAggTrades';
                }
            } elseif ($type === 'delivery') {
                $method = 'dapiPublicGetAggTrades';
            }
        } elseif ($method === 'publicGetHistoricalTrades') {
            if ($type === 'future') {
                if ($market['linear']) {
                    $method = 'fapiPublicGetHistoricalTrades';
                } elseif ($market['inverse']) {
                    $method = 'dapiPublicGetHistoricalTrades';
                }
            } elseif ($type === 'delivery') {
                $method = 'dapiPublicGetHistoricalTrades';
            }
        }
        if ($since !== null) {
            $request['startTime'] = $since;
            $request['endTime'] = $this->sum($since, 3600000);
        }
        if ($limit !== null) {
            $request['limit'] = $limit; // default = 500, maximum = 1000
        }
        $response = $this->$method(array_merge($request, $query));

        return $this->parse_trades($response, $market, $since, $limit);
    }

    public function parse_order_status($status)
    {
        $statuses = array(
            'NEW' => 'open',
            'PARTIALLY_FILLED' => 'open',
            'FILLED' => 'closed',
            'CANCELED' => 'canceled',
            'PENDING_CANCEL' => 'canceling', // currently unused
            'REJECTED' => 'rejected',
            'EXPIRED' => 'expired',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_order($order, $market = null)
    {

        $status = $this->parse_order_status($this->safe_string($order, 'status'));
        $marketId = $this->safe_string($order, 'symbol');
        $symbol = $this->safe_symbol($marketId, $market);
        $filled = $this->safe_string($order, 'executedQty', '0');
        $timestamp = null;
        $lastTradeTimestamp = null;
        if (is_array($order) && array_key_exists('time', $order)) {
            $timestamp = $this->safe_integer($order, 'time');
        } elseif (is_array($order) && array_key_exists('transactTime', $order)) {
            $timestamp = $this->safe_integer($order, 'transactTime');
        } elseif (is_array($order) && array_key_exists('updateTime', $order)) {
            if ($status === 'open') {
                if (Precise::string_gt($filled, '0')) {
                    $lastTradeTimestamp = $this->safe_integer($order, 'updateTime');
                } else {
                    $timestamp = $this->safe_integer($order, 'updateTime');
                }
            }
        }
        $average = $this->safe_string($order, 'avgPrice');
        $price = $this->safe_string($order, 'price');
        $amount = $this->safe_string($order, 'origQty');

        $cost = $this->safe_string_2($order, 'cummulativeQuoteQty', 'cumQuote');
        $cost = $this->safe_string($order, 'cumBase', $cost);
        $id = $this->safe_string($order, 'orderId');
        $type = $this->safe_string_lower($order, 'type');
        $side = $this->safe_string_lower($order, 'side');
        $fills = $this->safe_value($order, 'fills', array());
        $clientOrderId = $this->safe_string($order, 'clientOrderId');
        $timeInForce = $this->safe_string($order, 'timeInForce');
        if ($timeInForce === 'GTX') {

            $timeInForce = 'PO';
        }
        $postOnly = ($type === 'limit_maker') || ($timeInForce === 'PO');
        if ($type === 'limit_maker') {
            $type = 'limit';
        }
        $stopPriceString = $this->safe_string($order, 'stopPrice');
        $stopPrice = $this->parse_number($this->omit_zero($stopPriceString));
        return $this->safe_order(array(
            'info' => $order,
            'id' => $id,
            'clientOrderId' => $clientOrderId,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'lastTradeTimestamp' => $lastTradeTimestamp,
            'symbol' => $symbol,
            'type' => $type,
            'timeInForce' => $timeInForce,
            'postOnly' => $postOnly,
            'reduceOnly' => $this->safe_value($order, 'reduceOnly'),
            'side' => $side,
            'price' => $price,
            'stopPrice' => $stopPrice,
            'amount' => $amount,
            'cost' => $cost,
            'average' => $average,
            'filled' => $filled,
            'remaining' => null,
            'status' => $status,
            'fee' => null,
            'trades' => $fills,
        ), $market);
    }

    public function create_order($symbol, $type, $side, $amount, $price = null, $params = array())
    {

        $this->load_markets();
        $market = $this->market($symbol);
        $defaultType = $this->safe_string_2($this->options, 'createOrder', 'defaultType', 'spot');
        $marketType = $this->safe_string($params, 'type', $defaultType);
        $clientOrderId = $this->safe_string_2($params, 'newClientOrderId', 'clientOrderId');
        $postOnly = $this->safe_value($params, 'postOnly', false);
        $params = $this->omit($params, array('type', 'newClientOrderId', 'clientOrderId', 'postOnly'));
        $reduceOnly = $this->safe_value($params, 'reduceOnly');
        if ($reduceOnly !== null) {
            if (($marketType !== 'future') && ($marketType !== 'delivery')) {
                throw new InvalidOrder($this->id . ' createOrder() does not support $reduceOnly for ' . $marketType . ' orders, $reduceOnly orders are supported for future and delivery markets only');
            }
        }
        $method = 'privatePostOrder';
        if ($marketType === 'future') {
            $method = 'fapiPrivatePostOrder';
        } elseif ($marketType === 'delivery') {
            $method = 'dapiPrivatePostOrder';
        } elseif ($marketType === 'margin') {
            $method = 'sapiPostMarginOrder';
        }

        if ($market['spot']) {
            $test = $this->safe_value($params, 'test', false);
            if ($test) {
                $method .= 'Test';
            }
            $params = $this->omit($params, 'test');
            if ($postOnly) {
                $type = 'LIMIT_MAKER';
            }
        }
        $initialUppercaseType = strtoupper($type);
        $uppercaseType = $initialUppercaseType;
        $stopPrice = $this->safe_number($params, 'stopPrice');
        if ($stopPrice !== null) {
            $params = $this->omit($params, 'stopPrice');
            if ($uppercaseType === 'MARKET') {
                $uppercaseType = $market['contract'] ? 'STOP_MARKET' : 'STOP_LOSS';
            } elseif ($uppercaseType === 'LIMIT') {
                $uppercaseType = $market['contract'] ? 'STOP' : 'STOP_LOSS_LIMIT';
            }
        }
        $validOrderTypes = $this->safe_value($market['info'], 'orderTypes');
        if (!$this->in_array($uppercaseType, $validOrderTypes)) {
            if ($initialUppercaseType !== $uppercaseType) {
                throw new InvalidOrder($this->id . ' $stopPrice parameter is not allowed for ' . $symbol . ' ' . $type . ' orders');
            } else {
                throw new InvalidOrder($this->id . ' ' . $type . ' is not a valid order $type for the ' . $symbol . ' market');
            }
        }
        $request = array(
            'symbol' => $market['id'],
            'type' => $uppercaseType,
            'side' => strtoupper($side),
        );
        if ($clientOrderId === null) {
            $broker = $this->safe_value($this->options, 'broker');
            if ($broker !== null) {
                $brokerId = $this->safe_string($broker, $marketType);
                if ($brokerId !== null) {
                    $request['newClientOrderId'] = $brokerId . $this->uuid22();
                }
            }
        } else {
            $request['newClientOrderId'] = $clientOrderId;
        }
        if (($marketType === 'spot') || ($marketType === 'margin')) {
            $request['newOrderRespType'] = $this->safe_value($this->options['newOrderRespType'], $type, 'RESULT'); // 'ACK' for order id, 'RESULT' for full order or 'FULL' for order with fills
        } else {

            $request['newOrderRespType'] = 'RESULT';  // "ACK", "RESULT", default "ACK"
        }
        $timeInForceIsRequired = false;
        $priceIsRequired = false;
        $stopPriceIsRequired = false;
        $quantityIsRequired = false;

        if ($uppercaseType === 'MARKET') {
            if ($market['spot']) {
                $quoteOrderQty = $this->safe_value($this->options, 'quoteOrderQty', true);
                if ($quoteOrderQty) {
                    $quoteOrderQty = $this->safe_value_2($params, 'quoteOrderQty', 'cost');
                    $precision = $market['precision']['price'];
                    if ($quoteOrderQty !== null) {
                        $request['quoteOrderQty'] = $this->decimal_to_precision($quoteOrderQty, TRUNCATE, $precision, $this->precisionMode);
                        $params = $this->omit($params, array('quoteOrderQty', 'cost'));
                    } elseif ($price !== null) {
                        $request['quoteOrderQty'] = $this->decimal_to_precision($amount * $price, TRUNCATE, $precision, $this->precisionMode);
                    } else {
                        $quantityIsRequired = true;
                    }
                } else {
                    $quantityIsRequired = true;
                }
            } else {
                $quantityIsRequired = true;
            }
        } elseif ($uppercaseType === 'LIMIT') {
            $priceIsRequired = true;
            $timeInForceIsRequired = true;
            $quantityIsRequired = true;
        } elseif (($uppercaseType === 'STOP_LOSS') || ($uppercaseType === 'TAKE_PROFIT')) {
            $stopPriceIsRequired = true;
            $quantityIsRequired = true;
            if ($market['linear'] || $market['inverse']) {
                $priceIsRequired = true;
            }
        } elseif (($uppercaseType === 'STOP_LOSS_LIMIT') || ($uppercaseType === 'TAKE_PROFIT_LIMIT')) {
            $quantityIsRequired = true;
            $stopPriceIsRequired = true;
            $priceIsRequired = true;
            $timeInForceIsRequired = true;
        } elseif ($uppercaseType === 'LIMIT_MAKER') {
            $priceIsRequired = true;
            $quantityIsRequired = true;
        } elseif ($uppercaseType === 'STOP') {
            $quantityIsRequired = true;
            $stopPriceIsRequired = true;
            $priceIsRequired = true;
        } elseif (($uppercaseType === 'STOP_MARKET') || ($uppercaseType === 'TAKE_PROFIT_MARKET')) {
            $closePosition = $this->safe_value($params, 'closePosition');
            if ($closePosition === null) {
                $quantityIsRequired = true;
            }
            $stopPriceIsRequired = true;
        } elseif ($uppercaseType === 'TRAILING_STOP_MARKET') {
            $quantityIsRequired = true;
            $callbackRate = $this->safe_number($params, 'callbackRate');
            if ($callbackRate === null) {
                throw new InvalidOrder($this->id . ' createOrder() requires a $callbackRate extra param for a ' . $type . ' order');
            }
        }
        if ($quantityIsRequired) {
            $request['quantity'] = $this->amount_to_precision($symbol, $amount);
        }
        if ($priceIsRequired) {
            if ($price === null) {
                throw new InvalidOrder($this->id . ' createOrder() requires a $price argument for a ' . $type . ' order');
            }
            $request['price'] = $this->price_to_precision($symbol, $price);
        }
        if ($timeInForceIsRequired) {
            $request['timeInForce'] = $this->options['defaultTimeInForce']; // 'GTC' = Good To Cancel (default), 'IOC' = Immediate Or Cancel
        }
        if ($stopPriceIsRequired) {
            if ($stopPrice === null) {
                throw new InvalidOrder($this->id . ' createOrder() requires a $stopPrice extra param for a ' . $type . ' order');
            } else {
                $request['stopPrice'] = $this->price_to_precision($symbol, $stopPrice);
            }
        }
        $response = $this->$method(array_merge($request, $params));
        return $this->parse_order($response, $market);
    }

    public function fetch_order($id, $symbol = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchOrder() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $defaultType = $this->safe_string_2($this->options, 'fetchOrder', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $method = 'privateGetOrder';
        if ($type === 'future') {
            $method = 'fapiPrivateGetOrder';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetOrder';
        } elseif ($type === 'margin') {
            $method = 'sapiGetMarginOrder';
        }
        $request = array(
            'symbol' => $market['id'],
        );
        $clientOrderId = $this->safe_value_2($params, 'origClientOrderId', 'clientOrderId');
        if ($clientOrderId !== null) {
            $request['origClientOrderId'] = $clientOrderId;
        } else {
            $request['orderId'] = $id;
        }
        $query = $this->omit($params, array('type', 'clientOrderId', 'origClientOrderId'));
        $response = $this->$method(array_merge($request, $query));
        return $this->parse_order($response, $market);
    }

    public function fetch_orders($symbol = null, $since = null, $limit = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchOrders() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $defaultType = $this->safe_string_2($this->options, 'fetchOrders', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $method = 'privateGetAllOrders';
        if ($type === 'future') {
            $method = 'fapiPrivateGetAllOrders';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetAllOrders';
        } elseif ($type === 'margin') {
            $method = 'sapiGetMarginAllOrders';
        }
        $request = array(
            'symbol' => $market['id'],
        );
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $query = $this->omit($params, 'type');
        $response = $this->$method(array_merge($request, $query));

        return $this->parse_orders($response, $market, $since, $limit);
    }

    public function fetch_open_orders($symbol = null, $since = null, $limit = null, $params = array())
    {

        $this->load_markets();
        $market = null;
        $query = null;
        $type = null;
        $request = array();
        if ($symbol !== null) {
            $market = $this->market($symbol);
            $request['symbol'] = $market['id'];
            $defaultType = $this->safe_string_2($this->options, 'fetchOpenOrders', 'defaultType', 'spot');
            $marketType = (is_array($market) && array_key_exists('type', $market)) ? $market['type'] : $defaultType;
            $type = $this->safe_string($params, 'type', $marketType);
            $query = $this->omit($params, 'type');
        } elseif ($this->options['warnOnFetchOpenOrdersWithoutSymbol']) {
            $symbols = $this->symbols;
            $numSymbols = is_array($symbols) ? count($symbols) : 0;
            $fetchOpenOrdersRateLimit = intval($numSymbols / 2);
            throw new ExchangeError($this->id . ' fetchOpenOrders() WARNING => fetching open orders without specifying a $symbol is rate-limited to one call per ' . (string) $fetchOpenOrdersRateLimit . ' seconds. Do not call this $method frequently to avoid ban. Set ' . $this->id . '.options["warnOnFetchOpenOrdersWithoutSymbol"] = false to suppress this warning message.');
        } else {
            $defaultType = $this->safe_string_2($this->options, 'fetchOpenOrders', 'defaultType', 'spot');
            $type = $this->safe_string($params, 'type', $defaultType);
            $query = $this->omit($params, 'type');
        }
        $method = 'privateGetOpenOrders';
        if ($type === 'future') {
            $method = 'fapiPrivateGetOpenOrders';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetOpenOrders';
        } elseif ($type === 'margin') {
            $method = 'sapiGetMarginOpenOrders';
        }
        $response = $this->$method(array_merge($request, $query));
        return $this->parse_orders($response, $market, $since, $limit);
    }

    public function fetch_closed_orders($symbol = null, $since = null, $limit = null, $params = array())
    {

        $orders = $this->fetch_orders($symbol, $since, $limit, $params);
        return $this->filter_by($orders, 'status', 'closed');
    }

    public function cancel_order($id, $symbol = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' cancelOrder() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $defaultType = $this->safe_string_2($this->options, 'cancelOrder', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        // https://github.com/ccxt/ccxt/issues/6507
        $origClientOrderId = $this->safe_value_2($params, 'origClientOrderId', 'clientOrderId');
        $request = array(
            'symbol' => $market['id'],
            // 'orderId' => $id,
            // 'origClientOrderId' => $id,
        );
        if ($origClientOrderId === null) {
            $request['orderId'] = $id;
        } else {
            $request['origClientOrderId'] = $origClientOrderId;
        }
        $method = 'privateDeleteOrder';
        if ($type === 'future') {
            $method = 'fapiPrivateDeleteOrder';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateDeleteOrder';
        } elseif ($type === 'margin') {
            $method = 'sapiDeleteMarginOrder';
        }
        $query = $this->omit($params, array('type', 'origClientOrderId', 'clientOrderId'));
        $response = $this->$method(array_merge($request, $query));
        return $this->parse_order($response, $market);
    }

    public function cancel_all_orders($symbol = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' cancelAllOrders() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'symbol' => $market['id'],
        );
        $defaultType = $this->safe_string_2($this->options, 'cancelAllOrders', 'defaultType', 'spot');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        $method = 'privateDeleteOpenOrders';
        if ($type === 'margin') {
            $method = 'sapiDeleteMarginOpenOrders';
        } elseif ($type === 'future') {
            $method = 'fapiPrivateDeleteAllOpenOrders';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateDeleteAllOpenOrders';
        }
        $response = $this->$method(array_merge($request, $query));
        if (gettype($response) === 'array' && array_keys($response) === array_keys(array_keys($response))) {
            return $this->parse_orders($response, $market);
        } else {
            return $response;
        }
    }

    public function fetch_order_trades($id, $symbol = null, $since = null, $limit = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchOrderTrades() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $type = $this->safe_string($params, 'type', $market['type']);
        $params = $this->omit($params, 'type');
        if ($type !== 'spot') {
            throw new NotSupported($this->id . ' fetchOrderTrades() supports spot markets only');
        }
        $request = array(
            'orderId' => $id,
        );
        return $this->fetch_my_trades($symbol, $since, $limit, array_merge($request, $params));
    }

    public function fetch_my_trades($symbol = null, $since = null, $limit = null, $params = array())
    {

        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' fetchMyTrades() requires a $symbol argument');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $type = $this->safe_string($params, 'type', $market['type']);
        $params = $this->omit($params, 'type');
        $method = null;
        if ($type === 'spot') {
            $method = 'privateGetMyTrades';
        } elseif ($type === 'margin') {
            $method = 'sapiGetMarginMyTrades';
        } elseif ($type === 'future') {
            $method = 'fapiPrivateGetUserTrades';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetUserTrades';
        }
        $request = array(
            'symbol' => $market['id'],
        );
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $response = $this->$method(array_merge($request, $params));

        return $this->parse_trades($response, $market, $since, $limit);
    }

    public function fetch_my_dust_trades($symbol = null, $since = null, $limit = null, $params = array())
    {
        is->load_markets();
        $request = array();
        if ($since !== null) {
            $request['startTime'] = $since;
            $request['endTime'] = $this->sum($since, 7776000000);
        }
        $response = $this->sapiGetAssetDribblet(array_merge($request, $params));

        $results = $this->safe_value($response, 'userAssetDribblets', array());
        $rows = $this->safe_integer($response, 'total', 0);
        $data = array();
        for ($i = 0; $i < $rows; $i++) {
            $logs = $this->safe_value($results[$i], 'userAssetDribbletDetails', array());
            for ($j = 0; $j < count($logs); $j++) {
                $logs[$j]['isDustTrade'] = true;
                $data[] = $logs[$j];
            }
        }
        $trades = $this->parse_trades($data, null, $since, $limit);
        return $this->filter_by_since_limit($trades, $since, $limit);
    }

    public function parse_dust_trade($trade, $market = null)
    {

        $orderId = $this->safe_string($trade, 'transId');
        $timestamp = $this->safe_integer($trade, 'operateTime');
        $currencyId = $this->safe_string($trade, 'fromAsset');
        $tradedCurrency = $this->safe_currency_code($currencyId);
        $bnb = $this->currency('BNB');
        $earnedCurrency = $bnb['code'];
        $applicantSymbol = $earnedCurrency . '/' . $tradedCurrency;
        $tradedCurrencyIsQuote = false;
        if (is_array($this->markets) && array_key_exists($applicantSymbol, $this->markets)) {
            $tradedCurrencyIsQuote = true;
        }
        $feeCostString = $this->safe_string($trade, 'serviceChargeAmount');
        $fee = array(
            'currency' => $earnedCurrency,
            'cost' => $this->parse_number($feeCostString),
        );
        $symbol = null;
        $amountString = null;
        $costString = null;
        $side = null;
        if ($tradedCurrencyIsQuote) {
            $symbol = $applicantSymbol;
            $amountString = $this->safe_string($trade, 'transferedAmount');
            $costString = $this->safe_string($trade, 'amount');
            $side = 'buy';
        } else {
            $symbol = $tradedCurrency . '/' . $earnedCurrency;
            $amountString = $this->safe_string($trade, 'amount');
            $costString = $this->safe_string($trade, 'transferedAmount');
            $side = 'sell';
        }
        $priceString = null;
        if ($costString !== null) {
            if ($amountString) {
                $priceString = Precise::string_div($costString, $amountString);
            }
        }
        $id = null;
        $amount = $this->parse_number($amountString);
        $price = $this->parse_number($priceString);
        $cost = $this->parse_number($costString);
        $type = null;
        $takerOrMaker = null;
        return array(
            'id' => $id,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $symbol,
            'order' => $orderId,
            'type' => $type,
            'takerOrMaker' => $takerOrMaker,
            'side' => $side,
            'amount' => $amount,
            'price' => $price,
            'cost' => $cost,
            'fee' => $fee,
            'info' => $trade,
        );
    }

    public function fetch_deposits($code = null, $since = null, $limit = null, $params = array())
    {

        $this->load_markets();
        $currency = null;
        $response = null;
        $request = array();
        $legalMoney = $this->safe_value($this->options, 'legalMoney', array());
        $until = $this->safe_integer($params, 'until');
        if (is_array($legalMoney) && array_key_exists($code, $legalMoney)) {
            if ($code !== null) {
                $currency = $this->currency($code);
            }
            $request['transactionType'] = 0;
            if ($since !== null) {
                $request['beginTime'] = $since;
            }
            if ($until !== null) {
                $request['endTime'] = $until;
            }
            $raw = $this->sapiGetFiatOrders(array_merge($request, $params));
            $response = $this->safe_value($raw, 'data');
        } else {
            if ($code !== null) {
                $currency = $this->currency($code);
                $request['coin'] = $currency['id'];
            }
            if ($since !== null) {
                $request['startTime'] = $since;
                // max 3 months range https://github.com/ccxt/ccxt/issues/6495
                $endTime = $this->sum($since, 7776000000);
                if ($until !== null) {
                    $endTime = min($endTime, $until);
                }
                $request['endTime'] = $endTime;
            }
            if ($limit !== null) {
                $request['limit'] = $limit;
            }
            $response = $this->sapiGetCapitalDepositHisrec(array_merge($request, $params));
        }
        return $this->parse_transactions($response, $currency, $since, $limit);
    }

    public function fetch_withdrawals($code = null, $since = null, $limit = null, $params = array())
    {

        $this->load_markets();
        $legalMoney = $this->safe_value($this->options, 'legalMoney', array());
        $request = array();
        $response = null;
        $currency = null;
        if (is_array($legalMoney) && array_key_exists($code, $legalMoney)) {
            if ($code !== null) {
                $currency = $this->currency($code);
            }
            $request['transactionType'] = 1;
            if ($since !== null) {
                $request['beginTime'] = $since;
            }
            $raw = $this->sapiGetFiatOrders(array_merge($request, $params));
            $response = $this->safe_value($raw, 'data');
        } else {
            if ($code !== null) {
                $currency = $this->currency($code);
                $request['coin'] = $currency['id'];
            }
            if ($since !== null) {
                $request['startTime'] = $since;
                // max 3 months range https://github.com/ccxt/ccxt/issues/6495
                $request['endTime'] = $this->sum($since, 7776000000);
            }
            if ($limit !== null) {
                $request['limit'] = $limit;
            }
            $response = $this->sapiGetCapitalWithdrawHistory(array_merge($request, $params));
        }
        return $this->parse_transactions($response, $currency, $since, $limit);
    }

    public function parse_transaction_status_by_type($status, $type = null)
    {
        $statusesByType = array(
            'deposit' => array(
                '0' => 'pending',
                '1' => 'ok',
                'Processing' => 'pending',
                'Failed' => 'failed',
                'Successful' => 'ok',
                'Refunding' => 'canceled',
                'Refunded' => 'canceled',
                'Refund Failed' => 'failed',
            ),
            'withdrawal' => array(
                '0' => 'pending', // Email Sent
                '1' => 'canceled', // Cancelled (different from 1 = ok in deposits)
                '2' => 'pending', // Awaiting Approval
                '3' => 'failed', // Rejected
                '4' => 'pending', // Processing
                '5' => 'failed', // Failure
                '6' => 'ok', // Completed

                'Processing' => 'pending',
                'Failed' => 'failed',
                'Successful' => 'ok',
                'Refunding' => 'canceled',
                'Refunded' => 'canceled',
                'Refund Failed' => 'failed',
            ),
        );
        $statuses = $this->safe_value($statusesByType, $type, array());
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_transaction($transaction, $currency = null)
    {

        $id = $this->safe_string_2($transaction, 'id', 'orderNo');
        $address = $this->safe_string($transaction, 'address');
        $tag = $this->safe_string($transaction, 'addressTag'); // set but unused
        if ($tag !== null) {
            if (strlen($tag) < 1) {
                $tag = null;
            }
        }
        $txid = $this->safe_string($transaction, 'txId');
        if (($txid !== null) && (mb_strpos($txid, 'Internal transfer ') !== false)) {
            $txid = mb_substr($txid, 18);
        }
        $currencyId = $this->safe_string_2($transaction, 'coin', 'fiatCurrency');
        $code = $this->safe_currency_code($currencyId, $currency);
        $timestamp = null;
        $insertTime = $this->safe_integer_2($transaction, 'insertTime', 'createTime');
        $applyTime = $this->parse8601($this->safe_string($transaction, 'applyTime'));
        $type = $this->safe_string($transaction, 'type');
        if ($type === null) {
            if (($insertTime !== null) && ($applyTime === null)) {
                $type = 'deposit';
                $timestamp = $insertTime;
            } elseif (($insertTime === null) && ($applyTime !== null)) {
                $type = 'withdrawal';
                $timestamp = $applyTime;
            }
        }
        $status = $this->parse_transaction_status_by_type($this->safe_string($transaction, 'status'), $type);
        $amount = $this->safe_number($transaction, 'amount');
        $feeCost = $this->safe_number_2($transaction, 'transactionFee', 'totalFee');
        $fee = null;
        if ($feeCost !== null) {
            $fee = array('currency' => $code, 'cost' => $feeCost);
        }
        $updated = $this->safe_integer_2($transaction, 'successTime', 'updateTime');
        $internal = $this->safe_integer($transaction, 'transferType');
        if ($internal !== null) {
            $internal = $internal ? true : false;
        }
        $network = $this->safe_string($transaction, 'network');
        return array(
            'info' => $transaction,
            'id' => $id,
            'txid' => $txid,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'network' => $network,
            'address' => $address,
            'addressTo' => $address,
            'addressFrom' => null,
            'tag' => $tag,
            'tagTo' => $tag,
            'tagFrom' => null,
            'type' => $type,
            'amount' => $amount,
            'currency' => $code,
            'status' => $status,
            'updated' => $updated,
            'internal' => $internal,
            'fee' => $fee,
        );
    }

    public function parse_transfer_status($status)
    {
        $statuses = array(
            'CONFIRMED' => 'ok',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_transfer($transfer, $currency = null)
    {

        $id = $this->safe_string($transfer, 'tranId');
        $currencyId = $this->safe_string($transfer, 'asset');
        $code = $this->safe_currency_code($currencyId, $currency);
        $amount = $this->safe_number($transfer, 'amount');
        $type = $this->safe_string($transfer, 'type');
        $fromAccount = null;
        $toAccount = null;
        $accountsById = $this->safe_value($this->options, 'accountsById', array());
        if ($type !== null) {
            $parts = explode('_', $type);
            $fromAccount = $this->safe_value($parts, 0);
            $toAccount = $this->safe_value($parts, 1);
            $fromAccount = $this->safe_string($accountsById, $fromAccount, $fromAccount);
            $toAccount = $this->safe_string($accountsById, $toAccount, $toAccount);
        }
        $timestamp = $this->safe_integer($transfer, 'timestamp');
        $status = $this->parse_transfer_status($this->safe_string($transfer, 'status'));
        return array(
            'info' => $transfer,
            'id' => $id,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'currency' => $code,
            'amount' => $amount,
            'fromAccount' => $fromAccount,
            'toAccount' => $toAccount,
            'status' => $status,
        );
    }

    public function parse_income($income, $market = null)
    {

        $marketId = $this->safe_string($income, 'symbol');
        $symbol = $this->safe_symbol($marketId, $market);
        $amount = $this->safe_number($income, 'income');
        $currencyId = $this->safe_string($income, 'asset');
        $code = $this->safe_currency_code($currencyId);
        $id = $this->safe_string($income, 'tranId');
        $timestamp = $this->safe_integer($income, 'time');
        return array(
            'info' => $income,
            'symbol' => $symbol,
            'code' => $code,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'id' => $id,
            'amount' => $amount,
        );
    }

    public function parse_incomes($incomes, $market = null, $since = null, $limit = null)
    {
        $result = array();
        for ($i = 0; $i < count($incomes); $i++) {
            $entry = $incomes[$i];
            $parsed = $this->parse_income($entry, $market);
            $result[] = $parsed;
        }
        $sorted = $this->sort_by($result, 'timestamp');
        return $this->filter_by_since_limit($sorted, $since, $limit);
    }

    public function transfer($code, $amount, $fromAccount, $toAccount, $params = array())
    {

        $this->load_markets();
        $currency = $this->currency($code);
        $type = $this->safe_string($params, 'type');
        if ($type === null) {
            $accountsByType = $this->safe_value($this->options, 'accountsByType', array());
            $fromId = $this->safe_string($accountsByType, $fromAccount, $fromAccount);
            $toId = $this->safe_string($accountsByType, $toAccount, $toAccount);
            $type = $fromId . '_' . $toId;
        }
        $request = array(
            'asset' => $currency['id'],
            'amount' => $this->currency_to_precision($code, $amount),
            'type' => $type,
        );
        $response = $this->sapiPostAssetTransfer(array_merge($request, $params));

        $transfer = $this->parse_transfer($response, $currency);
        return array_merge($transfer, array(
            'amount' => $amount,
            'currency' => $code,
            'fromAccount' => $fromAccount,
            'toAccount' => $toAccount,
        ));
    }

    public function fetch_transfers($code = null, $since = null, $limit = null, $params = array())
    {

        $this->load_markets();
        $currency = null;
        if ($code !== null) {
            $currency = $this->currency($code);
        }
        $defaultType = $this->safe_string_2($this->options, 'fetchTransfers', 'defaultType', 'spot');
        $fromAccount = $this->safe_string($params, 'fromAccount', $defaultType);
        $defaultTo = ($fromAccount === 'future') ? 'spot' : 'future';
        $toAccount = $this->safe_string($params, 'toAccount', $defaultTo);
        $type = $this->safe_string($params, 'type');
        $accountsByType = $this->safe_value($this->options, 'accountsByType', array());
        $fromId = $this->safe_string($accountsByType, $fromAccount);
        $toId = $this->safe_string($accountsByType, $toAccount);
        if ($type === null) {
            if ($fromId === null) {
                $keys = is_array($accountsByType) ? array_keys($accountsByType) : array();
                throw new ExchangeError($this->id . ' $fromAccount parameter must be one of ' . implode(', ', $keys));
            }
            if ($toId === null) {
                $keys = is_array($accountsByType) ? array_keys($accountsByType) : array();
                throw new ExchangeError($this->id . ' $toAccount parameter must be one of ' . implode(', ', $keys));
            }
            $type = $fromId . '_' . $toId;
        }
        $request = array(
            'type' => $type,
        );
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        if ($limit !== null) {
            $request['size'] = $limit;
        }
        $response = $this->sapiGetAssetTransfer(array_merge($request, $params));

        $rows = $this->safe_value($response, 'rows', array());
        return $this->parse_transfers($rows, $currency, $since, $limit);
    }

    public function fetch_deposit_address($code, $params = array())
    {

        $this->load_markets();
        $currency = $this->currency($code);
        $request = array(
            'coin' => $currency['id'],

        );
        $networks = $this->safe_value($this->options, 'networks', array());
        $network = $this->safe_string_upper($params, 'network'); // this line allows the user to specify either ERC20 or ETH
        $network = $this->safe_string($networks, $network, $network); // handle ERC20>ETH alias
        if ($network !== null) {
            $request['network'] = $network;
            $params = $this->omit($params, 'network');
        }

        $response = $this->sapiGetCapitalDepositAddress(array_merge($request, $params));

        $address = $this->safe_string($response, 'address');
        $url = $this->safe_string($response, 'url');
        $impliedNetwork = null;
        if ($url !== null) {
            $reverseNetworks = $this->safe_value($this->options, 'reverseNetworks', array());
            $parts = explode('/', $url);
            $topLevel = $this->safe_string($parts, 2);
            if (($topLevel === 'blockchair.com') || ($topLevel === 'viewblock.io')) {
                $subLevel = $this->safe_string($parts, 3);
                if ($subLevel !== null) {
                    $topLevel = $topLevel . '/' . $subLevel;
                }
            }
            $impliedNetwork = $this->safe_string($reverseNetworks, $topLevel);
            $impliedNetworks = $this->safe_value($this->options, 'impliedNetworks', array(
                'ETH' => array('ERC20' => 'ETH'),
                'TRX' => array('TRC20' => 'TRX'),
            ));
            if (is_array($impliedNetworks) && array_key_exists($code, $impliedNetworks)) {
                $conversion = $this->safe_value($impliedNetworks, $code, array());
                $impliedNetwork = $this->safe_string($conversion, $impliedNetwork, $impliedNetwork);
            }
        }
        $tag = $this->safe_string($response, 'tag', '');
        if (strlen($tag) === 0) {
            $tag = null;
        }
        $this->check_address($address);
        return array(
            'currency' => $code,
            'address' => $address,
            'tag' => $tag,
            'network' => $impliedNetwork,
            'info' => $response,
        );
    }

    public function fetch_transaction_fees($codes = null, $params = array())
    {

        $this->load_markets();
        $response = $this->sapiGetCapitalConfigGetall($params);

        $withdrawFees = array();
        for ($i = 0; $i < count($response); $i++) {
            $entry = $response[$i];
            $currencyId = $this->safe_string($entry, 'coin');
            $code = $this->safe_currency_code($currencyId);
            $networkList = $this->safe_value($entry, 'networkList', array());
            $withdrawFees[$code] = array();
            for ($j = 0; $j < count($networkList); $j++) {
                $networkEntry = $networkList[$j];
                $networkId = $this->safe_string($networkEntry, 'network');
                $networkCode = $this->safe_currency_code($networkId);
                $fee = $this->safe_number($networkEntry, 'withdrawFee');
                $withdrawFees[$code][$networkCode] = $fee;
            }
        }
        return array(
            'withdraw' => $withdrawFees,
            'deposit' => array(),
            'info' => $response,
        );
    }

    public function withdraw($code, $amount, $address, $tag = null, $params = array())
    {

        list($tag, $params) = $this->handle_withdraw_tag_and_params($tag, $params);
        $this->check_address($address);
        $this->load_markets();
        $currency = $this->currency($code);
        $networks = $this->safe_value($this->options, 'networks', array());
        $network = $this->safe_string_upper($params, 'network'); // this line allows the user to specify either ERC20 or ETH
        $network = $this->safe_string($networks, $network, $network); // handle ERC20>ETH alias
        if ($network !== null) {
            $request['network'] = $network;
            $params = $this->omit($params, 'network');
        }
        $response = $this->sapiPostCapitalWithdrawApply(array_merge($request, $params));

        return $this->parse_transaction($response, $currency);
    }

    public function parse_trading_fee($fee, $market = null)
    {
        $marketId = $this->safe_string($fee, 'symbol');
        $symbol = $this->safe_symbol($marketId);
        return array(
            'info' => $fee,
            'symbol' => $symbol,
            'maker' => $this->safe_number($fee, 'makerCommission'),
            'taker' => $this->safe_number($fee, 'takerCommission'),
        );
    }

    public function fetch_trading_fee($symbol, $params = array())
    {
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'symbol' => $market['id'],
        );
        $response = $this->sapiGetAssetTradeFee(array_merge($request, $params));

        $first = $this->safe_value($response, 0, array());
        return $this->parse_trading_fee($first);
    }

    public function fetch_trading_fees($params = array())
    {
        $this->load_markets();
        $method = null;
        $defaultType = $this->safe_string_2($this->options, 'fetchTradingFees', 'defaultType', 'future');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        if (($type === 'spot') || ($type === 'margin')) {
            $method = 'sapiGetAssetTradeFee';
        } elseif ($type === 'future') {
            $method = 'fapiPrivateGetAccount';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetAccount';
        }
        $response = $this->$method($query);
        if (($type === 'spot') || ($type === 'margin')) {
            $result = array();
            for ($i = 0; $i < count($response); $i++) {
                $fee = $this->parse_trading_fee($response[$i]);
                $symbol = $fee['symbol'];
                $result[$symbol] = $fee;
            }
            return $result;
        } elseif ($type === 'future') {
            $symbols = is_array($this->markets) ? array_keys($this->markets) : array();
            $result = array();
            $feeTier = $this->safe_integer($response, 'feeTier');
            $feeTiers = $this->fees[$type]['trading']['tiers'];
            $maker = $feeTiers['maker'][$feeTier][1];
            $taker = $feeTiers['taker'][$feeTier][1];
            for ($i = 0; $i < count($symbols); $i++) {
                $symbol = $symbols[$i];
                $result[$symbol] = array(
                    'info' => array(
                        'feeTier' => $feeTier,
                    ),
                    'symbol' => $symbol,
                    'maker' => $maker,
                    'taker' => $taker,
                );
            }
            return $result;
        } elseif ($type === 'delivery') {
            $symbols = is_array($this->markets) ? array_keys($this->markets) : array();
            $result = array();
            $feeTier = $this->safe_integer($response, 'feeTier');
            $feeTiers = $this->fees[$type]['trading']['tiers'];
            $maker = $feeTiers['maker'][$feeTier][1];
            $taker = $feeTiers['taker'][$feeTier][1];
            for ($i = 0; $i < count($symbols); $i++) {
                $symbol = $symbols[$i];
                $result[$symbol] = array(
                    'info' => array(
                        'feeTier' => $feeTier,
                    ),
                    'symbol' => $symbol,
                    'maker' => $maker,
                    'taker' => $taker,
                );
            }
            return $result;
        }
    }

    public function futures_transfer($code, $amount, $type, $params = array())
    {
        if (($type < 1) || ($type > 4)) {
            throw new ArgumentsRequired($this->id . ' $type must be between 1 and 4');
        }
        $this->load_markets();
        $currency = $this->currency($code);
        $request = array(
            'asset' => $currency['id'],
            'amount' => $amount,
            'type' => $type,
        );
        $response = $this->sapiPostFuturesTransfer(array_merge($request, $params));

        return $this->parse_transfer($response, $currency);
    }


    public function parse_funding_rate($contract, $market = null)
    {
        $timestamp = $this->safe_integer($contract, 'time');
        $marketId = $this->safe_string($contract, 'symbol');
        $symbol = $this->safe_symbol($marketId, $market);
        $markPrice = $this->safe_number($contract, 'markPrice');
        $indexPrice = $this->safe_number($contract, 'indexPrice');
        $interestRate = $this->safe_number($contract, 'interestRate');
        $estimatedSettlePrice = $this->safe_number($contract, 'estimatedSettlePrice');
        $fundingRate = $this->safe_number($contract, 'lastFundingRate');
        $fundingTime = $this->safe_integer($contract, 'nextFundingTime');
        return array(
            'info' => $contract,
            'symbol' => $symbol,
            'markPrice' => $markPrice,
            'indexPrice' => $indexPrice,
            'interestRate' => $interestRate,
            'estimatedSettlePrice' => $estimatedSettlePrice,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'fundingRate' => $fundingRate,
            'fundingTimestamp' => $fundingTime,
            'fundingDatetime' => $this->iso8601($fundingTime),
            'nextFundingRate' => null,
            'nextFundingTimestamp' => null,
            'nextFundingDatetime' => null,
            'previousFundingRate' => null,
            'previousFundingTimestamp' => null,
            'previousFundingDatetime' => null,
        );
    }

    public function parse_account_positions($account)
    {
        $positions = $this->safe_value($account, 'positions');
        $assets = $this->safe_value($account, 'assets', array());
        $balances = array();
        for ($i = 0; $i < count($assets); $i++) {
            $entry = $assets[$i];
            $currencyId = $this->safe_string($entry, 'asset');
            $code = $this->safe_currency_code($currencyId);
            $crossWalletBalance = $this->safe_string($entry, 'crossWalletBalance');
            $crossUnPnl = $this->safe_string($entry, 'crossUnPnl');
            $balances[$code] = array(
                'crossMargin' => Precise::string_add($crossWalletBalance, $crossUnPnl),
                'crossWalletBalance' => $crossWalletBalance,
            );
        }
        $result = array();
        for ($i = 0; $i < count($positions); $i++) {
            $position = $positions[$i];
            $marketId = $this->safe_string($position, 'symbol');
            $market = $this->safe_market($marketId);
            $code = ($this->options['defaultType'] === 'future') ? $market['quote'] : $market['base'];
            if (is_array($balances) && array_key_exists($code, $balances)) {
                $parsed = $this->parse_account_position(array_merge($position, array(
                    'crossMargin' => $balances[$code]['crossMargin'],
                    'crossWalletBalance' => $balances[$code]['crossWalletBalance'],
                )), $market);
                $result[] = $parsed;
            }
        }
        return $result;
    }

    public function parse_account_position($position, $market = null)
    {
        $marketId = $this->safe_string($position, 'symbol');
        $market = $this->safe_market($marketId, $market);
        $symbol = $this->safe_string($market, 'symbol');
        $leverageString = $this->safe_string($position, 'leverage');
        $leverage = intval($leverageString);
        $initialMarginString = $this->safe_string($position, 'initialMargin');
        $initialMargin = $this->parse_number($initialMarginString);
        $initialMarginPercentageString = Precise::string_div('1', $leverageString, 8);
        $rational = (fmod(1000, $leverage)) === 0;
        if (!$rational) {
            $initialMarginPercentageString = Precise::string_div(Precise::string_add($initialMarginPercentageString, '1e-8'), '1', 8);
        }
        // as oppose to notionalValue
        $usdm = (is_array($position) && array_key_exists('notional', $position));
        $maintenanceMarginString = $this->safe_string($position, 'maintMargin');
        $maintenanceMargin = $this->parse_number($maintenanceMarginString);
        $entryPriceString = $this->safe_string($position, 'entryPrice');
        $entryPrice = $this->parse_number($entryPriceString);
        $notionalString = $this->safe_string_2($position, 'notional', 'notionalValue');
        $notionalStringAbs = Precise::string_abs($notionalString);
        $notional = $this->parse_number($notionalStringAbs);
        $contractsString = $this->safe_string($position, 'positionAmt');
        $contractsStringAbs = Precise::string_abs($contractsString);
        if ($contractsString === null) {
            $entryNotional = Precise::string_mul(Precise::string_mul($leverageString, $initialMarginString), $entryPriceString);
            $contractSize = $this->safe_string($market, 'contractSize');
            $contractsString = Precise::string_div($entryNotional, $contractSize);
            $contractsStringAbs = Precise::string_div(Precise::string_add($contractsString, '0.5'), '1', 0);
        }
        $contracts = $this->parse_number($contractsStringAbs);
        $leverageBrackets = $this->safe_value($this->options, 'leverageBrackets', array());
        $leverageBracket = $this->safe_value($leverageBrackets, $symbol, array());
        $maintenanceMarginPercentageString = null;
        for ($i = 0; $i < count($leverageBracket); $i++) {
            $bracket = $leverageBracket[$i];
            if (Precise::string_lt($notionalStringAbs, $bracket[0])) {
                break;
            }
            $maintenanceMarginPercentageString = $bracket[1];
        }
        $maintenanceMarginPercentage = $this->parse_number($maintenanceMarginPercentageString);
        $unrealizedPnlString = $this->safe_string($position, 'unrealizedProfit');
        $unrealizedPnl = $this->parse_number($unrealizedPnlString);
        $timestamp = $this->safe_integer($position, 'updateTime');
        if ($timestamp === 0) {
            $timestamp = null;
        }
        $isolated = $this->safe_value($position, 'isolated');
        $marginMode = null;
        $collateralString = null;
        $walletBalance = null;
        if ($isolated) {
            $marginMode = 'isolated';
            $walletBalance = $this->safe_string($position, 'isolatedWallet');
            $collateralString = Precise::string_add($walletBalance, $unrealizedPnlString);
        } else {
            $marginMode = 'cross';
            $walletBalance = $this->safe_string($position, 'crossWalletBalance');
            $collateralString = $this->safe_string($position, 'crossMargin');
        }
        $collateral = $this->parse_number($collateralString);
        $marginRatio = null;
        $side = null;
        $percentage = null;
        $liquidationPriceStringRaw = null;
        $liquidationPrice = null;
        $contractSize = $this->safe_value($market, 'contractSize');
        $contractSizeString = $this->number_to_string($contractSize);
        if (Precise::string_equals($notionalString, '0')) {
            $entryPrice = null;
        } else {
            $side = Precise::string_lt($notionalString, '0') ? 'short' : 'long';
            $marginRatio = $this->parse_number(Precise::string_div(Precise::string_add(Precise::string_div($maintenanceMarginString, $collateralString), '5e-5'), '1', 4));
            $percentage = $this->parse_number(Precise::string_mul(Precise::string_div($unrealizedPnlString, $initialMarginString, 4), '100'));
            if ($usdm) {
                $onePlusMaintenanceMarginPercentageString = null;
                $entryPriceSignString = $entryPriceString;
                if ($side === 'short') {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_add('1', $maintenanceMarginPercentageString);
                } else {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_add('-1', $maintenanceMarginPercentageString);
                    $entryPriceSignString = Precise::string_mul('-1', $entryPriceSignString);
                }
                $leftSide = Precise::string_div($walletBalance, Precise::string_mul($contractsStringAbs, $onePlusMaintenanceMarginPercentageString));
                $rightSide = Precise::string_div($entryPriceSignString, $onePlusMaintenanceMarginPercentageString);
                $liquidationPriceStringRaw = Precise::string_add($leftSide, $rightSide);
            } else {
                $onePlusMaintenanceMarginPercentageString = null;
                $entryPriceSignString = $entryPriceString;
                if ($side === 'short') {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_sub('1', $maintenanceMarginPercentageString);
                } else {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_sub('-1', $maintenanceMarginPercentageString);
                    $entryPriceSignString = Precise::string_mul('-1', $entryPriceSignString);
                }
                $size = Precise::string_mul($contractsStringAbs, $contractSizeString);
                $leftSide = Precise::string_mul($size, $onePlusMaintenanceMarginPercentageString);
                $rightSide = Precise::string_sub(Precise::string_mul(Precise::string_div('1', $entryPriceSignString), $size), $walletBalance);
                $liquidationPriceStringRaw = Precise::string_div($leftSide, $rightSide);
            }
            $pricePrecision = $market['precision']['price'];
            $pricePrecisionPlusOne = $pricePrecision + 1;
            $pricePrecisionPlusOneString = (string) $pricePrecisionPlusOne;
            $rounder = new Precise('5e-' . $pricePrecisionPlusOneString);
            $rounderString = (string) $rounder;
            $liquidationPriceRoundedString = Precise::string_add($rounderString, $liquidationPriceStringRaw);
            $truncatedLiquidationPrice = Precise::string_div($liquidationPriceRoundedString, '1', $pricePrecision);
            if ($truncatedLiquidationPrice[0] === '-') {
                $truncatedLiquidationPrice = null;
            }
            $liquidationPrice = $this->parse_number($truncatedLiquidationPrice);
        }
        $positionSide = $this->safe_string($position, 'positionSide');
        $hedged = $positionSide !== 'BOTH';
        return array(
            'info' => $position,
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'initialMargin' => $initialMargin,
            'initialMarginPercentage' => $this->parse_number($initialMarginPercentageString),
            'maintenanceMargin' => $maintenanceMargin,
            'maintenanceMarginPercentage' => $maintenanceMarginPercentage,
            'entryPrice' => $entryPrice,
            'notional' => $notional,
            'leverage' => $this->parse_number($leverageString),
            'unrealizedPnl' => $unrealizedPnl,
            'contracts' => $contracts,
            'contractSize' => $contractSize,
            'marginRatio' => $marginRatio,
            'liquidationPrice' => $liquidationPrice,
            'markPrice' => null,
            'collateral' => $collateral,
            'marginMode' => $marginMode,
            'side' => $side,
            'hedged' => $hedged,
            'percentage' => $percentage,
        );
    }

    public function parse_position_risk($position, $market = null)
    {
        $marketId = $this->safe_string($position, 'symbol');
        $market = $this->safe_market($marketId, $market);
        $symbol = $this->safe_string($market, 'symbol');
        $leverageBrackets = $this->safe_value($this->options, 'leverageBrackets', array());
        $leverageBracket = $this->safe_value($leverageBrackets, $symbol, array());
        $notionalString = $this->safe_string_2($position, 'notional', 'notionalValue');
        $notionalStringAbs = Precise::string_abs($notionalString);
        $maintenanceMarginPercentageString = null;
        for ($i = 0; $i < count($leverageBracket); $i++) {
            $bracket = $leverageBracket[$i];
            if (Precise::string_lt($notionalStringAbs, $bracket[0])) {
                break;
            }
            $maintenanceMarginPercentageString = $bracket[1];
        }
        $notional = $this->parse_number($notionalStringAbs);
        $contractsAbs = Precise::string_abs($this->safe_string($position, 'positionAmt'));
        $contracts = $this->parse_number($contractsAbs);
        $unrealizedPnlString = $this->safe_string($position, 'unRealizedProfit');
        $unrealizedPnl = $this->parse_number($unrealizedPnlString);
        $leverageString = $this->safe_string($position, 'leverage');
        $leverage = intval($leverageString);
        $liquidationPriceString = $this->omit_zero($this->safe_string($position, 'liquidationPrice'));
        $liquidationPrice = $this->parse_number($liquidationPriceString);
        $collateralString = null;
        $marginMode = $this->safe_string($position, 'marginType');
        $side = null;
        if (Precise::string_gt($notionalString, '0')) {
            $side = 'long';
        } elseif (Precise::string_lt($notionalString, '0')) {
            $side = 'short';
        }
        $entryPriceString = $this->safe_string($position, 'entryPrice');
        $entryPrice = $this->parse_number($entryPriceString);
        $contractSize = $this->safe_value($market, 'contractSize');
        $contractSizeString = $this->number_to_string($contractSize);
        $linear = (is_array($position) && array_key_exists('notional', $position));
        if ($marginMode === 'cross') {
            $precision = $this->safe_value($market, 'precision', array());
            if ($linear) {
                $onePlusMaintenanceMarginPercentageString = null;
                $entryPriceSignString = $entryPriceString;
                if ($side === 'short') {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_add('1', $maintenanceMarginPercentageString);
                    $entryPriceSignString = Precise::string_mul('-1', $entryPriceSignString);
                } else {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_add('-1', $maintenanceMarginPercentageString);
                }
                $inner = Precise::string_mul($liquidationPriceString, $onePlusMaintenanceMarginPercentageString);
                $leftSide = Precise::string_add($inner, $entryPriceSignString);
                $pricePrecision = $this->safe_integer($precision, 'price');
                $quotePrecision = $this->safe_integer($precision, 'quote', $pricePrecision);
                if ($quotePrecision !== null) {
                    $collateralString = Precise::string_div(Precise::string_mul($leftSide, $contractsAbs), '1', $quotePrecision);
                }
            } else {
                $onePlusMaintenanceMarginPercentageString = null;
                $entryPriceSignString = $entryPriceString;
                if ($side === 'short') {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_sub('1', $maintenanceMarginPercentageString);
                } else {
                    $onePlusMaintenanceMarginPercentageString = Precise::string_sub('-1', $maintenanceMarginPercentageString);
                    $entryPriceSignString = Precise::string_mul('-1', $entryPriceSignString);
                }
                $leftSide = Precise::string_mul($contractsAbs, $contractSizeString);
                $rightSide = Precise::string_sub(Precise::string_div('1', $entryPriceSignString), Precise::string_div($onePlusMaintenanceMarginPercentageString, $liquidationPriceString));
                $basePrecision = $this->safe_integer($precision, 'base');
                if ($basePrecision !== null) {
                    $collateralString = Precise::string_div(Precise::string_mul($leftSide, $rightSide), '1', $basePrecision);
                }
            }
        } else {
            $collateralString = $this->safe_string($position, 'isolatedMargin');
        }
        $collateralString = ($collateralString === null) ? '0' : $collateralString;
        $collateral = $this->parse_number($collateralString);
        $markPrice = $this->parse_number($this->omit_zero($this->safe_string($position, 'markPrice')));
        $timestamp = $this->safe_integer($position, 'updateTime');
        if ($timestamp === 0) {
            $timestamp = null;
        }
        $maintenanceMarginPercentage = $this->parse_number($maintenanceMarginPercentageString);
        $maintenanceMarginString = Precise::string_mul($maintenanceMarginPercentageString, $notionalStringAbs);
        $maintenanceMargin = $this->parse_number($maintenanceMarginString);
        $initialMarginPercentageString = Precise::string_div('1', $leverageString, 8);
        $rational = (fmod(1000, $leverage)) === 0;
        if (!$rational) {
            $initialMarginPercentageString = Precise::string_add($initialMarginPercentageString, '1e-8');
        }
        $initialMarginString = Precise::string_div(Precise::string_mul($notionalStringAbs, $initialMarginPercentageString), '1', 8);
        $initialMargin = $this->parse_number($initialMarginString);
        $marginRatio = null;
        $percentage = null;
        if (!Precise::string_equals($collateralString, '0')) {
            $marginRatio = $this->parse_number(Precise::string_div(Precise::string_add(Precise::string_div($maintenanceMarginString, $collateralString), '5e-5'), '1', 4));
            $percentage = $this->parse_number(Precise::string_mul(Precise::string_div($unrealizedPnlString, $initialMarginString, 4), '100'));
        }
        $positionSide = $this->safe_string($position, 'positionSide');
        $hedged = $positionSide !== 'BOTH';
        return array(
            'info' => $position,
            'symbol' => $symbol,
            'contracts' => $contracts,
            'contractSize' => $contractSize,
            'unrealizedPnl' => $unrealizedPnl,
            'leverage' => $this->parse_number($leverageString),
            'liquidationPrice' => $liquidationPrice,
            'collateral' => $collateral,
            'notional' => $notional,
            'markPrice' => $markPrice,
            'entryPrice' => $entryPrice,
            'timestamp' => $timestamp,
            'initialMargin' => $initialMargin,
            'initialMarginPercentage' => $this->parse_number($initialMarginPercentageString),
            'maintenanceMargin' => $maintenanceMargin,
            'maintenanceMarginPercentage' => $maintenanceMarginPercentage,
            'marginRatio' => $marginRatio,
            'datetime' => $this->iso8601($timestamp),
            'marginMode' => $marginMode,
            'marginType' => $marginMode, // deprecated
            'side' => $side,
            'hedged' => $hedged,
            'percentage' => $percentage,
        );
    }

    public function load_leverage_brackets($reload = false, $params = array())
    {
        $this->load_markets();
        $leverageBrackets = $this->safe_value($this->options, 'leverageBrackets');
        if (($leverageBrackets === null) || ($reload)) {
            $method = null;
            $defaultType = $this->safe_string($this->options, 'defaultType', 'future');
            $type = $this->safe_string($params, 'type', $defaultType);
            $query = $this->omit($params, 'type');
            if ($type === 'future') {
                $method = 'fapiPrivateGetLeverageBracket';
            } elseif ($type === 'delivery') {
                $method = 'dapiPrivateV2GetLeverageBracket';
            } else {
                throw new NotSupported($this->id . ' loadLeverageBrackets() supports linear and inverse contracts only');
            }
            $response = $this->$method($query);
            $this->options['leverageBrackets'] = array();
            for ($i = 0; $i < count($response); $i++) {
                $entry = $response[$i];
                $marketId = $this->safe_string($entry, 'symbol');
                $symbol = $this->safe_symbol($marketId);
                $brackets = $this->safe_value($entry, 'brackets', array());
                $result = array();
                for ($j = 0; $j < count($brackets); $j++) {
                    $bracket = $brackets[$j];
                    $floorValue = $this->safe_string_2($bracket, 'notionalFloor', 'qtyFloor');
                    $maintenanceMarginPercentage = $this->safe_string($bracket, 'maintMarginRatio');
                    $result[] = array($floorValue, $maintenanceMarginPercentage);
                }
                $this->options['leverageBrackets'][$symbol] = $result;
            }
        }
        return $this->options['leverageBrackets'];
    }

    public function fetch_leverage_tiers($symbols = null, $params = array())
    {
        $this->load_markets();
        list($type, $query) = $this->handle_market_type_and_params('fetchLeverageTiers', null, $params);
        $method = null;
        if ($type === 'future') {
            $method = 'fapiPrivateGetLeverageBracket';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateV2GetLeverageBracket';
        } else {
            throw new NotSupported($this->id . ' fetchLeverageTiers() supports linear and inverse contracts only');
        }
        $response = $this->$method($query);
        return $this->parse_leverage_tiers($response, $symbols, 'symbol');
    }

    public function parse_market_leverage_tiers($info, $market)
    {
        $marketId = $this->safe_string($info, 'symbol');
        $market = $this->safe_market($marketId, $market);
        $brackets = $this->safe_value($info, 'brackets', array());
        $tiers = array();
        for ($j = 0; $j < count($brackets); $j++) {
            $bracket = $brackets[$j];
            $tiers[] = array(
                'tier' => $this->safe_number($bracket, 'bracket'),
                'currency' => $market['quote'],
                'minNotional' => $this->safe_number_2($bracket, 'notionalFloor', 'qtyFloor'),
                'maxNotional' => $this->safe_number($bracket, 'notionalCap', 'qtyCap'),
                'maintenanceMarginRate' => $this->safe_number($bracket, 'maintMarginRatio'),
                'maxLeverage' => $this->safe_number($bracket, 'initialLeverage'),
                'info' => $bracket,
            );
        }
        return $tiers;
    }

    public function fetch_positions($symbols = null, $params = array())
    {
        $defaultMethod = $this->safe_string($this->options, 'fetchPositions', 'positionRisk');
        if ($defaultMethod === 'positionRisk') {
            return $this->fetch_positions_risk($symbols, $params);
        } elseif ($defaultMethod === 'account') {
            return $this->fetch_account_positions($symbols, $params);
        } else {
            throw new NotSupported($this->id . '.options["fetchPositions"] = "' . $defaultMethod . '" is invalid, please choose between "account" and "positionRisk"');
        }
    }

    public function fetch_account_positions($symbols = null, $params = array())
    {
        if ($symbols !== null) {
            if (gettype($symbols) !== 'array' || array_keys($symbols) !== array_keys(array_keys($symbols))) {
                throw new ArgumentsRequired($this->id . ' fetchPositions() requires an array argument for symbols');
            }
        }
        $this->load_markets();
        $this->load_leverage_brackets();
        $method = null;
        $defaultType = $this->safe_string($this->options, 'defaultType', 'future');
        $type = $this->safe_string($params, 'type', $defaultType);
        $query = $this->omit($params, 'type');
        if ($type === 'future') {
            $method = 'fapiPrivateGetAccount';
        } elseif ($type === 'delivery') {
            $method = 'dapiPrivateGetAccount';
        } else {
            throw new NotSupported($this->id . ' fetchPositions() supports linear and inverse contracts only');
        }
        $account = $this->$method($query);
        $result = $this->parse_account_positions($account);
        return $this->filter_by_array($result, 'symbol', $symbols, false);
    }

    public function fetch_positions_risk($symbols = null, $params = array())
    {
        if ($symbols !== null) {
            if (gettype($symbols) !== 'array' || array_keys($symbols) !== array_keys(array_keys($symbols))) {
                throw new ArgumentsRequired($this->id . ' fetchPositionsRisk() requires an array argument for symbols');
            }
        }
        $this->load_markets();
        $this->load_leverage_brackets();
        $request = array();
        $method = null;
        $defaultType = 'future';
        $defaultType = $this->safe_string($this->options, 'defaultType', $defaultType);
        $type = $this->safe_string($params, 'type', $defaultType);
        $params = $this->omit($params, 'type');
        if (($type === 'future') || ($type === 'linear')) {
            $method = 'fapiPrivateGetPositionRisk';
        } elseif (($type === 'delivery') || ($type === 'inverse')) {
            $method = 'dapiPrivateGetPositionRisk';
        } else {
            throw new NotSupported($this->id . ' fetchPositionsRisk() supports linear and inverse contracts only');
        }
        $response = $this->$method(array_merge($request, $params));
        $result = array();
        for ($i = 0; $i < count($response); $i++) {
            $parsed = $this->parse_position_risk($response[$i]);
            $result[] = $parsed;
        }
        return $this->filter_by_array($result, 'symbol', $symbols, false);
    }

    public function fetch_funding_history($symbol = null, $since = null, $limit = null, $params = array())
    {
        $this->load_markets();
        $market = null;
        $method = null;
        $defaultType = 'future';
        $request = array(
            'incomeType' => 'FUNDING_FEE', // "TRANSFER"，"WELCOME_BONUS", "REALIZED_PNL"，"FUNDING_FEE", "COMMISSION" and "INSURANCE_CLEAR"
        );
        if ($symbol !== null) {
            $market = $this->market($symbol);
            $request['symbol'] = $market['id'];
            if ($market['linear']) {
                $defaultType = 'future';
            } elseif ($market['inverse']) {
                $defaultType = 'delivery';
            } else {
                throw new NotSupported($this->id . ' fetchFundingHistory() supports linear and inverse contracts only');
            }
        }
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $defaultType = $this->safe_string_2($this->options, 'fetchFundingHistory', 'defaultType', $defaultType);
        $type = $this->safe_string($params, 'type', $defaultType);
        $params = $this->omit($params, 'type');
        if (($type === 'future') || ($type === 'linear')) {
            $method = 'fapiPrivateGetIncome';
        } elseif (($type === 'delivery') || ($type === 'inverse')) {
            $method = 'dapiPrivateGetIncome';
        } else {
            throw new NotSupported($this->id . ' fetchFundingHistory() supports linear and inverse contracts only');
        }
        $response = $this->$method(array_merge($request, $params));
        return $this->parse_incomes($response, $market, $since, $limit);
    }

    public function set_leverage($leverage, $symbol = null, $params = array())
    {
        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' setLeverage() requires a $symbol argument');
        }
        if (($leverage < 1) || ($leverage > 125)) {
            throw new BadRequest($this->id . ' $leverage should be between 1 and 125');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $method = null;
        if ($market['linear']) {
            $method = 'fapiPrivatePostLeverage';
        } elseif ($market['inverse']) {
            $method = 'dapiPrivatePostLeverage';
        } else {
            throw new NotSupported($this->id . ' setLeverage() supports linear and inverse contracts only');
        }
        $request = array(
            'symbol' => $market['id'],
            'leverage' => $leverage,
        );
        return $this->$method(array_merge($request, $params));
    }

    public function set_margin_mode($marginMode, $symbol = null, $params = array())
    {
        if ($symbol === null) {
            throw new ArgumentsRequired($this->id . ' setMarginMode() requires a $symbol argument');
        }
        $marginMode = strtoupper($marginMode);
        if ($marginMode === 'CROSS') {
            $marginMode = 'CROSSED';
        }
        if (($marginMode !== 'ISOLATED') && ($marginMode !== 'CROSSED')) {
            throw new BadRequest($this->id . ' $marginMode must be either isolated or cross');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $method = null;
        if ($market['linear']) {
            $method = 'fapiPrivatePostMarginType';
        } elseif ($market['inverse']) {
            $method = 'dapiPrivatePostMarginType';
        } else {
            throw new NotSupported($this->id . ' setMarginMode() supports linear and inverse contracts only');
        }
        $request = array(
            'symbol' => $market['id'],
            'marginType' => $marginMode,
        );
        $response = null;
        try {
            $response = $this->$method(array_merge($request, $params));
        } catch (Exception $e) {
            if ($e instanceof MarginModeAlreadySet) {
                $throwMarginModeAlreadySet = $this->safe_value($this->options, 'throwMarginModeAlreadySet', false);
                if ($throwMarginModeAlreadySet) {
                    throw $e;
                } else {
                    $response = array('code' => -4046, 'msg' => 'No need to change margin type.');
                }
            }
        }
        return $response;
    }

    public function set_position_mode($hedged, $symbol = null, $params = array())
    {
        $defaultType = $this->safe_string($this->options, 'defaultType', 'future');
        $type = $this->safe_string($params, 'type', $defaultType);
        $params = $this->omit($params, array('type'));
        $dualSidePosition = null;
        if ($hedged) {
            $dualSidePosition = 'true';
        } else {
            $dualSidePosition = 'false';
        }
        $request = array(
            'dualSidePosition' => $dualSidePosition,
        );
        $method = null;
        if ($type === 'delivery') {
            $method = 'dapiPrivatePostPositionSideDual';
        } else {
            $method = 'fapiPrivatePostPositionSideDual';
        }
        return $this->$method(array_merge($request, $params));
    }

    public function sign($path, $api = 'public', $method = 'GET', $params = array(), $headers = null, $body = null)
    {
        if (!(is_array($this->urls['api']) && array_key_exists($api, $this->urls['api']))) {
            throw new NotSupported($this->id . ' does not have a testnet/sandbox URL for ' . $api . ' endpoints');
        }
        $url = $this->urls['api'][$api];
        $url .= '/' . $path;
        if ($api === 'wapi') {
            $url .= '.html';
        }
        if ($path === 'historicalTrades') {
            if ($this->apiKey) {
                $headers = array(
                    'X-MBX-APIKEY' => $this->apiKey,
                );
            } else {
                throw new AuthenticationError($this->id . ' historicalTrades endpoint requires `apiKey` credential');
            }
        }
        $userDataStream = ($path === 'userDataStream') || ($path === 'listenKey');
        if ($userDataStream) {
            if ($this->apiKey) {
                // v1 special case for $userDataStream
                $headers = array(
                    'X-MBX-APIKEY' => $this->apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                );
                if ($method !== 'GET') {
                    $body = $this->urlencode($params);
                }
            } else {
                throw new AuthenticationError($this->id . ' $userDataStream endpoint requires `apiKey` credential');
            }
        } elseif (($api === 'private') || ($api === 'sapi' && $path !== 'system/status') || ($api === 'sapiV3') || ($api === 'wapi' && $path !== 'systemStatus') || ($api === 'dapiPrivate') || ($api === 'dapiPrivateV2') || ($api === 'fapiPrivate') || ($api === 'fapiPrivateV2')) {
            $this->check_required_credentials();
            $query = null;
            $defaultRecvWindow = $this->safe_integer($this->options, 'recvWindow');
            $extendedParams = array_merge(array(
                'timestamp' => $this->nonce(),
            ), $params);
            if ($defaultRecvWindow !== null) {
                $extendedParams['recvWindow'] = $defaultRecvWindow;
            }
            $recvWindow = $this->safe_integer($params, 'recvWindow');
            if ($recvWindow !== null) {
                $extendedParams['recvWindow'] = $recvWindow;
            }
            if (($api === 'sapi') && ($path === 'asset/dust')) {
                $query = $this->urlencode_with_array_repeat($extendedParams);
            } elseif (($path === 'batchOrders') || (mb_strpos($path, 'sub-account') !== false) || ($path === 'capital/withdraw/apply')) {
                $query = $this->rawencode($extendedParams);
            } else {
                $query = $this->urlencode($extendedParams);
            }
            $signature = $this->hmac($this->encode($query), $this->encode($this->secret));
            $query .= '&' . 'signature=' . $signature;
            $headers = array(
                'X-MBX-APIKEY' => $this->apiKey,
            );
            if (($method === 'GET') || ($method === 'DELETE') || ($api === 'wapi')) {
                $url .= '?' . $query;
            } else {
                $body = $query;
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }
        } else {
            if ($params) {
                $url .= '?' . $this->urlencode($params);
            }
        }
        return array('url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers);
    }

    public function handle_errors($code, $reason, $url, $method, $headers, $body, $response, $requestHeaders, $requestBody)
    {
        if (($code === 418) || ($code === 429)) {
            throw new DDoSProtection($this->id . ' ' . (string) $code . ' ' . $reason . ' ' . $body);
        }
        if ($code >= 400) {
            if (mb_strpos($body, 'Price * QTY is zero or less') !== false) {
                throw new InvalidOrder($this->id . ' order cost = amount * price is zero or less ' . $body);
            }
            if (mb_strpos($body, 'LOT_SIZE') !== false) {
                throw new InvalidOrder($this->id . ' order amount should be evenly divisible by lot size ' . $body);
            }
            if (mb_strpos($body, 'PRICE_FILTER') !== false) {
                throw new InvalidOrder($this->id . ' order price is invalid, i.e. exceeds allowed price precision, exceeds min price or max price limits or is invalid value in general, use $this->price_to_precision(symbol, amount) ' . $body);
            }
        }
        if ($response === null) {
            return; // fallback to default $error handler
        }
        $success = $this->safe_value($response, 'success', true);
        if (!$success) {
            $message = $this->safe_string($response, 'msg');
            $parsedMessage = null;
            if ($message !== null) {
                try {
                    $parsedMessage = json_decode($message, $as_associative_array = true);
                } catch (Exception $e) {
                    $parsedMessage = null;
                }
                if ($parsedMessage !== null) {
                    $response = $parsedMessage;
                }
            }
        }
        $message = $this->safe_string($response, 'msg');
        if ($message !== null) {
            $this->throw_exactly_matched_exception($this->exceptions['exact'], $message, $this->id . ' ' . $message);
            $this->throw_broadly_matched_exception($this->exceptions['broad'], $message, $this->id . ' ' . $message);
        }
        $error = $this->safe_string($response, 'code');
        if ($error !== null) {
            if (($error === '200') || Precise::string_equals($error, '0')) {
                return null;
            }
            if (($error === '-2015') && $this->options['hasAlreadyAuthenticatedSuccessfully']) {
                throw new DDoSProtection($this->id . ' ' . $body);
            }
            $feedback = $this->id . ' ' . $body;
            if ($message === 'No need to change margin type.') {
                throw new MarginModeAlreadySet($feedback);
            }
            $this->throw_exactly_matched_exception($this->exceptions['exact'], $error, $feedback);
            throw new ExchangeError($feedback);
        }
        if (!$success) {
            throw new ExchangeError($this->id . ' ' . $body);
        }
    }

    public function calculate_rate_limiter_cost($api, $method, $path, $params, $config = array(), $context = array())
    {
        if ((is_array($config) && array_key_exists('noCoin', $config)) && !(is_array($params) && array_key_exists('coin', $params))) {
            return $config['noCoin'];
        } elseif ((is_array($config) && array_key_exists('noSymbol', $config)) && !(is_array($params) && array_key_exists('symbol', $params))) {
            return $config['noSymbol'];
        } elseif ((is_array($config) && array_key_exists('noPoolId', $config)) && !(is_array($params) && array_key_exists('poolId', $params))) {
            return $config['noPoolId'];
        } elseif ((is_array($config) && array_key_exists('byLimit', $config)) && (is_array($params) && array_key_exists('limit', $params))) {
            $limit = $params['limit'];
            $byLimit = $config['byLimit'];
            for ($i = 0; $i < count($byLimit); $i++) {
                $entry = $byLimit[$i];
                if ($limit <= $entry[0]) {
                    return $entry[1];
                }
            }
        }
        return $this->safe_integer($config, 'cost', 1);
    }

    public function request($path, $api = 'public', $method = 'GET', $params = array(), $headers = null, $body = null, $config = array(), $context = array())
    {
        $response = $this->fetch2($path, $api, $method, $params, $headers, $body, $config, $context);
        if (($api === 'private') || ($api === 'wapi')) {
            $this->options['hasAlreadyAuthenticatedSuccessfully'] = true;
        }
        return $response;
    }

    public function modify_margin_helper($symbol, $amount, $addOrReduce, $params = array())
    {
        $defaultType = $this->safe_string($this->options, 'defaultType', 'future');
        if ($defaultType === 'spot') {
            $defaultType = 'future';
        }
        $type = $this->safe_string($params, 'type', $defaultType);
        if (($type === 'margin') || ($type === 'spot')) {
            throw new NotSupported($this->id . ' add / reduce margin only supported with $type future or delivery');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $amount = $this->amount_to_precision($symbol, $amount);
        $request = array(
            'type' => $addOrReduce,
            'symbol' => $market['id'],
            'amount' => $amount,
        );
        $method = null;
        $code = null;
        if ($type === 'future') {
            $method = 'fapiPrivatePostPositionMargin';
            $code = $market['quote'];
        } else {
            $method = 'dapiPrivatePostPositionMargin';
            $code = $market['base'];
        }
        $response = $this->$method(array_merge($request, $params));
        return array_merge($this->parse_margin_modification($response, $market), array(
            'code' => $code,
        ));
    }

    public function parse_margin_modification($data, $market = null)
    {
        $rawType = $this->safe_integer($data, 'type');
        $resultType = ($rawType === 1) ? 'add' : 'reduce';
        $resultAmount = $this->safe_number($data, 'amount');
        $errorCode = $this->safe_string($data, 'code');
        $status = ($errorCode === '200') ? 'ok' : 'failed';
        return array(
            'info' => $data,
            'type' => $resultType,
            'amount' => $resultAmount,
            'code' => null,
            'symbol' => $market['symbol'],
            'status' => $status,
        );
    }

    public function reduce_margin($symbol, $amount, $params = array())
    {
        return $this->modify_margin_helper($symbol, $amount, 2, $params);
    }

    public function add_margin($symbol, $amount, $params = array())
    {
        return $this->modify_margin_helper($symbol, $amount, 1, $params);
    }

    public function fetch_borrow_rate($code, $params = array())
    {
        $this->load_markets();
        $currency = $this->currency($code);
        $request = array(
            'asset' => $currency['id'],
        );
        $response = $this->sapiGetMarginInterestRateHistory(array_merge($request, $params));
        $rate = $this->safe_value($response, 0);
        return $this->parse_borrow_rate($rate);
    }

    public function fetch_borrow_rate_history($code, $since = null, $limit = null, $params = array())
    {
        $this->load_markets();
        if ($limit === null) {
            $limit = 93;
        } elseif ($limit > 93) {
            throw new BadRequest($this->id . ' fetchBorrowRateHistory() $limit parameter cannot exceed 92');
        }
        $currency = $this->currency($code);
        $request = array(
            'asset' => $currency['id'],
            'limit' => $limit,
        );
        if ($since !== null) {
            $request['startTime'] = $since;
            $endTime = $this->sum($since, $limit * 86400000) - 1; // required when startTime is further than 93 days in the past
            $now = $this->milliseconds();
            $request['endTime'] = min($endTime, $now); // cannot have an $endTime later than current time
        }
        $response = $this->sapiGetMarginInterestRateHistory(array_merge($request, $params));
        return $this->parse_borrow_rate_history($response);
    }

    public function parse_borrow_rate_history($response, $code, $since, $limit)
    {
        $result = array();
        for ($i = 0; $i < count($response); $i++) {
            $item = $response[$i];
            $borrowRate = $this->parse_borrow_rate($item);
            $result[] = $borrowRate;
        }
        $sorted = $this->sort_by($result, 'timestamp');
        return $this->filter_by_currency_since_limit($sorted, $code, $since, $limit);
    }

    public function parse_borrow_rate($info, $currency = null)
    {
        $timestamp = $this->safe_number($info, 'timestamp');
        $currency = $this->safe_string($info, 'asset');
        return array(
            'currency' => $this->safe_currency_code($currency),
            'rate' => $this->safe_number($info, 'dailyInterestRate'),
            'period' => 86400000,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'info' => $info,
        );
    }

    public function create_gift_code($code, $amount, $params = array())
    {
        $this->load_markets();
        $currency = $this->currency($code);
        $request = array(
            'token' => $currency['id'],
            'amount' => $amount,
        );
        $response = $this->sapiPostGiftcardCreateCode(array_merge($request, $params));
        $data = $this->safe_value($response, 'data');
        $giftcardCode = $this->safe_string($data, 'code');
        $id = $this->safe_string($data, 'referenceNo');
        return array(
            'info' => $response,
            'id' => $id,
            'code' => $giftcardCode,
            'currency' => $code,
            'amount' => $amount,
        );
    }

    public function redeem_gift_code($giftcardCode, $params = array())
    {
        $request = array(
            'code' => $giftcardCode,
        );
        $response = $this->sapiPostGiftcardRedeemCode(array_merge($request, $params));
        return $response;
    }

    public function verify_gift_code($id, $params = array())
    {
        $request = array(
            'referenceNo' => $id,
        );
        $response = $this->sapiGetGiftcardVerify(array_merge($request, $params));
        return $response;
    }

    public function fetch_borrow_interest($code = null, $symbol = null, $since = null, $limit = null, $params = array())
    {
        $this->load_markets();
        $request = array();
        $market = null;
        if ($code !== null) {
            $currency = $this->currency($code);
            $request['asset'] = $currency['id'];
        }
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        if ($limit !== null) {
            $request['size'] = $limit;
        }
        if ($symbol !== null) { // Isolated
            $market = $this->market($symbol);
            $request['isolatedSymbol'] = $market['id'];
        }
        $response = $this->sapiGetMarginInterestHistory(array_merge($request, $params));
        $rows = $this->safe_value($response, 'rows');
        $interest = $this->parse_borrow_interests($rows, $market);
        return $this->filter_by_currency_since_limit($interest, $code, $since, $limit);
    }

    public function parse_borrow_interest($info, $market)
    {
        $symbol = $this->safe_string($info, 'isolatedSymbol');
        $timestamp = $this->safe_number($info, 'interestAccuredTime');
        $marginMode = ($symbol === null) ? 'cross' : 'isolated';
        return array(
            'account' => ($symbol === null) ? 'cross' : $symbol,
            'symbol' => $symbol,
            'marginMode' => $marginMode,
            'currency' => $this->safe_currency_code($this->safe_string($info, 'asset')),
            'interest' => $this->safe_number($info, 'interest'),
            'interestRate' => $this->safe_number($info, 'interestRate'),
            'amountBorrowed' => $this->safe_number($info, 'principal'),
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'info' => $info,
        );
    }

    public function repay_margin($code, $amount, $symbol = null, $params = array())
    {
        $this->load_markets();
        $market = null;
        if ($symbol !== null) {
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
        }
        $currency = $this->currency($code);
        $request = array(
            'asset' => $currency['id'],
            'amount' => $this->currency_to_precision($code, $amount),
        );
        $defaultMarginMode = $this->safe_string_2($this->options, 'defaultMarginMode', 'marginMode', 'cross');
        $marginMode = $this->safe_string($params, 'marginMode', $defaultMarginMode); // cross or isolated
        if ($marginMode === 'isolated') {
            if ($symbol === null) {
                throw new ArgumentsRequired($this->id . ' repayMargin() requires a $symbol argument for isolated margin');
            }
            $request['isIsolated'] = 'TRUE';
            $request['symbol'] = $market['id'];
        }
        $params = $this->omit($params, 'marginMode');
        $response = $this->sapiPostMarginRepay(array_merge($request, $params));
        $transaction = $this->parse_margin_loan($response, $currency);
        return array_merge($transaction, array(
            'amount' => $amount,
            'symbol' => $symbol,
        ));
    }

    public function borrow_margin($code, $amount, $symbol = null, $params = array())
    {
        $this->load_markets();
        $market = null;
        if ($symbol !== null) {
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
        }
        $currency = $this->currency($code);
        $request = array(
            'asset' => $currency['id'],
            'amount' => $this->currency_to_precision($code, $amount),
        );
        $defaultMarginMode = $this->safe_string_2($this->options, 'defaultMarginMode', 'marginMode', 'cross');
        $marginMode = $this->safe_string($params, 'marginMode', $defaultMarginMode); // cross or isolated
        if ($marginMode === 'isolated') {
            if ($symbol === null) {
                throw new ArgumentsRequired($this->id . ' borrowMargin() requires a $symbol argument for isolated margin');
            }
            $request['isIsolated'] = 'TRUE';
            $request['symbol'] = $market['id'];
        }
        $params = $this->omit($params, 'marginMode');
        $response = $this->sapiPostMarginLoan(array_merge($request, $params));
        $transaction = $this->parse_margin_loan($response, $currency);
        return array_merge($transaction, array(
            'amount' => $amount,
            'symbol' => $symbol,
        ));
    }

    public function parse_margin_loan($info, $currency = null)
    {
        return array(
            'id' => $this->safe_integer($info, 'tranId'),
            'currency' => $this->safe_currency_code(null, $currency),
            'amount' => null,
            'symbol' => null,
            'timestamp' => null,
            'datetime' => null,
            'info' => $info,
        );
    }

    public function fetch_open_interest_history($symbol, $timeframe = '5m', $since = null, $limit = null, $params = array())
    {
        if ($timeframe === '1m') {
            throw new BadRequest($this->id . 'fetchOpenInterestHistory cannot use the 1m timeframe');
        }
        $this->load_markets();
        $market = $this->market($symbol);
        $request = array(
            'period' => $this->timeframes[$timeframe],
        );
        if ($limit !== null) {
            $request['limit'] = $limit;
        }
        $symbolKey = $market['linear'] ? 'symbol' : 'pair';
        $request[$symbolKey] = $market['id'];
        if ($market['delivery']) {
            $request['contractType'] = $this->safe_string($params, 'contractType', 'CURRENT_QUARTER');
        }
        if ($since !== null) {
            $request['startTime'] = $since;
        }
        $until = $this->safe_integer_2($params, 'until', 'till'); // unified in milliseconds
        $endTime = $this->safe_string($params, 'endTime', $until); // exchange-specific in milliseconds
        $params = $this->omit($params, array('endTime', 'until', 'till'));
        if ($endTime) {
            $request['endTime'] = $endTime;
        } elseif ($since) {
            if ($limit === null) {
                $limit = 30; // Exchange default
            }
            $duration = $this->parse_timeframe($timeframe);
            $request['endTime'] = $this->sum($since, $duration * $limit * 1000);
        }
        $method = 'fapiDataGetOpenInterestHist';
        if ($market['inverse']) {
            $method = 'dapiDataGetOpenInterestHist';
        }
        $response = $this->$method(array_merge($request, $params));
        return $this->parse_open_interests($response, $symbol, $since, $limit);
    }

    public function parse_open_interest($interest, $market = null)
    {
        $timestamp = $this->safe_integer($interest, 'timestamp');
        $id = $this->safe_string($interest, 'symbol');
        $market = $this->safe_market($id, $market);
        return array(
            'symbol' => $this->safe_symbol($id),
            'baseVolume' => $this->safe_number($interest, 'sumOpenInterest'),
            'quoteVolume' => $this->safe_number($interest, 'sumOpenInterestValue'),
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'info' => $interest,
        );
    }
}
