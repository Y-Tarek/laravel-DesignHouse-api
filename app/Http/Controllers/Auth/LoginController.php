<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected function attemptLogin(Request $req){
        $token = $this->guard()->attempt($this->credentials($req)); 
         if(! $token){
             return false;
         }
        //get authinticated user
         $user = $this->guard()->user();

         if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
             return false;
         }

         $this->guard()->setToken($token);
         return true;

    }
   
    protected function sendLoginResponse(Request $req){
        $this->clearLoginAttempts($req);
        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');
         return response()->json([
             "token" => $token,
             "token_type" => "bearer",
             "expires_in" => $expiration
         ],200);
    }


    protected function sendFailedLoginResponse(Request $req){
        $user = $this->guard()->user();
        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json([
                "errors" => "you need to verify your email"
            ]);
            throw ValidationException::withMessages([
                $this->username() => "Authintication failed"
            ]);
        }

    }

    public function logout(){
        $this->guard()->logout();
        return response()->json(["message" => "successfully logged put"],200);
    }

}
