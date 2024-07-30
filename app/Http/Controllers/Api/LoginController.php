<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserTrait;
use App\Lib\GoogleAuthenticator;
use App\Mail\ForgetPasswordEmail;
use App\Mail\ForgetPinEmail;
use App\Mail\GoogleAuthenticatorOtpEmail;
use App\Mail\SignupEmail;
use App\Models\GeneralSetting;
use App\Models\Referal;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAppNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class LoginController extends Controller
{
    use UserTrait;

    /**
     * @OA\Post(
     ** path="/api/login", 
     *   summary="Login",
     *   operationId="login",
     *   tags={"Authentication"},
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="device_token",
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
     *      )
     * 
     *)
     **/

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        try {
            $credentials = $request->only('email', 'password');
            $user = User::where('email', $request->email)->first();
            if (!empty($user)) {
                if ($user->deleted_at != null) {
                    return response()->json(['status' => 401, 'message' => __('locale.Your account has been disabled!')]);
                }
                if ($user->status == 0) {
                    return response()->json(['status' => 401, 'message' => __('locale.Your account has been banned by admin!')]);
                }
            }
            if (Auth::attempt($credentials)) {
                $user             = User::find(Auth::user()->id);
                $user->device_token = $request->device_token;
                $user->save();
                $success['name']  = $user->firstname . ' ' . $user->lastname;
                $success['firstname'] = $user->firstname;
                $success['lastname'] = $user->lastname;
                $success['profile_photo_path'] = $user->profile_photo_path;
                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['referal_code'] = $user->referal_code;
                $success['country_code'] = $user->country_code;
                $success['mobile'] = $user->mobile;
                if (empty($user->pin))
                    $success['isPinCreated'] = 0;
                else
                    $success['isPinCreated'] = 1;
                if ($user->transaction_pin == NULL)
                    $success['is_transaction_pin_enabled'] = 0;
                else
                    $success['is_transaction_pin_enabled'] = 1;

                if ($user->secret_code == NULL)
                    $success['isGoogleAuthenticatorEnabled'] = 0;
                else {
                    $success['isGoogleAuthenticatorEnabled'] = 1;
                    $success['secret_code'] = $user->secret_code;
                }

                if ($user->pin == 0)
                    $success['isPinDisabled'] = 0;
                else
                    $success['isPinDisabled'] = 1;
                if ($user->phone_verified_at == NULL) {
                    if ($user->mobile != NULL) {
                        // $account_sid = getenv("TWILIO_SID");
                        // $auth_token = getenv("TWILIO_TOKEN");
                        // $twilio_number = getenv("TWILIO_FROM");
                        // $generator = "1357902468";
                        // $result = "";

                        // for ($i = 1; $i <= 4; $i++) {
                        //     $result .= substr($generator, (rand() % (strlen($generator))), 1);
                        // }

                        // $client = new Client($account_sid, $auth_token);
                        // $client->messages->create($user->mobile, [
                        //     'from' => $twilio_number,
                        //     'body' => $result
                        // ]);
                        //$user->mobile_otp = $result;
                        $user->mobile_otp = "1234";
                        $user->save();
                        $success['isPhoneVerified'] = 0;
                        $success['mobile_otp'] = $user->mobile_otp;
                    } else
                        $success['isPhoneVerified'] = 0;
                } else
                    $success['isPhoneVerfied'] = 1;
                if ($user->email_verified_at != NULL)
                    $success['isEmailVerfied'] = 1;
                else {
                    $generator = "1357902468";
                    $result = "";

                    for ($i = 1; $i <= 4; $i++) {
                        $result .= substr($generator, (rand() % (strlen($generator))), 1);
                    }
                    $user->verify_token = $result;
                    $user->save();
                    Mail::to($user->email)->send(new SignupEmail($user));
                    $success['isEmailVerified'] = 0;
                    $success['otp'] = $user->verify_token;
                }
                $success['pin'] = $user->pin;
                $success['transaction_pin'] = $user->transaction_pin;
                $success['wallet_balance'] = $user->balance;
                $success['is_kyc_verified'] = $user->kyc;
                $success['is_withdrawal_password_set'] = !empty($user->withdraw_password) ? 1 : 0;
                $success['is_bank_account_added'] = $user->bank_account;
                $success['token'] = $user->createToken('accessToken')->accessToken;

                $success['isSociallyLoggedIn'] = 0;

                return response()->json(['status' => 200, 'message' => __('locale.You are successfully logged in.'), 'data' => $success]);
            } else {
                return response()->json(['status' => 401, 'message' => __('locale.Either your email or password is incorrect')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/resend-otp", 
     *   summary="Resend Otp",
     *   operationId="resend-otp",
     *   tags={"Authentication"},
     *   @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="mobile",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="type",
     *      in="query",
     *      description="1=>email,2=>mobile,3=>pin",
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
     * 
     *)
     **/
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        try {
            $generator = "1357902468";
            $result = "";

            for ($i = 1; $i <= 4; $i++) {
                $result .= substr($generator, (rand() % (strlen($generator))), 1);
            }
            if ($request->type == 1) {
                $user = User::where('email', $request->email)->where('id', $request->user_id)->first();
                if ($user) {
                    $user->verify_token = $result;
                    $user->save();
                    Mail::to($user->email)->send(new SignupEmail($user));
                } else {

                    return response()->json(['status' => 400, 'message' => 'Email not valid!']);
                }
                $success['otp'] = $user->verify_token;

                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['country_code'] = $user->country_code;
                $success['mobile'] = $user->mobile;

                return response()->json(['status' => 200, 'message' => __('Otp sent again successfully!'), 'data' => $success]);
            } else if ($request->type == 2) {
                $user = User::where('mobile', $request->mobile)->where('id', $request->user_id)->first();
                // $account_sid = getenv("TWILIO_SID");
                // $auth_token = getenv("TWILIO_TOKEN");
                // $twilio_number = getenv("TWILIO_FROM");


                // $client = new Client($account_sid, $auth_token);
                // $client->messages->create($request->mobile, [
                //     'from' => $twilio_number,
                //     'body' => $result
                // ]);
                //$user->mobile_otp = $result;
                if ($user) {
                    $user->mobile_otp = "1234";
                    $user->save();
                } else {
                    return response()->json(['status' => 400, 'message' => 'Mobile number not valid!']);
                }

                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['country_code'] = $user->country_code;
                $success['mobile'] = $user->mobile;
                $success['mobile_otp'] = $user->mobile_otp;

                return response()->json(['status' => 200, 'message' => __('locale.Otp sent again successfully!'), 'data' => $success]);
            } else if ($request->type == 3) {
                $user = User::where('email', $request->email)->where('id', $request->user_id)->first();
                if ($user) {
                    $user->otp = $result;
                    $user->save();
                    Mail::to($user->email)->send(new ForgetPinEmail($user));
                } else {

                    return response()->json(['status' => 400, 'message' => 'Email not valid!']);
                }
                $success['otp'] = $user->otp;

                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['country_code'] = $user->country_code;
                $success['mobile'] = $user->mobile;

                return response()->json(['status' => 200, 'message' => __('locale.Otp sent again successfully!'), 'data' => $success]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/register",
     *   summary="Register",
     *   operationId="register",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="firstname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="lastname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *       name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="mobile",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="device_token",
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
     *      )
     *)
     **/

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required',
            'lastname'     => 'required',
            'country_code' => 'required',
            'mobile' => 'required',
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->email_verified_at != null) {
                    return response()->json(['status' => 422, 'message' => __('locale.Email already exists.Please login!!!')]);
                }
            } else {
                $user = User::create([
                    'firstname'     => $request->firstname,
                    'lastname'     => $request->lastname,
                    'country_code' => $request->country_code,
                    'device_token' => $request->device_token,
                    'mobile'     => $request->mobile,
                    'email'    => $request->email,
                    'password' => bcrypt($request->password),
                    'role_id' => 2
                ]);
            }
            $generator = "1357902468";
            $result = "";

            for ($i = 1; $i <= 4; $i++) {
                $result .= substr($generator, (rand() % (strlen($generator))), 1);
            }
            $user->verify_token = $result;
            $user->save();
            if ($user) {
                Mail::to($user->email)->send(new SignupEmail($user));
            } else {
                return response()->json(['status' => 400, 'message' =>  __('locale.Error while creating user!!')]);
            }
            // $account_sid = getenv("TWILIO_SID");
            // $auth_token = getenv("TWILIO_TOKEN");
            // $twilio_number = getenv("TWILIO_FROM");
            // $generator = "1357902468";
            // $result = "";

            // for ($i = 1; $i <= 4; $i++) {
            //     $result .= substr($generator, (rand() % (strlen($generator))), 1);
            // }

            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($request->mobile, [
            //     'from' => $twilio_number,
            //     'body' => $result
            // ]);

            //$user->mobile_otp = $result;
            $user->mobile_otp = "1234";

            $user->save();
            $user->referal_code = createReferalCode($user);
            $user->save();

            $success['name']  = $user->firstname . ' ' . $user->lastname;
            $success['firstname'] = $user->firstname;
            $success['lastname'] = $user->lastname;
            $success['profile_photo_path'] = $user->profile_photo_path;
            $success['user_id'] = $user->id;
            $success['email'] = $user->email;
            $success['country_code'] = $user->country_code;
            $success['mobile'] = $user->mobile;
            $success['otp'] = $user->verify_token;
            $success['mobile_otp'] =  $user->mobile_otp;
            $success['wallet_balance'] = $user->balance ?? 0;
            $success['isSociallyLoggedIn'] = 0;
            $success['is_kyc_verified'] = $user->kyc ?? 1;
            $success['is_bank_account_added'] = $user->bank_account ?? 1;
            $success['referal_code'] = $user->referal_code;

            $message          = __('locale.Yay! A user has been successfully created.');
            $success['token'] = $user->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = $e->getMessage();
        }
        return response()->json(['status' => 200, 'message' => $message, 'data' => $success]);
    }

    /**
     * @OA\Get(
     ** path="/api/logout",
     *   summary="Logout",
     *   operationId="logout",
     *   tags={"Authentication"},
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

    public function logout(Request $request)
    {
        try {
            $user = User::whereId(Auth::id())->first();
            if ($user) {
                $user->device_token = "";
                $user->update();
                // DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
                return response()->json(['status' => 200, "message" => __("locale.Successfully logged out.")]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/verify-email-and-phone",
     *   summary="Verify Email and Phone",
     *   operationId="verify-email-and-phone",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      description="1=>Email,2=>Phone,3=>Both",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="authentication_type",
     *      in="query",
     *      required=false,
     *      description="1=>SignUp,2=>Login,3=>Forget Password",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="mobile_otp",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email_otp",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="invited_by",
     *      in="query",
     *      required=false,
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
     *      )
     *)
     **/

    public function verifyEmailandPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            if (empty($request->authentication_type) || $request->authentication_type == 1 || $request->authentication_type == 2) {
                if ($request->type == 1) {
                    $user = User::where('id', $request->user_id)->where('verify_token', $request->email_otp)->first();
                    if ($user) {
                        $user->email_verified_at = Carbon::now();
                        $user->invited_by = $request->invited_by ?? NULL;
                        $gen = GeneralSetting::first();
                        $referral_bonus = getAmount($gen->referral_bonus);
                        if ($request->invited_by) {
                            $other_user = User::where('id', $request->invited_by)->first();
                            $referal = new Referal();
                            $referal->refered_by = $request->invited_by;
                            $referal->refered_code = $other_user->referal_code;
                            $referal->refered_to = $user->id;
                            $referal->bonus_amount = $referral_bonus;
                            $referal->save();
                            $other_user->balance += $referal->bonus_amount;
                            $other_user->save();
                            $notificationData = new UserAppNotification();
                            $notificationData->fill(['user_id' => $other_user->id, 'title' => "Reward for referring Notification", 'message' => 'You have been rewarded with ' . $referal->bonus_amount . ' dollars for referring ' . $user->firstname . ' ' . $user->lastname, 'type' => 'reward']);
                            $notificationData->save();



                            $notification = sendFCM([$other_user->device_token], [
                                'title' => "Reward for referring Notification", 'body' => 'You have been rewarded with ' . $referal->bonus_amount . ' dollars for referring ' . $user->firstname . ' ' . $user->lastname, 'icon' => asset('/assets/images/app_icon.png')
                            ], [
                                'type' => 'reward',
                                'title' => "Reward for referring Notification", 'notification_message' => 'You have been rewarded with ' . $referal->bonus_amount . ' dollars for referring ' . $user->firstname . ' ' . $user->lastname
                            ]);
                        }
                        $user->save();
                        $success['name']  = $user->firstname . ' ' . $user->lastname;
                        $success['firstname'] = $user->firstname;
                        $success['lastname'] = $user->lastname;
                        $success['profile_photo_path'] = $user->profile_photo_path;
                        $success['user_id'] = $user->id;
                        $success['email'] = $user->email;
                        return response()->json([
                            'status'   => 200,

                            'message'      => __("locale.Email is Verified"),
                            'data'         => $success
                        ], 200);
                    } else {
                        return response()->json(['status' => 400, 'message' => __('locale.OTP not valid')]);
                    }
                } else if ($request->type == 2) {
                    $user = User::where('id', $request->user_id)->where('mobile_otp', $request->mobile_otp)->first();
                    if ($user) {
                        $user->phone_verified_at = Carbon::now();
                        $user->save();
                        $success['name']  = $user->firstname . ' ' . $user->lastname;
                        $success['firstname'] = $user->firstname;
                        $success['lastname'] = $user->lastname;
                        $success['profile_photo_path'] = $user->profile_photo_path;
                        $success['user_id'] = $user->id;
                        //$success['email'] = $user->email;
                        $success['country_code'] = $user->country_code;
                        $success['mobile'] = $user->mobile;
                        return response()->json([
                            'status'   => 200,

                            'message'      => __("locale.Phone is Verified"),
                            'data'         => $success
                        ], 200);
                    } else {
                        return response()->json(['status' => 400, 'message' => 'OTP not valid']);
                    }
                } else if ($request->type == 3) {
                    $user = User::where('id', $request->user_id)->where('mobile_otp', $request->mobile_otp)->where('verify_token', $request->email_otp)->first();
                    if ($user) {
                        $user->phone_verified_at = Carbon::now();
                        $user->email_verified_at = Carbon::now();
                        $user->save();
                        $success['name']  = $user->firstname . ' ' . $user->lastname;
                        $success['firstname'] = $user->firstname;
                        $success['lastname'] = $user->lastname;
                        $success['user_id'] = $user->id;
                        //$success['email'] = $user->email;
                        $success['country_code'] = $user->country_code;
                        $success['mobile'] = $user->mobile;
                        return response()->json([
                            'status'   => 200,

                            'message'      => __("locale.Email and Phone are Verified"),
                            'data'         => $success
                        ], 200);
                    } else {
                        return response()->json(['status' => 400, 'message' => __('locale.OTP not valid')]);
                    }
                }
            } else if (!empty($request->authentication_type) && ($request->authentication_type == 3)) {
                $user = User::where('id', $request->user_id)->where('verify_token', $request->email_otp)->first();
                if ($user) {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                    $success['name']  = $user->firstname . ' ' . $user->lastname;
                    $success['firstname'] = $user->firstname;
                    $success['lastname'] = $user->lastname;
                    $success['profile_photo_path'] = $user->profile_photo_path;
                    $success['user_id'] = $user->id;
                    $success['email'] = $user->email;
                    return response()->json([
                        'status'   => 200,

                        'message'      => __("locale.Your reset password request has been successfully verified!!!"),
                        'data'         => $success
                    ], 200);
                } else {
                    return response()->json(['status' => 400, 'message' => __('locale.OTP not valid')]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



    /**
     * @OA\Post(
     ** path="/api/add-phone-number",
     *   summary="Add Phone Number",
     *   operationId="add-phone-number",
     *   tags={"Authentication"},
     *   @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="mobile",
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

    public function addPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $user = User::where('id', $request->user_id)->first();

            $user->country_code = $request->country_code;
            $user->mobile = $request->mobile;
            $user->save();
            // $account_sid = getenv("TWILIO_SID");
            // $auth_token = getenv("TWILIO_TOKEN");
            // $twilio_number = getenv("TWILIO_FROM");
            // $generator = "1357902468";
            // $result = "";

            // for ($i = 1; $i <= 4; $i++) {
            //     $result .= substr($generator, (rand() % (strlen($generator))), 1);
            // }

            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($request->mobile, [
            //     'from' => $twilio_number,
            //     'body' => $result
            // ]);
            $user->mobile_otp = "1234";
            //$user->mobile_otp = $result;
            $user->save();
            $success['name']  = $user->firstname . ' ' . $user->lastname;
            $success['firstname'] = $user->firstname;
            $success['lastname'] = $user->lastname;
            $success['profile_photo_path'] = $user->profile_photo_path;
            $success['user_id'] = $user->id;
            //$success['email'] = $user->email;
            $success['country_code'] = $user->country_code;
            $success['mobile'] = $user->mobile;
            $success['mobile_otp'] = $user->mobile_otp;
            return response()->json([
                'status'   => 200,

                'message'      => __("locale.Phone Number is added"),
                'data'         => $success
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



    /**
     * @OA\Post(
     ** path="/api/social-login",
     *   summary="Social Login",
     *   operationId="social-login",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="provider_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="provider",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="device_token",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="device_type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="firstname",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="lastname",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
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

    public function socialLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'provider_id'     => 'required',
            'provider'     => 'required',
            //'email' => 'required',
            //'firstname' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);

        try {
            if ($request->provider != "apple")
                $user = User::where('provider_id', $request->provider_id)->where('email', $request->email)->first();
            else
                $user = User::where('provider_id', $request->provider_id)->whereNotNull('email')->first();

            if (!empty($user)) {
                if ($user->deleted_at != null) {
                    return response()->json(['status' => 401, 'message' => __('locale.Your account has been disabled!')]);
                }

                if ($user->status == 0) {
                    return response()->json(['status' => 401, 'message' => __('locale.Your account has been banned by admin!')]);
                }
                $user->device_token = $request->device_token ?? "temp";
                $user->save();
                $success['name']  = $user->firstname . ' ' . $user->lastname;
                $success['firstname'] = $user->firstname;
                $success['lastname'] = $user->lastname;
                $success['profile_photo_path'] = $user->profile_photo_path;
                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['referal_code'] = $user->referal_code;
                $success['token'] = $user->createToken('accessToken')->accessToken;
                if (empty($user->pin))
                    $success['isPinCreated'] = 0;
                else
                    $success['isPinCreated'] = 1;
                if ($user->pin == 0)
                    $success['isPinDisabled'] = 0;
                else
                    $success['isPinDisabled'] = 1;
                if ($user->transaction_pin == NULL)
                    $success['is_transaction_pin_enabled'] = 0;
                else
                    $success['is_transaction_pin_enabled'] = 1;
                if ($user->secret_code == NULL)
                    $success['isGoogleAuthenticatorEnabled'] = 0;
                else {
                    $success['isGoogleAuthenticatorEnabled'] = 1;
                    $success['secret_code'] = $user->secret_code;
                }
                if ($user->phone_verified_at == NULL) {
                    $success['isPhoneVerified'] = 0;
                } else
                    $success['isPhoneVerfied'] = 1;
                if ($user->email_verified_at != NULL)
                    $success['isEmailVerfied'] = 1;
                else {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                    $success['isEmailVerified'] = 1;
                }
                $success['pin'] = $user->pin;
                $success['transaction_pin'] = $user->transaction_pin;
                $success['is_withdrawal_password_set'] = !empty($user->withdraw_password) ? 1 : 0;
                $success['country_code'] = $user->country_code;
                $success['mobile'] = $user->mobile;
                $success['wallet_balance'] = $user->balance;
                $success['isSociallyLoggedIn'] = 1;
                $success['is_kyc_verified'] = $user->kyc ?? 1;
                $success['is_bank_account_added'] = $user->bank_account ?? 1;

                return response()->json(['status' => 200, 'message' => __('locale.You are successfully logged in.'), 'data' => $success]);
            } else {
                $user = new User();
                $user->email = $request->email;
                $user->firstname = $request->firstname;
                $user->lastname = $request->lastname ?? '';
                $user->device_token = $request->device_token ?? "temp";
                $user->device_type = $request->device_type;
                $user->provider = $request->provider;
                $user->provider_id = $request->provider_id;
                $user->profile_photo_path = $request->profile_photo_path;
                $user->email_verified_at = Carbon::now();
                $user->save();
                $user->referal_code = createReferalCode($user);
                $user->save();

                $UserData = User::where('id', $user->id)->first();

                Auth::login($UserData);
                // Auth::user()->AauthAcessToken()->delete();
                $tokenResult = $UserData->createToken('Token');
                $token = $tokenResult->token;
                $token->save();

                $responseData = [];

                $UserData->token_type = 'Bearer';
                $UserData->token = $tokenResult->accessToken;
                $responseData = $UserData;

                $data = $responseData;


                $success['name']  = $user->firstname . ' ' . $user->lastname;
                $success['firstname'] = $user->firstname;
                $success['lastname'] = $user->lastname;
                $success['profile_photo_path'] = $user->profile_photo_path;
                $success['wallet_balance'] = $user->balance;
                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['referal_code'] = $user->referal_code;
                $success['token'] = $tokenResult->accessToken;
                if (empty($user->pin))
                    $success['isPinCreated'] = 0;
                else
                    $success['isPinCreated'] = 1;
                if ($user->pin == 0)
                    $success['isPinDisabled'] = 0;
                else
                    $success['isPinDisabled'] = 1;
                if ($user->transaction_pin == NULL)
                    $success['is_transaction_pin_enabled'] = 0;
                else
                    $success['is_transaction_pin_enabled'] = 1;
                if ($user->phone_verified_at == NULL) {
                    $success['isPhoneVerified'] = 0;
                } else
                    $success['isPhoneVerfied'] = 1;
                $user->email_verified_at = Carbon::now();
                $user->save();
                $success['is_withdrawal_password_set'] = !empty($user->withdraw_password) ? 1 : 0;
                $success['isEmailVerified'] = 1;
                $success['isSociallyLoggedIn'] = 1;
                $success['is_kyc_verified'] = $user->kyc ?? 1;
                $success['is_bank_account_added'] = $user->bank_account ?? 1;

                return response()->json(['status' => 200, 'message' => __('locale.You are successfully logged in.'), 'data' => $success]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/create-pin",
     *   summary="Create Pin",
     *   operationId="create-pin",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="pin",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      description="1 for login pin, 2 for transaction pin",
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

    public function createPin(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'pin' => 'required',
            'type' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            if ($request->type == 1)
                $user  = User::where('id', Auth::user()->id)->update(['pin' => $request->pin]);
            else if ($request->type == 2)
                $user  = User::where('id', Auth::user()->id)->update(['transaction_pin' => $request->pin]);

            return response(["status" => 200, "message" => __("locale.Pin created successfully"), 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/forget-pin",
     *   summary="Forget Pin",
     *   operationId="forget-pin",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="email",
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

    public function forgetPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail) {

                $user  = User::where('id', $checkEmail->id)->first();
                $generator = "1357902468";
                $result = "";

                for ($i = 1; $i <= 4; $i++) {
                    $result .= substr($generator, (rand() % (strlen($generator))), 1);
                }
                $user->otp = $result;

                $user->save();
                Mail::to($user->email)->send(new ForgetPinEmail($user));
                return response(["status" => 200, "message" => __("locale.OTP sent successfully"), 'data' => ["user_id" => $checkEmail->id, 'email' => $user->email, 'otp' => $user->otp]]);
            } else {

                return response()->json(['status' => 400, 'message' => __('locale.Please Enter Your Email Correctly.')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/verify-forget-pin",
     *   summary="Verify Forgot Pin",
     *   operationId="verify-forget-pin",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="otp",
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

    public function verifyForgetPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $user = User::where('id', Auth::user()->id)->where('otp', $request->otp)->first();
            if ($user) {
                $notificationData = new UserAppNotification();
                $notificationData->fill(['user_id' => $user->id, 'title' => "Pin change notification", 'message' => __('locale.You have successfully reset your login pin!'), 'type' => 'pin_changed']);
                $notificationData->save();

                return response()->json([
                    'status'   => 200,
                    'message'      => "OTP Verified",
                    'data'         => ['user_id' => $user->id]
                ], 200);
            } else {
                return response()->json(['status' => 400, 'message' => __('locale.OTP not valid')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/reset-pin",
     *   summary="Reset Pin",
     *   operationId="reset-pin",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="pin",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=true,
     *      description="1 for login pin, 2 for transaction pin",
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="c_pin",
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

    public function resetPin(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'pin'   => 'required',
            'c_pin' => 'required|same:pin',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $checkUser = User::where('id', Auth::user()->id)->first();
            if ($checkUser) {
                if ($request->type = 1) {
                    $checkUser->pin = $request->pin;
                    $checkUser->update();
                } else if ($request->type = 2) {
                    $checkUser->transaction_pin = $request->pin;
                    $checkUser->update();
                }
                return response(["status" => 200, "message" => __("locale.Pin has been Changed successfully"), 'data' => []]);
            } else {
                return response()->json(['status' => 400, 'message' => __('locale.Sorry, User not found.')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/forget-password",
     *   summary="Forget Password",
     *   operationId="forget-password",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="email",
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
     *      )
     *)
     **/

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'new_password'   => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        }
        try {
            $checkEmail = User::where('email', $request->email)->first();
            if ($checkEmail) {

                $user  = User::where('id', $checkEmail->id)->first();
                $user->password = Hash::make($request->new_password);
                $generator = "1357902468";
                $result = "";

                for ($i = 1; $i <= 4; $i++) {
                    $result .= substr($generator, (rand() % (strlen($generator))), 1);
                }
                $user->verify_token = $result;

                $user->save();
                Mail::to($user->email)->send(new ForgetPasswordEmail($user));

                return response(["status" => 200, "message" => __("locale.OTP sent successfully"), 'data' => ["user_id" => $checkEmail->id, "email" => $checkEmail->email, "otp" => $user->verify_token]]);
            } else {

                return response()->json(['status' => 400, 'message' => __('locale.Please Enter Your Email Correctly.')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    /**
     * @OA\Post(
     ** path="/api/change-password",
     *   summary="Change Password",
     *   operationId="change-password",
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

    public function changePassword(Request $request)
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
            if (Hash::check($request->old_password, $checkUser->password)) {
                $checkUser->password = Hash::make($request->new_password);
                $checkUser->update();
                return response()->json(['status' => 200, 'message' => __('locale.Password has been Changed successfully.'), 'data' => $checkUser]);
            } else {
                return response()->json(['status' => 400, 'message' => __('locale.Sorry, wrong  password.')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/profile-update",
     *   summary="Profile Update",
     *   operationId="profile-update",
     *   tags={"Profile Update"},
     *  @OA\Parameter(
     *      name="firstname",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="lastname",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="profile_photo_path",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="file"
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

    public function profileUpdate(Request $request)
    {
        try {

            $image      = $request->file('profile_photo_path');
            if ($image) {
                $fileName   = $image->getClientOriginalName();
                $Path = public_path('assets/images/user/profile');
                $image->move($Path, $fileName);
            }

            $user = Auth::user();
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            if ($image)
                $user->profile_photo_path = 'https://talal.block-brew.com/assets/images/user/profile/' . $fileName;
            $user->save();
            $success['name']  = $user->firstname . ' ' . $user->lastname;
            $success['firstname'] = $user->firstname;
            $success['lastname'] = $user->lastname;
            $success['profile_photo_path'] = $user->profile_photo_path;
            $success['user_id'] = $user->id;
            $success['email'] = $user->email;
            $success['country_code'] = $user->country_code;
            $success['mobile'] = $user->mobile;

            return response()->json(['status' => 200, 'message' => __('locale.Profile has been Updated successfully.'), 'data' => $success]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     ** path="/api/disable",
     *   summary="Disable Account",
     *   operationId="disable",
     *   tags={"Authentication"},
     
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

    public function disable(Request $request)
    {
        try {
            $user = User::whereId(Auth::id())->delete();

            return response()->json(['status' => 200, "message" => __("locale.Successfully disabled your account!")]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    /**
     * @OA\Get(
     ** path="/api/get-status",
     *   summary="Get Status of kyc and bank account",
     *   operationId="get-status",
     *   tags={"Authentication"},
     
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


    public function getStatus(Request $request)
    {
        try {
            $user = User::where('id', Auth::id())->first();
            $data['is_kyc_verified'] = $user->kyc;
            $data['is_bank_account_added'] = $user->bank_account;
            return response()->json(['status' => 200, "message" => __("locale.Successfully fetched statuses!"), 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    /**
     * @OA\Get(
     ** path="/api/get-notification-count",
     *   summary="Get Notification count",
     *   operationId="get-notification-count",
     *   tags={"Home Page"},
     
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

    public function getNotificationCount(Request $request)
    {
        try {
            $data['count'] = UserAppNotification::where('user_id', Auth::user()->id)->where('is_read', 0)->count();

            return response()->json(['status' => 200, "message" => __("locale.Successfully fetched count!"), 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     ** path="/api/disable-pin",
     *   summary="Disable Pin",
     *   operationId="disable-pin",
     *   tags={"Authentication"},
     
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

    public function disablePin(Request $request)
    {
        try {
            $user = User::whereId(Auth::id())->first();
            $user->pin = 0;
            $user->save();
            return response()->json(['status' => 200, "message" => __("locale.Successfully disabled your pin!")]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/send-google-authenticator-otp",
     *   summary="Send Google Authenticator Otp",
     *   operationId="Send Google Authenticator Otp",
     *   tags={"Authentication"},
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

    public function sendGoogleAuthenticatorOtp(Request $request)
    {

        try {

            $user = Auth::user();
            $user->google_authenticator_otp = rand(1000, 9999);;
            $user->save();
            Mail::to($user->email)->send(new GoogleAuthenticatorOtpEmail($user));

            $success['otp'] = $user->google_authenticator_otp;
            $success['user_id'] = $user->id;
            $success['email'] = $user->email;

            return response()->json(['status' => 200, 'message' => __('locale.Otp sent successfully!'), 'data' => $success]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/verify-google-authenticator",
     *   summary="Verify Google Authenticator",
     *   operationId="Verify Google Authenticator",
     *   tags={"Authentication"},
     *  @OA\Parameter(
     *      name="otp",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="type",
     *      in="query",
     *      description="1 for create, 2 for reset", 
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="secret_code",
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

    public function verifyGoogleAuthenticator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'     => 'required',
            'secret_code'     => 'required'
        ]);
        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);

        try {
            $user = User::where('id', Auth::user()->id)->where('google_authenticator_otp', $request->otp)->first();
            if ($user) {
                $user->secret_code = $request->secret_code;
                $user->save();
               
                return response()->json([
                    'status'   => 200,
                    'message'      => __("locale.Google Authenticator is Verified")
                
                ], 200);
            } else {
                return response()->json(['status' => 200, 'message' => __('locale.OTP not valid')]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
