<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Coin;
use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Models\PriceAlert;
use App\Models\ThirdpartyTransactions;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletsTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use stdClass;


class WalletController extends Controller
{


    /**
     * @OA\Get(
     ** path="/api/transaction-listing",
     *   summary="Transaction Listing",
     *   operationId="transaction-listing",
     *   tags={"Wallet Manager"},
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


    public function transactionListing(Request $request)
    {

        $deposits = auth()->user()->deposits()->with('gateway')->get();
        foreach ($deposits as $deposit)
            $deposit['type'] = 1;
        $withdrawals = auth()->user()->withdrawals()->with('method')->get();
        foreach ($withdrawals as $withdraw)
            $withdraw['type'] = 2;
        $logs = $deposits->merge($withdrawals)->sortByDesc('created_at')->values()->all();
        $logs = $this->paginate($logs)->values()->all();

        return response()->json([
            'status' => 200,
            'message' => __("locale.Wallet Transaction History Fetched Successfully!"),
            'data' => $logs,
        ]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     *   @OA\Post(
     **  path="/api/add-bank-account",
     *   summary="Add Bank Account",
     *   operationId="add-bank-account",
     *   tags={"Wallet Manager"},
     * @OA\Parameter(
     *      name="bank_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="account_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="ifsc_code",
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
    public function addBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $account  = new BankAccount();
            $account->user_id = Auth::user()->id;
            $account->bank_name = $request->bank_name;
            $account->account_number = $request->account_number;
            $account->ifsc_code = $request->ifsc_code;
            $account->save();
            $user = User::find(Auth::user()->id);
            $user->bank_account = 3;
            $user->save();
            $account->is_bank_account_added = 3;

            return response(["status" => 200, "message" => __("locale.Bank account added successfully"), 'data' => $account]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     ** path="/api/get-bank-account-details",
     *   summary="Bank account Details",
     *   operationId="get-bank-account-details",
     *   tags={"Wallet Manager"},
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

    public function getBankAccountDetails(Request $request)
    {

        try {
            $account  = BankAccount::where('user_id', Auth::user()->id)->get();

            return response(["status" => 200, "message" => __("locale.Bank account details fetched successfully"), 'data' => $account]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     *   @OA\Post(
     **  path="/api/missing-deposits-search",
     *   summary="Missing Deposits Entry Search",
     *   operationId="missing-deposits-search",
     *   tags={"Wallet Manager"},
     *   @OA\Parameter(
     *      name="transaction_id",
     *      in="query",
     *      required=true,
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

    public function missingDepositsSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'amount' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {

            $data = Deposit::where('trx', $request->transaction_id)->where('user_id', Auth::user()->id)->where('amount', $request->amount)->first();

            if ($data == NULL)
                return response(["status" => 400, "message" => __("locale.No such deposit with this transaction id and amount exists!"), 'data' => []]);
            if ($data->status == 1)
                return response(["status" => 200, "message" => __("locale.Amount has been succcessfully deposited to wallet!"), 'data' => []]);
            else if ($data->status == 2)
                return response(["status" => 400, "message" => __("locale.Amount is pending to be deposited to wallet!"), 'data' => []]);
            else if ($data->status == 3)
                return response(["status" => 400, "message" => __("locale.Deposit to wallet was cancelled!"), 'data' => []]);
            else
                return response(["status" => 400, "message" => __("locale.Deposit order was not successfully placed!"), 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/create-withdraw-password",
     *   summary="Create Withdraw Password",
     *   operationId="create-withdraw-password",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="password",
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
     *      ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/

    public function createWithdrawPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'password' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {

            $user  = User::where('id', Auth::user()->id)->update(['withdraw_password' => Hash::make($request->password)]);

            return response(["status" => 200, "message" => __("locale.Withdraw Password created successfully"), 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/change-withdraw-password",
     *   summary="Change Withdraw Password",
     *   operationId="change-withdraw-password",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="old_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="new_password",
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
     *      ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/

    public function changeWithdrawPassword(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        if ($validator->fails()) {

            return response()->json(['status' => 400, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $checkUser = User::find($user_id);
            if (Hash::check($request->old_password, $checkUser->withdraw_password)) {
                $checkUser->withdraw_password = Hash::make($request->new_password);
                $checkUser->update();
                return response()->json(['status' => 200, 'message' => __('locale.Password has been Changed successfully.'), 'data' => $checkUser]);
            } else {
                return response()->json(['status' => 400, 'message' => __('locale.Sorry, wrong  password.')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
}
