<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Post;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Http;
use stdClass;
use WebDevEtc\BlogEtc\Models\Post as ModelsPost;
use Exception;

class HomeController extends Controller
{

    /**
     *   @OA\Get(
     **  path="/api/get-home-data",
     *   summary="Get Home Data- Banners,top gainers, top loosers, newly launched)",
     *   operationId="get-home-data",
     *   tags={"Home Page"},
     * @OA\Parameter(
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
    public function getHomeData(Request $request)
    {
        try {
            //Banners
            $banners =  Banner::get();
            $data = [];
            foreach ($banners as $key => $banner) {
                $data[$key]['banner_url'] = $banner->banner_url;
                $data[$key]['link'] = $banner->link;
            }
            $watchlists = [];
            if (!empty($request->user_id))
                $watchlists = Watchlist::where('user_id', $request->user_id)->whereNotNull('coin_id')->pluck('coin_id')->toArray();

            //Top Gainers    
            $top_gainers = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=100&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
            $top_gainers = json_decode($top_gainers->getBody()->getContents());
            $object = new stdClass();
            if (isset($top_gainers)) {
                foreach ($top_gainers as $key => $value) {
                    $object->$key = $value;
                }
            }
            // else {
            //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
            // }
            $top_gainers = collect($object)->sortByDesc('price_change_percentage_24h');

            $top_gainers = $top_gainers->values()->take(10);
            if ($top_gainers) {
                foreach ($top_gainers as $res) {
                    if (isset($res->id)) {
                        if (in_array($res->id, $watchlists))
                            $res->is_added_to_watchlist = 1;
                        else
                            $res->is_added_to_watchlist = 0;
                    }
                    // else {
                    //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
                    // }
                }
            }
            else {
                return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
            }

            //Top Loosers
            $top_loosers = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=100&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
            $top_loosers = json_decode($top_loosers->getBody()->getContents());
            $object = new stdClass();
            if (isset($top_loosers)) {
                foreach ($top_loosers as $key => $value) {
                    $object->$key = $value;
                }
            }
            // else {
            //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
            // }
            $top_loosers = collect($object)->sortBy('price_change_percentage_24h');
            $top_loosers = $top_loosers->values()->take(10);
            if ($top_loosers) {
                foreach ($top_loosers as $res) {
                    if (isset($res->id)) {
                        if (in_array($res->id, $watchlists))
                            $res->is_added_to_watchlist = 1;
                        else
                            $res->is_added_to_watchlist = 0;
                    }
                    // else {
                    //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
                    // }
                }
            }

            //Newly Launched
            $newly_launched = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&per_page=" . $request->per_apge . "&page=" . $request->page . "&order=gecko_asc&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
            $newly_launched = json_decode($newly_launched->getBody()->getContents());
            $object = new stdClass();
            if (isset($newly_launched)) {
                foreach ($newly_launched as $key => $value) {
                    $object->$key = $value;
                }
            }
            // else {
            //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
            // }
            $newly_launched = collect($object);
            if ($newly_launched) {
                foreach ($newly_launched as $key => $res) {


                    if (!isset($res->price_change_percentage_24h))
                        $newly_launched->forget($key);
                    // else {
                    //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
                    // }
                    // else {
                    //     return response()->json(['status' => 400, 'message' => __('locale.Please try again after some time!')]);
                    // }

                    if (isset($res->id)) {
                        if (in_array($res->id, $watchlists))
                            $res->is_added_to_watchlist = 1;
                        else
                            $res->is_added_to_watchlist = 0;
                    }
                    // } else {
                    //     return response()->json(['status' => 400, 'message' => __('Youve exceeded the Rate Limit. Please visit https://www.coingecko.com/en/api/pricing to subscribe to our API plans for higher rate limits.')]);
                    // }
                }
            }
            $newly_launched =  $newly_launched->values()->take(10);

            //News
            $news = [];
            $news = Http::get("https://cointelegraph.com/feed");

            $news = $news->getBody()->getContents();

            $xml = simplexml_load_string($news);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            //Blogs
            $posts = ModelsPost::orderBy('posted_at', 'desc')->get();

            return response()->json(['status' => 200, 'message' => __('locale.Home data fetched successfully!.'), 'banners' => $data, 'top_gainers' => $top_gainers, 'top_loosers' => $top_loosers, 'newly_launched' => $newly_launched, 'news' => $array, 'blogs' => $posts]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Get(
     **  path="/api/get-coin-details",
     *   summary="Get Coin details",
     *   operationId="get-coin-details",
     *   tags={"Home Page"},
     *   @OA\Parameter(
     *      name="currency",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
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
    public function getCoinDetails(Request $request)
    {
        try {
            $coin = Http::get("https://api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency . "&ids=" . $request->coin_id);
            $coin = json_decode($coin->getBody()->getContents());

            return response()->json(['status' => 200, 'message' => __('locale.Coin detail fetched successfully!.'), 'data' => $coin[0]]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
