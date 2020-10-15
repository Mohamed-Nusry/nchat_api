<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ValidationTrait;
use App\JobCategories;
use App\WorkersJobCat;
use App\ChatConversation;
use App\User;
use Webpatser\Uuid\Uuid;

class ChatConversationController extends Controller
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
    public function showAllChatConversation(Request $request,$id)
    {
        $chat_conversation= ChatConversation::all();
        return response()->json($chat_conversation, 200);
    }
    
    public function showOneChatConversation(Request $request,$id){
      $userId = Auth::user()->_id;

      $get_chat_conversation= ChatConversation::where(function ($q) use($userId,$id) {
        $q->where('partner_id', $id)->Where('user_id', $userId);
        $q->orwhere('partner_id', $userId)->Where('user_id', $id);
      })->count();

      // $get_chat_conversation= ChatConversation::where('partner_id',$id)->where('user_id',$userId)
      // ->where(function ($q) {
      //   $q->where('age', Auth::user()->age)->orWhere('age', 0);
      // ->count();

    //   $get_chat_conversation = ChatConversation::where('user_id', $id)->where(function ($q) {
    //     $q->where('age', Auth::user()->age)->orWhere('age', 0);
    // })->get();

      if($get_chat_conversation > 0){
        // $user_chat_conversation= ChatConversation::where('partner_id',$id)->where('user_id',$userId)->first();

        $user_chat_conversation= ChatConversation::where(function ($q) use($userId,$id) {
          $q->where('partner_id', $id)->where('user_id', $userId);
          $q->orwhere('partner_id', $userId)->where('user_id', $id);
        })->first();

        return response()->json($user_chat_conversation, 200); 

      }else{

        try {
          $chat_conversation=new ChatConversation;
        
          $chat_conversation->user_id= $userId;
          $chat_conversation->partner_id=$id;
          $chat_conversation->save();
          return response() -> json($chat_conversation, 200);
      } catch (Exception $e) {
         //return error message
         throw new NotFoundException('chat Failed');
        }



      }

       }

       
      public function getAuthChatConversations(){
        $userId = Auth::user()->_id;
  
        // $get_auth_chat_conversations= ChatConversation::where(function ($q) use($userId) {
        //   $q->where('partner_id', $userId);
        //   $q->orWhere('user_id', $userId);
        // })->get();

      $get_auth_chat_conversations =ChatConversation::Select('chat_conversations.id','chat_conversations.user_id','chat_conversations.partner_id','sender.username as sender_username','sender.first_name as sender_firstname','sender.last_name as sender_lastname','sender.avatar as sender_avatar','receiver.username as receivername','receiver.first_name as receiver_firstname','receiver.last_name as receiver_lastname','receiver.avatar as receiver_avatar')
      ->join('users as sender', 'chat_conversations.user_id' ,'=', 'sender._id')
      ->join('users as receiver', 'chat_conversations.partner_id' ,'=', 'receiver._id')
      ->where(function ($q) use($userId) {
          $q->where('partner_id', $userId);
          $q->orWhere('user_id', $userId);
        })->get();

 
        return response() -> json($get_auth_chat_conversations, 200);
 
      }

    // public function create(Request $request){

    //   $this->validate($request, [
    //     'message' => 'required',
       
    //  ]);  
    //   try {

    //         $chat_conversation=new ChatConversation;
    //         $userId = Auth::user()->_id;
    //         $chat_conversation->user_id= $userId;
    //         $chat_conversation->partner_id=$request->receiver_partnerId;
    //         $chat_conversation->save();
    //         return response() -> json([$chat_conversation, 201]);
    //     } catch (Exception $e) {
    //        //return error message
    //        throw new NotFoundException('chat Failed');
    //  }

    // } 
    

    //Update
    // public function update($id, Request $request){
    //   $chatmessage= ChatConversation::findOrFail($id);
    //   $chatmessage->update($request->all());
    //   return response()->json($chatmessage, 200);
    // }

    
    public function delete($id){
      ChatConversation::findOrFail($id)->delete();
      return response('Deleted Successfully', 200);  
    }

    public function getChatUserDetails(Request $request){
      $user_id = $request->user_id;
      $partner_id = $request->partner_id;

      $user_details = User::where('_id',$user_id)->first();
      $partner_details = User::where('_id',$partner_id)->first();

      return response() -> json(['user_details' => $user_details,'partner_details' => $partner_details], 201);
}
}
