<?php

 namespace App\Repositories\Contracts;
 
 interface IChat
 {
    public function createParticipants($chat_id, array $data);
    public function getUserChats();
 }