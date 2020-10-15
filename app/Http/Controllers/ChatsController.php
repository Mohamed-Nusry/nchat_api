<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ValidationTrait;
use App\ChatConversation;
use App\Chats;
use App\User;
use Webpatser\Uuid\Uuid;

class ChatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
      //Show All 
    public function showAllChat(Request $request,$id)
    {
        $chatmessages= Chats::all();
        return response()->json($chatmessages, 200);
    }
    
    public function showOneChat(Request $request,$id){
      $chatmessage= Chats::where('sender_id',$id)->first();
      return response()->json($chatmessage, 200); 
    }

    public function getSingleMessages(Request $request,$id){
      
    //  $singleChats= Chats::where('chat_conversation_id',$id)->get();
    //   return response()->json($singleChats, 200); 

    // $singleChats = Chats::find($id);

      $singleChats =Chats::Select('chats.chat_conversation_id','chats.sender_id','chats.receiver_id','sender.username as username','sender.first_name as sender_firstname','sender.last_name as sender_lastname','sender.avatar as sender_avatar','chats.message','chats.created_at','receiver.username as receivername','receiver.first_name as receiver_firstname','receiver.last_name as receiver_lastname','receiver.avatar as partner_avatar')
      ->join('chat_conversations', 'chats.chat_conversation_id' ,'=', 'chat_conversations.id')
      ->join('users as sender', 'chats.sender_id' ,'=', 'sender._id')
      ->join('users as receiver', 'chats.receiver_id' ,'=', 'receiver._id')
     ->where('chats.chat_conversation_id',$id)
     ->get();
        
         return response()->json(  $singleChats, 200); 
   }



    public function create(Request $request){

      $this->validate($request, [
        'message' => 'required',
       
     ]);  
      try {
       // $chat_conversation_id =ChatConversation::where('sender_id',$request->chat_conversation_id);
        
            $chatmessage=new Chats;

            $userId = Auth::user()->_id;
            $chatmessage->chat_conversation_id= $request->chat_conversation_id;;
            $chatmessage->sender_id= $userId;
            $chatmessage->receiver_id=$request->receiver_id;
            $chatmessage->chat_conversation_id=$request->chat_conversation_id;
            $chatmessage->message=$request->message;
            
            $chatmessage->save();
            return response() -> json($chatmessage, 201);
        } catch (Exception $e) {
           //return error message
           throw new NotFoundException('chat Failed');
     }

    } 
    

    //Update
    // public function update($id, Request $request){
    //   $chatmessage= Chats::findOrFail($id);
    //   $chatmessage->update($request->all());
    //   return response()->json($chatmessage, 200);
    // }

    
    public function delete($id){
      Chat::findOrFail($id)->delete();
      return response('Deleted Successfully', 200);  
    }

    
    
    
    

}
