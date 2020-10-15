<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model 
{
     public $table = 'chat_conversations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'partner_id',
        'created_at',
        'updated_at',
    
        
    ];






    
}