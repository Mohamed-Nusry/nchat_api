<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model 
{
     public $table = 'chats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chat_conversation_id',
        'sender_id',
        'receiver_id',
        'message',
        'created_at',
        'updated_at',
    
        
    ];






    
}