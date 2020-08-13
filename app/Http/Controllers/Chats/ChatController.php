<?php

namespace App\Http\Controllers\Chats;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Repositories\Contracts\IChat;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\IMessage;
use App\Repositories\elequint\criteria\WithTrashed;

class ChatController extends Controller
{
  protected $chats,$messages;
    public function __construct(IChat $chats, IMessage $messages)
    {
        $this->chats = $chats;
        $this->messages = $messages;
    }

    public function sendMessage(Request $req)
    {
      $this->validate($req,[
       'recipient' => ['required'],
       'body' => ['required']
      ]);
      $chat = auth()->user()->getChatWithUser($req->recipient);
       if(! $chat){
        $chat =  $this->chats->create([]);
         $this->chats->createParticipants($chat->id,[auth()->user()->id, $req->recipient]);
       }
       $message = $this->messages->create([
           'user_id' => auth()->user()->id,
           'chat_id' => $chat->id,
           'body' => $req->body,
           'last_read' => null
       ]);
        return new MessageResource($message);
    }

    public function getUserChats()
    {
          $chats = $this->chats->getUserChats();
          return ChatResource::collection($chats);
    }

    public function  getChatMessages($id)
    {
     $messages = $this->messages
                      ->withCriteria([
                       new WithTrashed()
                      ])
                      ->findWhere('chat_id', $id);
      return MessageResource::collection($messages);
    }

    public function markAsRead($id)
    {
       $chat = $this->chats->find($id);
       $chat->markMessageAsRead(auth()->user()->id);
        return response()->json([
            "message" => "seen"
        ],200);
    }

    public function destroyMessage($id)
    {
       $message  =$this->messages->find($id);
       $this->authorize('delete',$message);
       $message->delete();
    }
}
