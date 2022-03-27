<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request){

        try {
            //validate request;
            $rules=[
                'email' => 'required|unique:users,email',
                'password' => 'required',
                'name' => 'required',
            ];
            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()){
                $code =  $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            //User register;
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$password = Hash::make($request->password),
            ]);
            $token = Auth::guard('user_api')->attempt(['email'=>$request->email,'password'=>$request->password]);
            $user->token = $token;
            if(!$user)
            {
                return $this->returnErrorResponse('Registration Failed');
            }

            return $this->returnResponseData('user',$user,'success');
        }catch (\Exception $ex){
            return  $this->returnErrorResponse($ex->getMessage());
        }

    }

    public function login(Request $request){

        try {
            //validate request;
            $rules=[
                'email' => 'required|exists:users,email',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(),$rules);

            if($validator->fails()){
                $code =  $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            //User login;
            $credentials = $request->only('email','password');
            $token = Auth::guard('user_api')->attempt($credentials);
            if(!$token)
            {
                return $this->returnErrorResponse('Not Authenticated (error credentials)');
            }
            // you cam write this query by two shaped
            //$admin = auth('admin_api')->user();
            $user = Auth::guard('user_api')->user();
            $user->token = $token;
            return $this->returnResponseData('user',$user,'success');
        }catch (\Exception $ex){
            return  $this->returnErrorResponse($ex->getMessage());
        }

    }

    public function userProfile(){
        return Auth::guard('user_api')->user();
    }
}
