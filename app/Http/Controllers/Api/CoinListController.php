<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CoinSearch;
use App\Models\Banner;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Exception;

class CoinListController extends Controller
{
    use CoinSearch;
    /**
     *   @OA\Get(
     **  path="/api/get-coin-list",
     *   summary="Get Coin List",
     *   operationId="get-coin-list",
     *   tags={"Coin List Manager"},
     *   @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
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
     *    )
     *)
     **/
    public function getCoinList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required',
        ]);

        $currency = $request->currency ?? 'usd';

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $currency . "&per_page=100&page=1&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

            $response = json_decode($response->getBody()->getContents());

            $object = new stdClass();
            if (isset($response)) {
                foreach ($response as $key => $value) {
                    $object->$key = $value;
                }
            }
          
            if (!empty($request->search)) { //search + order case
                $ids = $this->search($request->search);


                $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=100&page=1&ids=" . $ids."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

                $response = json_decode($response->getBody()->getContents());
                $object = new stdClass();
                if (isset($response)) {
                    foreach ($response as $key => $value) {
                        $object->$key = $value;
                    }
                    $response = collect($object)->all();
                }
                
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

            return response()->json(['status' => 200, 'message' => __('locale.Coin List fetched successfully!.'), 'data' => $response]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
