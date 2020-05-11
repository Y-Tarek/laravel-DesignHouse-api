<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Repositories\Contracts\IUser;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    protected $users;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUser $users)
    {
        $this->users = $users;
        //$this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $req, User $user)
    {
      //check if url is a valid signed url
      if(!URL::hasValidSignature($req)){
          return response()->json(["errors" => [
              "message" => "Invalid verification link"
          ]],422);
      }

      //check  if user has already verified
      if($user->hasVerifiedEmail()){
          return response()->json(["errors" => [
              "message" => "email address has already verified"
          ]],422);
      }

      $user->markEmailAsVerified();
      event(new Verified($user));
      return response()->json(["message" => "Email succsefully Verified"],200);
    }

    public function resend(Request $req, User $user)
    {
        $this->validate($req,[
            'email' => ['email','required']
        ]);
        $user = $this->users->findWhereFirst('email',$req->email);
         if(! $user){
             return response()->json(['errors' => [
                 'email' => 'No user could be found'
             ]],422);
         }

        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "email address has already verified"
            ]],422);
        }

         $user->sendEmailVerificationNotification();
          return response()->json(['message' => 'Email resended succesfully'],200);


        
    }
}
