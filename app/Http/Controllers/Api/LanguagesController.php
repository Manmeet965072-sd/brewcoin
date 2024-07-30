<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currencies;
use App\Models\Language;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class LanguagesController extends Controller
{
   
    /**
     *   @OA\Get(
     **  path="/api/change-currency",
     *   summary="Change Currency",
     *   operationId="change-currency",
     *   tags={"Currency Manager"},
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

    public function changeCurrency(Request $request)
    {
        try {
            $currency = Currencies::where('code', strtoupper($request->currency))->first();
            $user = Auth::user();

            $data = $user->balance * $currency->rate;
            $object = new stdClass();
            $object->wallet_balance = $data;
            return response(["status" => 200, "message" => __('locale.Currency changed successfully!'), 'data' => $object]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Get(
     **  path="/api/get-currency-listing",
     *   summary="Get currency listing",
     *   operationId="get-currency-listing",
     *   tags={"Currency Manager"},
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
     *)
     **/

    public function getCurrencyListing(Request $request)
    {
        try {
            $currency = Currencies::where('status', 1)->get();

            return response(["status" => 200, "message" => __('locale.Currencies fetched successfully!'), 'data' => $currency]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }


     /**
     *   @OA\Get(
     **  path="/api/get-language-listing",
     *   summary="Get language listing",
     *   operationId="get-language-listing",
     *   tags={"Language Manager"},
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
     *)
     **/

    public function getLanguageListing(Request $request)
    {
        try {
            $languages = Language::where('status', 1)->get();

            return response(["status" => 200, "message" => __('locale.Languages fetched successfully!'), 'data' => $languages]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
