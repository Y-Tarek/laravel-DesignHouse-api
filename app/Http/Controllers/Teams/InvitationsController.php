<?php

namespace App\Http\Controllers\Teams;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Contracts\IUser;
use App\Mail\SendInvitationToJoinTeam;
use App\Repositories\Contracts\IInvitation;

class InvitationsController extends Controller
{
     protected $invitations;
     protected $teams;
     protected $user;
     public function __construct(IInvitation $invitations, ITeam $teams, IUser $user)
     {
         $this->invitations = $invitations;
         $this->teams = $teams;
         $this->user = $user;
     }

     public function invite(Request $req, $teamId)
     {
         $team = $this->teams->find($teamId);
         $this->validate($req,[
           'email' => ['required','email']
         ]);

      //check if user owns the team
        $user = auth()->user();
            if(! $user->isOwnerOfTeam($team)){
                return response()->json([
                    "message" => "You are not the Owner of the Team"
                ],401); 
            }
      //check if email has a pending invitation
            if($team->hasPendingInvitation($req->email)){
                return response()->json([
                    "message" => "It has a pending Invitation"
                ],422); 
            }

       $recipient = $this->user->findByEmail($req->email);

    // User doesnot exists(not signed up)
         if(! $recipient){
             $this->createInvitation(false, $team, $req->email);   
             return response()->json([
                "message" => "Invitation sent to user"
            ],200); 
         }

    // if User already a memeber
        if($team->hasUser($recipient)){
            return response()->json([
                "message" => "Sounds like he/she is already a member of the Team"
            ],422); 
        }

        $this->createInvitation(true,$team,$req->email);
            return response()->json([
                "message" => "Invitation sent to user"
            ],200); 
    
     }

     public function resend($id)
     {
         $invitation = $this->invitations->find($id);
         $recipient = $this->user
                           ->findByEmail($invitation->recipient_email);
        $user = auth()->user();
        if(! $user->isOwnerOfTeam($team)){
            return response()->json([
                "message" => "You are not the Owner of the Team"
            ],401); 
        }

         Mail::to($invitation->recipient_email)
            ->send(new SendInvitationToJoinTeam($invitation, ! is_null($recipient)));

          return response()->json([
              "message" => "Invitation resend successfully."
          ],200);

     }

     public function respond(Request $req,$id)
     {
       $this->validate($req,[
           'token'=> ['required'],
           'desecision' => ['required','string']
       ]);
       $invitation = $this->invitations->find($id);
       $user = auth()->user();
       $token = $req->token;
       $descision = $req->desecision;
           $this->authorize('respond',$invitation);

           if($token != $invitation->token){
                return response()->json([
                    "message" => "Invalid Token"
                ],404);
            }
        
            if($descision != 'deny'){
                $this->invitations->addUserToTeam($invitation->team, $user->id);
            }
          $this->invitations->delete($id);
          return resposnse()->json([
              "message" => "responded"
          ],200);
     }

     public function destroy($id)
     {
        $invitation = $this->invitations->find($id);
        $this->authorize('delete',$invitation);
        $this->invitations->delete($id);
        return response()->json([
            "message" => "Invitation Deleted."
        ],200);
     }

     public function createInvitation(bool $user_exists,Team $team, string $email)
     {
         $invitation = $this->invitations->create([
          'team_id' => $team->id,
          'sender_id' => auth()->user()->id,
          'recipient_email' => $email,
          'token' => md5(uniqid(microtime()))
         ]);    
         Mail::to($email)
                ->send(new SendInvitationToJoinTeam($invitation, $user_exists));
     }
}
