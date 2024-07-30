<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currencies;
use App\Models\Referal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferalController extends Controller
{
    /**
     * @OA\Get(
     ** path="/api/referal-listing",
     *   summary="Referal Listing",
     *   operationId="referal-listing",
     *   tags={"Referal Codes Manager"},
     *   @OA\Parameter(
     *      name="currency",
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


    public function referalListing(Request $request)
    {
        try {
            $logs = Referal::where('refered_by', Auth::user()->id)->get();
            foreach($logs as $log)
            {
                $user=User::where('id',$log->refered_to)->first();
                $log->message="You have refered to ".$user->firstname.' '.$user->lastname.' and earned '.$log->bonus_amount.' USD!!!';
            }
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                foreach ($logs as $value) {
                    $value->amount = $value->amount * $currency->rate;
                }
            }
            return response()->json([
                'status' => 200,
                'message' => __("locale.Referal History Fetched Successfully!"),
                'data' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
