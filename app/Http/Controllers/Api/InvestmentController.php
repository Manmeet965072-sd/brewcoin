<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\Currencies;
use App\Models\Order;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Http;
use stdClass;


class InvestmentController extends Controller
{
    /**
     * @OA\Get(
     ** path="/api/my-investments",
     *   summary="Investments Listing",
     *   operationId="my-investments",
     *   tags={"Investment Manager"},
     *  @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="currency",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function myInvestments(Request $request)
    {
        try {
            $investments = [];
            $total_investments = [];
            if (!empty($request->user_id)) {
                $investments_buy = DB::table('orders')
                    ->where('user_id', $request->user_id)->where('purchase_type', "Buy")->where('status', "Completed")
                    ->join('coins', 'coins.id', '=', 'orders.coin_id')
                    ->groupBy('coins.name')
                    ->selectRaw('coins.coin_id as coin_id,coins.name as name,coins.symbol as symbol,coins.icon as image,sum(amount) as total_amount,sum(qty) as total_qty')->get();

                $investments_sell = DB::table('orders')
                    ->where('user_id', $request->user_id)->where('purchase_type', "Sell")->where('status', "Completed")
                    ->join('coins', 'coins.id', '=', 'orders.coin_id')
                    ->groupBy('coins.name')
                    ->selectRaw('coins.coin_id as coin_id,coins.name as name,coins.symbol as symbol,coins.icon as image,sum(amount) as total_amount,sum(qty) as total_qty')->get();

                //Change Currency
                if (!empty($request->currency)) {
                    $currency = Currencies::where('code', strtoupper($request->currency))->first();
                    foreach ($investments_buy as $value) {
                        $value->total_amount = $value->total_amount * $currency->rate;
                    }
                    foreach ($investments_sell as $value) {
                        $value->total_amount = $value->total_amount * $currency->rate;
                    }
                }
                //Assets having both buy and sell 
                foreach ($investments_buy as $key1 => $buy) {
                    foreach ($investments_sell as $key => $sell) {
                        if ($buy->name == $sell->name) {
                            $investments[$key]['coin_id'] = $buy->coin_id;
                            $investments[$key]['name'] = $buy->name;
                            $investments[$key]['symbol'] = $buy->symbol;
                            $investments[$key]['image'] = $buy->image;
                            $investments[$key]['total_amount'] =  $buy->total_amount - $sell->total_amount;
                            $investments[$key]['total_qty'] =  $buy->total_qty - $sell->total_qty;
                            $investments_buy->forget($key1);
                        }
                    }
                }
                $investments_buy = $investments_buy->toArray();
                $buys = [];
                foreach ($investments_buy as $key => $buy)

                    $buys[$key] = (array)$buy;

                //All investments including only buys and both buy and sell
                $investments = array_merge($investments, $buys);

                //To get live price of assets
                $ids = array_column($investments, 'coin_id');
                $ids = implode(',', $ids);
                $live_price = Http::get("https://pro-api.coingecko.com/api/v3/simple/price?vs_currencies=" . $request->currency . "&ids=" . $ids."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
                $live_price = $live_price->getBody()->getContents();


                //Total investments (sum of investments of individual coins) which appears at top.
                $currency = strtolower($request->currency);
                foreach ($investments as $key => $investment) {

                    $coin_id = $investments[$key]['coin_id'];
                    $investments[$key]['live_price'] = json_decode($live_price)->$coin_id->$currency;
                    $investments[$key]['live_amount'] = (float)$investments[$key]['total_qty'] * $investments[$key]['live_price'];
                    $investments[$key]['total_qty'] = (float)$investments[$key]['total_qty'];
                    $investments[$key]['return'] = ($investments[$key]['live_amount'] - $investments[$key]['total_amount']);
                    if ($investments[$key]['total_amount'] != 0)
                        $investments[$key]['return_percentage'] = ($investments[$key]['live_amount'] - $investments[$key]['total_amount']) / $investments[$key]['total_amount'] * 100;
                    else
                        $investments[$key]['return_percentage'] = 0;
                    if ((float)$investments[$key]['total_amount'] != 0)
                        $investments[$key]['average'] = ((float)$investments[$key]['total_amount'] / (float)$investments[$key]['total_qty']);
                    else
                        $investments[$key]['average'] = 0;
                }

                $total_investments['live_price'] = 0;
                $total_investments['live_amount'] = 0;
                $total_investments['total_qty'] = 0;
                $total_investments['return'] = 0;
                $total_investments['return_percentage'] = 0;
                $total_investments['total_amount'] = 0;
                $total_investments['average'] = 0;
                $count = count($investments);
                foreach ($investments as $key => $investment) {
                    $coin_id = $investments[$key]['coin_id'];
                    $total_investments['live_price'] += json_decode($live_price)->$coin_id->$currency;
                    $total_investments['live_amount'] += ((float)$investments[$key]['total_qty'] * $investments[$key]['live_price']);
                    $total_investments['total_qty'] += ((float)$investments[$key]['total_qty']);
                    $total_investments['total_amount'] += ($investments[$key]['total_amount']);
                    $total_investments['return'] += ($investments[$key]['live_amount'] - $investments[$key]['total_amount']);
                    if ($investments[$key]['total_amount'] != 0)
                        $total_investments['return_percentage'] += (($investments[$key]['live_amount'] - $investments[$key]['total_amount']) / $investments[$key]['total_amount'] * 100);
                    $total_investments['average'] +=  $investments[$key]['average'];
                }
                if ($count != 0)
                    $total_investments['return_percentage'] = $total_investments['return_percentage'] / $count;
            }

            //Send 10 top listed coins in this api.
            $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=10&page=1&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

            $response = json_decode($response->getBody()->getContents());
            $object = new stdClass();
            if (isset($response)) {
                foreach ($response as $key => $value) {
                    $object->$key = $value;
                }
                $response = collect($object)->all();
            }
            if (!empty($request->user_id)) {
                $watchlists = Watchlist::where('user_id', $request->user_id)->whereNotNull('coin_id')->pluck('coin_id')->toArray();
                if (isset($response)) {
                    foreach ($response as $res) {
                        if (isset($res->id)) {
                            if (in_array($res->id, $watchlists))
                                $res->is_added_to_watchlist = 1;
                            else
                                $res->is_added_to_watchlist = 0;
                        }
                    }
                }
            }

            return response()->json(['status' => 200, 'message' =>  __("locale.Investments fetched successfully!"), 'data' => $investments, 'total_investments' =>  $total_investments, 'coins' => $response]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Post(
     **  path="/api/investment-details",
     *   summary="Investment Details",
     *   operationId="investment-details",
     *   tags={"Investment Manager"},
     *   @OA\Parameter(
     *      name="coin_symbol",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *    ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/
    public function investmentDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_symbol' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $coin = Coin::where('symbol', $request->coin_symbol)->first();
            $orders = [];

            $orders = DB::table('orders')
                ->where('user_id', Auth::user()->id)->where('orders.coin_id', $coin->id)
                ->join('coins', 'coins.id', '=', 'orders.coin_id')
                ->selectRaw('coins.coin_id,coins.name,coins.symbol as coin_symbol,coins.icon as image,amount,qty,live_rate,target_rate,status,purchase_type,order_type,executed_at,orders.created_at')->orderBy('orders.created_at', 'desc')->paginate(10);

            return response()->json([
                'status' => 200,
                'message' => __("locale.Investment Details Fetched Successfully!"),
                'data' => $orders,
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
