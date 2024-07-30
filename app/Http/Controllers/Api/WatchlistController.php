<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use stdClass;
use App\Http\Traits\CoinSearch;
use Illuminate\Support\Facades\Validator;
use Exception;

class WatchlistController extends Controller
{
    use CoinSearch;
    //
    /**
     *   @OA\Post(
     **  path="/api/add-to-watchlist",
     *   summary="Add to Watchlist",
     *   operationId="add-to-watchlist",
     *   tags={"Watchlist Manager"},
     *   @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      description="1 for add,2 for delete",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="coin_id",
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
    public function addToWatchlist(Request $request)
    {
        $user = Auth::user();

        if ($request->type == 1) {
            if (Watchlist::where('user_id', $user->id)->where('coin_id', $request->coin_id)->exists()) {
                return response()->json(
                    [
                        'status' => 200,
                        'message' => __('locale.Coin already in Watchlist')
                    ]
                );
            }
            $watchlists = new Watchlist();
            $watchlists->user_id = $user->id;
            $watchlists->coin_id = $request->coin_id;
            $watchlists->type = 1;
            $watchlists->save();
            return response()->json(
                [
                    'status' => 200,
                    'message' => __('locale.Coin added to watchlist Successfully')
                ]
            );
        } else {
            $watchlist = Watchlist::where('user_id', $user->id)->where('coin_id', $request->coin_id)->delete();
            return response()->json(
                [
                    'status' => 200,
                    'message' => __('locale.Coin removed from watchlist Successfully')
                ]
            );
        }
    }


    /**
     * @OA\Get(
     ** path="/api/get-watchlist",
     *   summary="Watchlist Listing",
     *   operationId="deposit-transaction-listing",
     *   tags={"Watchlist Manager"},
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
     *      ),
     * security={
     *  {"Bearer": {}},
     *   },
     *)
     **/



    public function getWatchlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $watchlists = Watchlist::where('user_id', Auth::user()->id)->whereNotNull('coin_id')->pluck('coin_id')->toArray();
            $watchlists = implode(',', $watchlists);
            if ($watchlists == NULL) {
                return response()->json(['status' => 200, 'message' => __('locale.Watchlist fetched successfully!.'), 'data' => []]);
            }

            $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=100&page=1&ids=" . $watchlists."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

            $response = json_decode($response->getBody()->getContents());
            $object = new stdClass();
            if ($response) {
                foreach ($response as $key => $value) {
                    $object->$key = $value;
                }
            }

            if (!empty($request->search)) {
                $ids = $this->search($request->search);
                $ids = explode(',', $ids);
                $watchlists = Watchlist::where('user_id', Auth::user()->id)->whereNotNull('coin_id')->pluck('coin_id')->toArray();
                $ids = array_intersect($watchlists, $ids);
                $ids = implode(',', $ids);

                $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=100&page=1&ids=" . $ids."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

                $response = json_decode($response->getBody()->getContents());
                $object = new stdClass();
                if ($response) {
                    foreach ($response as $key => $value) {
                        $object->$key = $value;
                    }

                    $response = collect($object)->all();
                }
            }

            $watchlists = Watchlist::where('user_id', Auth::user()->id)->whereNotNull('coin_id')->pluck('coin_id')->toArray();
            foreach ($response as $res) {
                if (in_array($res->id, $watchlists))
                    $res->is_added_to_watchlist = 1;
                else
                    $res->is_added_to_watchlist = 0;
            }

            return response()->json(['status' => 200, 'message' => __('locale.Watchlist fetched successfully!.'), 'data' => $response]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
