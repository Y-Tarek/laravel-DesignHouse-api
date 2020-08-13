<?php 
namespace App\Repositories\elequint;
use App\Repositories\Contracts\IChat;
use App\Models\Chat;
use App\Repositories\elequint\BaseRepository;

class ChatRepository extends BaseRepository implements IChat
{
   public function model()
   {
       return Chat::class;//App\Models\Design
   }

   public function createParticipants($chat_id, array $data)
   {
       $chat = $this->model->find($chat_id);
       return $chat->participants()->sync($data);
   }

   public function getUserChats()
   {
     return auth()
          ->user()
          ->chats()
          ->with(['messages' , 'participants'])
          ->get();
   }

}