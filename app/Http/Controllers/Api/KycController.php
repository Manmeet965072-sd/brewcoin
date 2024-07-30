<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\KYC;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class KycController extends Controller
{

    /**
     *  @OA\Post(
     **  path="/api/submit-kyc-details",
     *   summary="Submit Kyc Details",
     *   operationId="submit-kyc-details",
     *   tags={"Kyc Manager"},
     *   @OA\Parameter(
     *      name="type1",
     *      in="query",
     *      required=true,
     *      description="passport/driving/nidcard",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="type2",
     *      in="query",
     *      required=true,
     *      description="passport/driving/nidcard",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="document1_front",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="document1_back",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="document2_front",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="document2_back",
     *      in="query",
     *      required=true,
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
    //
    public function submitKycDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type1'     => 'required',
            'type2'     => 'required',
            'document1_front' => 'required',
            'document1_back' => 'required',
            'document2_front' => 'required',
            'document2_back' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        try {

            $kyc = KYC::where('userId', Auth::user()->id)->whereNotNull('document')->whereNotNull('document3')->first();
            if ($kyc == NULL) {
                $kyc = new KYC();
                $kyc->userId = Auth::user()->id;
                $user = User::find(Auth::user()->id);

                $kyc->firstName =  $user->firstname;
                $kyc->lastName = $user->lastname;
                if ($request->hasFile('document1_front')) {
                    $image      = $request->file('document1_front');


                    $fileName   = $image->getClientOriginalName();

                    // $img = Image::make($image->getRealPath());
                    // $img->resize(120, 120, function ($constraint) {
                    //     $constraint->aspectRatio();
                    // });
                    // $img->stream(); // <-- Key point

                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc->document = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                if ($request->hasFile('document1_back')) {
                    $image      = $request->file('document1_back');
                    $fileName   = $image->getClientOriginalName();
                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc->document3 = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                $kyc->documentType = $request->type1;
                $kyc->save();

                $kyc = new KYC();
                $kyc->userId = Auth::user()->id;
                $user = User::find(Auth::user()->id);

                $kyc->firstName =  $user->firstname;
                $kyc->lastName = $user->lastname;
                if ($request->hasFile('document2_front')) {
                    $image      = $request->file('document2_front');
                    $fileName   = $image->getClientOriginalName();


                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc->document = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                if ($request->hasFile('document2_back')) {
                    $image      = $request->file('document2_back');
                    $fileName   = $image->getClientOriginalName();
                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);
                    $kyc->document3 = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                $kyc->documentType = $request->type2;
                $kyc->save();
            } else {
                $kyc1 = KYC::where('userId', Auth::user()->id)->whereNotNull('document')->whereNotNull('document3')->first();
                $kyc2 = KYC::where('userId', Auth::user()->id)->whereNotNull('document')->whereNotNull('document3')->skip(1)->take(1)->first();
                if ($request->hasFile('document1_front')) {

                    $image      = $request->file('document1_front');
                    $fileName   = $image->getClientOriginalName();

                    // $img = Image::make($image->getRealPath());
                    // $img->resize(120, 120, function ($constraint) {
                    //     $constraint->aspectRatio();
                    // });
                    // $img->stream(); // <-- Key point

                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc1->document = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                if ($request->hasFile('document1_back')) {

                    $image      = $request->file('document1_back');
                    $fileName   = $image->getClientOriginalName();
                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc1->document3 = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);

                $kyc1->documentType = $request->type1;
                $kyc1->status = 'pending';
                $kyc1->save();
                if ($request->hasFile('document2_front')) {
                    $image      = $request->file('document2_front');
                    $fileName   = $image->getClientOriginalName();

                    // $img = Image::make($image->getRealPath());
                    // $img->resize(120, 120, function ($constraint) {
                    //     $constraint->aspectRatio();
                    // });
                    // $img->stream(); // <-- Key point

                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc2->document = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);
                if ($request->hasFile('document2_back')) {
                    $image      = $request->file('document2_back');
                    $fileName   = $image->getClientOriginalName();
                    $Path = public_path('assets/images/kyc');
                    $image->move($Path, $fileName);

                    $kyc2->document3 = $fileName;
                } else
                    return response()->json(['status' => 400, 'message' => __('locale.Please enter a valid file!')]);

                $kyc2->documentType = $request->type2;
                $kyc2->status = 'pending';
                $kyc2->save();
                $kyc = $kyc2;
            }
            $kyc->is_kyc_verified = 1;
            $user = User::find(Auth::user()->id);
            $user->kyc = 1;
            $user->save();
            $user->device_token = $request->device_token;
            $user->save();
            $success['firstname'] = $kyc->firstName;
            $success['lastname'] = $kyc->lastName;
            $success['user_id'] = $kyc->userId;
            $success['email'] = $user->email;
            $success['country_code'] = $user->country_code;
            $success['mobile'] = $user->mobile;
            $success['reviewed_by'] = $kyc->reviewedBy;
            $success['reviewed_at'] = $kyc->reviewedAt;
            $success['status'] = $kyc->status;
            $success['is_kyc_verified'] = $kyc->is_kyc_verified;
            return response()->json(['status' => 200, 'message' => __('locale.KYC submitted successfully!'), 'data' => $success]);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 400, 'message' => $e->getMessage(), 'status' => false], 400);
        }
    }

    /**
     *  @OA\Post(
     **  path="/api/submit-kyc-selfie-details",
     *   summary="Submit Kyc Selfie Details",
     *   operationId="submit-kyc-selfie-details",
     *   tags={"Kyc Manager"},
     *  @OA\Parameter(
     *      name="selfie_file",
     *      in="query",
     *      required=true,
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
    public function submitKycSelfieDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selfie_file'     => 'required',

        ]);

        if ($validator->fails()) return response()->json(['status' => 422, 'message' => implode(",", $validator->errors()->all())]);
        try {

            $user = User::find(Auth::user()->id);

            $kyc = new KYC();
            $kyc->userId = Auth::user()->id;
            $kyc->firstName =  $user->firstname;
            $kyc->lastName = $user->lastname;
            if ($request->hasFile('selfie_file')) {
                $image      = $request->file('selfie_file');
                $fileName   = $image->getClientOriginalName();

                $Path = public_path('assets/images/kyc');
                $image->move($Path, $fileName);

                $kyc->document2 = $fileName;
            } else
                return response()->json(['status' => 400, 'message' => __('Please enter a valid file!')]);

            $kyc->documentType = 'selfie';
            $kyc->save();
            
            $kyc->is_kyc_verified = 2;
            $user->kyc = 2;
            $user->save();
            $success['firstname'] = $kyc->firstName;
            $success['lastname'] = $kyc->lastName;
            $success['user_id'] = $kyc->userId;
            $success['email'] = $user->email;
            $success['country_code'] = $user->country_code;
            $success['mobile'] = $user->mobile;
            $success['reviewed_by'] = $kyc->reviewedBy;
            $success['reviewed_at'] = $kyc->reviewedAt;
            $success['status'] = $kyc->status;
            $success['is_kyc_verified'] = $kyc->is_kyc_verified;
            return response()->json(['status' => 200, 'message' => __('locale.KYC submitted successfully.It is in review and its status will be notified to you within 24 hours once admin approves it.!'), 'data' => $success]);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 400, 'message' => $e->getMessage(), 'status' => false], 400);
        }
    }

    /**
     *   @OA\Get(
     **  path="/api/get-document-listing",
     *   summary="Get document listing",
     *   operationId="get-document-listing",
     *   tags={"Kyc Manager"},
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

    public function getDocumentList(Request $request)
    {
        try {
            $data = ['Passport', 'Driving Licence', 'Aadhar Card', 'Pan Card', 'Voting Card'];

            return response(["status" => 200, "message" => __("locale.Documents listing fetched successfully"), 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' =>  $e->getMessage()]);
        }
    }
}
