<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request){
        try {
            //validate request
            $rules=[
                'email' => 'required|exists:admin,email',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()){
                $code =  $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            //Admin Login
            $credentials = $request->only('email','password');
            $token = Auth::guard('admin_api')->attempt($credentials);
            if(!$token)
            {
                return $this->returnErrorResponse('Not Authenticated');
            }
            // you cam write this query by two shaped
            //$admin = auth('admin_api')->user();
            $admin = Auth::guard('admin_api')->user();
            $admin->token = $token;
            return $this->returnResponseData('admin',$admin,'success');

        }catch (\Exception $ex){
            return  $this->returnErrorResponse($ex->getMessage());
        }

    }

    public function logout(Request $request){

        try {
            $token = $request->header('auth_token');
            if($token){

                \JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMessage('success');
            }else{
                return $this->returnErrorResponse('Some thing wrong happened');
            }
        }catch (\Exception $ex){
            return $this->returnErrorResponse($ex->getMessage());
        }

    }

    public function adminProfile(){
        try {
            return Auth::guard('admin_api')->user();
        }catch (\Exception $ex){
            return $this->returnErrorResponse("Some thing error happened");
        }

    }
}
