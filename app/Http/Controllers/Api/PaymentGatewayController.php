<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Mail\TransactionOtpEmail;
use App\Models\Currencies;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Illuminate\Support\Facades\Session;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\WalletsTransactions;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Exception;

class PaymentGatewayController extends Controller
{
    public static function userDataUpdate($trx)
    {
        $data = Deposit::where('trx', $trx)->first();
        if ($data->status == 0) {
            $data->status = 1;
            $data->save();

            $user = User::find($data->user_id);
            $user->balance += $data->amount;
            $user->save();
            return $user->balance;
        }
    }

    public function stripeIpn($transaction_id, $token)
    {
        $transaction_id = $transaction_id;
        $data = Deposit::where('trx', $transaction_id)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        if ($data == NULL)
            return response()->json(['status' => 401, 'message' => __('locale.No deposit made with this transaction id')]);
        if ($data->status == 1) {
            return response()->json(['status' => 401, 'message' => __('locale.Deposit has been already made with this transaction id')]);
        }
        $stripeAcc = json_decode($data->gateway_currency()->gateway_parameter);

        Stripe::setApiKey($stripeAcc->secret_key);
        Stripe::setApiVersion("2020-03-02");

        $stripe = new \Stripe\StripeClient(
            'sk_test_51Kei0PDdDnwFdkhkf90191Bjxn24d8b06GOFzz7ABXExrQPYpQqKZFXKnpJG0oHS9MjgO4eJiA37kqg6pCPGp4i000Bl4MfDXW'
        );
        $token_data = $stripe->tokens->retrieve(
            $token,
            []
        );
        try {
            $cnts = round($data->final_amo, 2) * 100;
            try {
                $charge = Charge::create(array(
                    'card' => $token_data['id'],
                    'currency' => $data->method_currency,
                    'amount' => $cnts,
                    'description' => 'item',
                ));

                if ($charge['status'] == 'succeeded') {
                    $balance = $this->userDataUpdate($data->trx);
                    return response()->json(['status' => 200, 'message' => __("locale.Payment Success"), 'data' => ['wallet_balance' => $balance]]);
                }
            } catch (\Exception $e) {
                return response()->json(['status' => 400, 'message' => $e->getMessage()]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/send-transaction-otp",
     *   summary="Send Transaction Otp",
     *   operationId="Send Transaction Otp",
     *   tags={"Payment Gateways"},
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

    public function sendTransactionOtp(Request $request)
    {

        try {
           
            $user = Auth::user();
            $user->transaction_otp = rand(1000, 9999);;
            $user->save();
            Mail::to($user->email)->send(new TransactionOtpEmail($user));

            $success['transaction_otp'] = $user->transaction_otp;
            $success['user_id'] = $user->id;
            $success['email'] = $user->email;

            return response()->json(['status' => 200, 'message' => __('locale.Otp sent successfully!'), 'data' => $success]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/verify-deposit-transaction",
     *   summary="Verify Deposit Transaction",
     *   operationId="Verify Deposit Transaction",
     *   tags={"Payment Gateways"},
     *  @OA\Parameter(
     *      name="token",
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
     *    @OA\Parameter(
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
     *      ),
     *  security={
     *  {"Bearer": {}},
     *   },
     *)
     **/

    public function verifyDepositTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'amount'   => 'required',
            'currency' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $user = Auth::user();

            $gate = GatewayCurrency::where('method_code', 103)->where('currency', $request->currency)->first();
            if (!$gate) {
                return response()->json(['status' => 400, 'message' => __('locale.Invalid Gateway')]);
            }

            $amount=$request->amount;
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                $amount = $amount / $currency->rate;
            }

            if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
                return response()->json(['status' => 400, 'message' => 'Please Follow Deposit Limit']);
            }

            $charge = getAmount($gate->fixed_charge + ($amount * $gate->percent_charge / 100));
            $payable = getAmount($amount + $charge);
            $final_amo = getAmount($payable * $gate->rate);

            $data = new Deposit();
            $data->user_id = Auth::user()->id;
            $data->method_code = $gate->method_code;
            $data->method_currency = strtoupper($gate->currency);
            $data->amount = $amount;
            $data->charge = $charge;
            $data->rate = $gate->rate;
            $data->final_amo = getAmount($final_amo);
            $data->trx = getTrx();
            $data->status = 0;
            $data->save();
            return $this->stripeIpn($data->trx, $request->token);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/verify-withdraw-transaction",
     *   summary="Verify Withdraw Transaction",
     *   operationId="Verify Withdraw Transaction",
     *   tags={"Payment Gateways"},
     *   @OA\Parameter(
     *      name="amount",
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
     *     @OA\Parameter(
     *      name="bank_account_id",
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

    public function verifyWithdrawTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_account_id'    => 'required',
            'amount'   => 'required',
            'currency' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {

            $user = auth()->user();
            $amount=$request->amount;
            if (!empty($request->currency)) {
                $currency = Currencies::where('code', strtoupper($request->currency))->first();
                $amount = $amount / $currency->rate;
            }

            if ($amount > $user->balance) {
                return response()->json(['status' => 400, 'message' => __('locale.You do not have Sufficient Balance For Withdraw.')]);
            }

            //$charge =  $request->amount;
            $afterCharge = $amount;
            $finalAmount = getAmount($afterCharge);

            $withdraw = new Withdrawal();
            //$withdraw->method_id = $method->id;
            $withdraw->bank_account_id = $request->bank_account_id;
            $withdraw->user_id = $user->id;
            $withdraw->amount = getAmount($amount);
            $withdraw->currency = $request->currency;
            // $withdraw->rate = $method->rate;
            //$withdraw->charge = $charge;
            $withdraw->final_amount = $finalAmount;
            $withdraw->after_charge = $afterCharge;
            $withdraw->trx = getTrx();
            $withdraw->save();

            $user->balance -= $amount;
            $user->save();
            return response()->json(['status' => 200, 'message' => __("locale.Withdraw request made successfully!"),'data'=>['wallet_balance' => $user->balance]]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
