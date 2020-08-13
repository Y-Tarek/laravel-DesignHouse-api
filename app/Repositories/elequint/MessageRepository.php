<?php 
namespace App\Repositories\elequint;
use App\Models\Message;
use App\Repositories\Contracts\IMessage;
use App\Repositories\elequint\BaseRepository;

class MessageRepository extends BaseRepository implements IMessage
{
   public function model()
   {
       return Message::class;//App\Models\Design
   }

   

}