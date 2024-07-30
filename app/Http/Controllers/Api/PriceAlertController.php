<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\PriceAlert;
use App\Models\User;
use App\Models\UserAppNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use stdClass;
use Illuminate\Support\Facades\Log;

class PriceAlertController extends Controller
{
    //

    /**
     *   @OA\Post(
     **  path="/api/create-price-alert",
     *   summary="Create Price Alert",
     *   operationId="create-price-alert",
     *   tags={"Price Alerts"},
     *   @OA\Parameter(
     *      name="coin_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="target_price",
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
     *      name="image",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="name",
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
    public function createPriceAlert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin_id' => 'required',
            'target_price' => 'required',
            'currency' => 'required',
            'image' => 'required',
            'name' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {

            $alert  = new PriceAlert();
            $alert->user_id = Auth::user()->id;
            $alert->coin_id = $request->coin_id;
            $alert->name = $request->name;
            $alert->target_price = $request->target_price;
            $alert->currency = $request->currency;
            $alert->image = $request->image;
            $alert->save();
            return response(["status" => 200, "message" => __("locale.Price Alert created successfully"), 'data' => $alert]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Get(
     **  path="/api/price-alerts-listing",
     *   summary="Price Alerts Listing",
     *   operationId="price-alerts-listing",
     *   tags={"Price Alerts"},
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

    public function priceAlertsListing(Request $request)
    {
        try {

            $alert  =  PriceAlert::where('user_id', Auth::user()->id)->where('is_executed',0)->orderBy('created_at', 'desc')->get();
            $response = Http::get("https://pro-api.coingecko.com/api/v3/coins/markets?vs_currency=" . $request->currency."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");

            $response = json_decode($response->getBody()->getContents());
            $object = new stdClass();
            if ($response) {
                foreach ($response as $key => $value) {
                    $object->$key = $value;
                }
            }
            $response = collect($object)->sortByDesc('current_price')->values()->first();

            return response(["status" => 200, "message" => __("locale.Price Alert listing fetched successfully"), 'data' => $alert, 'coin' => $response]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Post(
     **  path="/api/delete-price-alert",
     *   summary="Delete Price Alert",
     *   operationId="delete-price-alert",
     *   tags={"Price Alerts"},
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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
    public function deletePriceAlert(Request $request)
    {
        try {

            $alert  =  PriceAlert::where('user_id', Auth::user()->id)->where('id', $request->id)->delete();

            return response(["status" => 200, "message" => __("locale.Price Alert deleted successfully"), 'data' => $alert]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    public function sendPriceAlertNotification(Request $request)
    {

        $notification = sendFCM([$request->token], [
            'title' => "Price alert", 'body' => 'Your requested target price 22000 for coin bitcoin has reached. Purchase now!', 'icon' => asset('/assets/images/app_icon.png')
        ], [
            'type' => 'price_alert', 'coin_id' => 'bitcoin',
            'title' => "Price alert", 'notification_message' => 'Your requested target price 22000 for coin bitcoin has reached. Purchase now!'
        ]);
     
    }

    /**
     *   @OA\Get(
     **  path="/api/get-notification-listing",
     *   summary="Get notification listing",
     *   operationId="get-notification-listing",
     *   tags={"Notifications"},
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
    public function getNotificationListing(Request $request)
    {
        try {
            $notifications  =  UserAppNotification::where('user_id', Auth::user()->id)->get();
            foreach ($notifications as $notification) {
                $notification->is_read = 1;
                $notification->save();
            }

            $notifications  =  UserAppNotification::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);


            return response(["status" => 200, "message" => __("locale.Notification listing fetched successfully"), 'data' => $notifications]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
