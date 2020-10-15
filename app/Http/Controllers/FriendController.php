<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\ValidationTrait;
// use App\JobCategories;
// use App\WorkersJobCat;
use App\FriendReq;
use App\User;
use Webpatser\Uuid\Uuid;

class FriendController extends Controller
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


    public function showFriendRequests(){

      $userId = Auth::user()->_id;

    //   $friendRequests =FriendReq::Select('friendreq.user_id','friendreq.partner_id','friendreq.status','users._id','users.username','users.first_name','users.last_name','users.avatar')
    //   ->join('users', 'friendreq.user_id' ,'=', 'users._id')
    //  ->where('friendreq.partner_id',$userId)
    //  ->get();

      $friendRequests =FriendReq::Select('friendreq.user_id','friendreq.partner_id','friendreq.status','users._id','users.username','users.first_name','users.last_name','users.avatar','receiver.username as receivername','receiver.first_name as receiver_firstname','receiver.last_name as receiver_lastname','receiver.avatar as receiver_avatar')
      ->join('users', 'friendreq.user_id' ,'=', 'users._id')
      ->join('users as receiver', 'friendreq.partner_id' ,'=', 'receiver._id')
      ->where(function ($q) use($userId) {
        $q->where('friendreq.partner_id', $userId);
        $q->orWhere('friendreq.user_id', $userId);
      })->get();

     return response()->json(  $friendRequests, 200); 


    }


    public function create(Request $request){

      $this->validate($request, [
        'partnerId' => 'required', 
        'status' => 'required',
       
     ]);  

      try {

            $friend_request = new FriendReq;
            $userId = Auth::user()->_id;
            $friend_request->user_id= $userId;
            $friend_request->partner_id=$request->partnerId;
            $friend_request->status=$request->status;
            $friend_request->save();
            return response() -> json([$friend_request, 201]);
        } catch (Exception $e) {
           //return error message
           throw new NotFoundException('Friend Request Failed');
     }

    } 

    public function update(Request $request,$id){

      // return $id;

      $this->validate($request, [
        'status' => 'required', 
      ]);  

      try {

            $userId = Auth::user()->_id;

            $friend_request_update = FriendReq::where(['user_id'=>$id,'partner_id'=>$userId])->first();

            // return $request->status;
          
            $friend_request_update->status=$request->status;
            $friend_request_update->save();

            return response() -> json([$friend_request_update, 201]);
        } catch (Exception $e) {
           //return error message
           throw new NotFoundException('Friend Request Failed');
     }

    } 
    

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


}
