<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\Currencies;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use stdClass;

class OrdersController extends Controller
{
    /**
     *   @OA\Post(
     **  path="/api/buy-coin",
     *   summary="Buy a coin",
     *   operationId="buy-coin",
     *   tags={"Orders Manager"},
     *   @OA\Parameter(
     *      name="coin_symbol",
     *      in="query",
     *      required=true,
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
     *   @OA\Parameter(
     *      name="live_rate",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="target_rate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="amount",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="qty",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="order_type",
     *      in="query",
     *      required=true,
     *      description="Instant/Limit",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="used_coupon_code",
     *      in="query",
     *      required=false,
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
    public function buy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_symbol' => 'required',
            'live_rate' => 'required',
            'amount' => 'required',
            'qty' => 'required',
            'order_type' => 'required',
            'currency' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $user = Auth::user();
            if ($request->amount > $user->balance)
                return response()->json(['status' => 400, 'message' => __('locale.Wallet balance is insufficient!')]);
            $order  = new Order();
            $order->user_id = Auth::user()->id;
            Http::get("https://talal.block-brew.com/get-coins");
            $coin = Coin::where('symbol', $request->coin_symbol)->first();

            // if ($coin == NULL)
            //     return response()->json(['status' => 400, 'message' => __('locale.No coin found with this symbol!')]);
            $order->coin_id = $coin->id;
            $order->live_rate = $request->live_rate;
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                $order->amount = $request->amount / $currency->rate;
            }
            $order->qty = $request->qty;
            $order->order_type = $request->order_type;
            $order->purchase_type = "Buy";

            $order->status = 'Completed';
            $order->is_executed = 1;
            $order->executed_at = Carbon::now();
            $order->save();

            $user->balance -= $order->amount;
            $user->save();
            return response()->json(['status' => 200, 'message' =>  __("locale.Coin purchased successfully!"), 'data' => $order, 'wallet_balance' => $user->balance]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Post(
     **  path="/api/get-purchased-coin-price",
     *   summary="Get purchased coin price",
     *   operationId="get-purchased-coin-price",
     *   tags={"Orders Manager"},
     *   @OA\Parameter(
     *      name="coin_id",
     *      in="query",
     *      required=true,
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
     *    ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/

    public function getPurchasedCoinPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_id' => 'required',
            'currency' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $coin = Coin::where('coin_id', $request->coin_id)->first();
            $past_orders_buy =  DB::table('orders')
                ->where('user_id', Auth::user()->id)->where('purchase_type', "Buy")->where('status', "Completed")->where('coin_id', $coin->id)
                ->selectRaw('sum(qty) as total_qty')->first();

            $past_orders_sell =  DB::table('orders')
                ->where('user_id', Auth::user()->id)->where('purchase_type', "Sell")->where('status', "Completed")->where('coin_id', $coin->id)
                ->selectRaw('sum(qty) as total_qty')->first();
            $qty_purchased = $past_orders_buy->total_qty - $past_orders_sell->total_qty;
            if ($qty_purchased == 0)
                return response()->json(['status' => 400, 'message' => 'Coin not purchased!']);
            $live_price = Http::get("https://pro-api.coingecko.com/api/v3/simple/price?vs_currencies=" . $request->currency . "&ids=" . $request->coin_id."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
            $live_price = json_decode($live_price->getBody()->getContents());
            $object = new stdClass();
            foreach ($live_price as $key => $value) {
                $object->$key = $value;
            }
            $live_price = collect($object)->values()->first();
            $currency = $request->currency;
            $amount_purchased = $live_price->$currency * $qty_purchased;
            $data['live_rate'] = $live_price->$currency;
            $data['amount'] = (string) $amount_purchased;
            $data['qty'] = (string) $qty_purchased;
            return response()->json(['status' => 200, 'message' =>  __("locale.Purchased price fetched successfully!"), 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
    /**
     *   @OA\Post(
     **  path="/api/sell-coin",
     *   summary="Sell a coin",
     *   operationId="sell-coin",
     *   tags={"Orders Manager"},
     *   @OA\Parameter(
     *      name="coin_symbol",
     *      in="query",
     *      required=true,
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
     *   @OA\Parameter(
     *      name="live_rate",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="target_rate",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="amount",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="qty",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="order_type",
     *      in="query",
     *      required=true,
     *      description="Instant/Limit",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="used_coupon_code",
     *      in="query",
     *      required=false,
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
    public function sell(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_symbol' => 'required',
            'live_rate' => 'required',
            'amount' => 'required',
            'qty' => 'required',
            'order_type' => 'required',
            'currency' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $user = Auth::user();
            $coin = Coin::where('symbol', $request->coin_symbol)->first();
            $past_orders_buy =  DB::table('orders')
                ->where('user_id', Auth::user()->id)->where('purchase_type', "Buy")->where('status', "Completed")->where('coin_id', $coin->id)
                ->selectRaw('sum(amount) as total_amount')->first();

            $past_orders_sell =  DB::table('orders')
                ->where('user_id', Auth::user()->id)->where('purchase_type', "Sell")->where('status', "Completed")->where('coin_id', $coin->id)
                ->selectRaw('sum(amount) as total_amount')->first();
            $amount_purchased = $past_orders_buy->total_amount - $past_orders_sell->total_amount;
            $order  = new Order();
            $order->user_id = Auth::user()->id;
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                $amount = $request->amount / $currency->rate;
            }
            if ($amount > $amount_purchased)
                return response()->json(['status' => 400, 'message' => __('locale.Insufficient amount of coin purchased!')]);
            if ($coin == NULL)
                return response()->json(['status' => 400, 'message' => __('locale.No coin found with this symbol!')]);
            $order->coin_id = $coin->id;

            $order->live_rate = $request->live_rate;
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                $order->amount = $request->amount / $currency->rate;
            }
            $order->qty = $request->qty;
            $order->order_type = $request->order_type;
            $order->purchase_type = "Sell";

            $order->status = 'Completed';
            $order->is_executed = 1;
            $order->executed_at = Carbon::now();
            $order->save();

            $user->balance += $order->amount;
            $user->save();
            return response()->json(['status' => 200, 'message' =>  __("locale.Coin sold successfully!"), 'data' => $order, 'wallet_balance' => $user->balance]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    public function placeOrder($order) //place order at binance
    {
    }

    /**
     * @OA\Get(
     ** path="/api/order-listing",
     *   summary="Order Listing",
     *   operationId="order-listing",
     *   tags={"Orders Manager"},
     *   @OA\Parameter(
     *      name="currency",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
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
     *      ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/


    public function orderListing(Request $request)
    {
        try {
            $logs = DB::table('orders')
                ->where('user_id', Auth::user()->id)
                ->join('coins', 'coins.id', '=', 'orders.coin_id')
                ->selectRaw('coins.coin_id,coins.name,coins.symbol as coin_symbol,coins.icon as image,amount,qty,live_rate,target_rate,status,purchase_type,order_type,executed_at,orders.created_at')->orderBy('orders.created_at', 'desc');

            if (!empty($request->search)) {

                $search = $request->search;
                $logs = $logs->where('name', 'like', '%' .  $search . '%');
            }
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();

                $logs = $logs->get();
                foreach ($logs as $value) {
                    $value->live_rate = $value->live_rate * $currency->rate;
                    $value->target_rate = $value->target_rate * $currency->rate;
                    $value->amount = $value->amount * $currency->rate;
                }
            }
            $logs = $logs->toArray();
            $logs = $this->paginate($logs);
            return response()->json([
                'status' => 200,
                'message' => __("locale.Order History Fetched Successfully!"),
                'data' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values()->all(), $items->count(), $perPage, $page, $options);
    }
}
