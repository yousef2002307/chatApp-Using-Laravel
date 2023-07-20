<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Functions extends Controller
{
    
   
    public function Leftside()
    {
       
       $id =  Auth::user()->id;
       //first we select all users that are not $id
       $sql = 'SELECT id FROM `users` WHERE id != ?';
        $users = DB::select($sql,[$id]);
      $newarr = [];
       //then we chack in () or () wheter taht newid share with $id any messages in messages table and if it does we psh it id into new id i created
       foreach($users as $user){
        $id2 =  $user->id;
        //this sql will get us the last message from user
        $vals = DB::select('SELECT * FROM `messages` WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?) ORDER BY id DESC LIMIT 1',[$id,$id2,$id2,$id]);
        $mId = $vals[0]->id;
       $count =  count($vals) ;
       if($count > 0){
        $newarr[$mId] = $id2;
       }
    
    }
    krsort($newarr);
    
    $theselectedusers = [];
    $i =0; 
      foreach ($newarr as $key => $value) {
        $sql2 = "SELECT * FROM `users` WHERE id =?";
       $user = DB::select($sql2,[$value]);
       
       $theselectedusers[$i++] = array($user[0]->name,$user[0]->id);
      }
    
      /*
       $sql2 = "SELECT * FROM `users` WHERE id IN(" . implode(",",$newarr) . ")";
       $theselectedusers = DB::select($sql2);
       */
      //get teh last messages
      $messagesarray = [];
      foreach($newarr as $user){
       $id2 = $user;
       $subarray = [];
        //this sql will get us the last message from user
        $vals = DB::select('SELECT * FROM `messages` WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?) ORDER BY id DESC LIMIT 1',[$id,$id2,$id2,$id]);
        $message = $vals[0]->message;
        if(intval($vals[0]->from_user) === $id2){
          $bool = false;
        }else{
          $bool = true;
        }
        array_push($subarray,$message);
        array_push($subarray,$bool);
        array_push($messagesarray,$subarray);
    }
    $lastmessage = DB::select("SELECT * FROM `messages` WHERE from_user = ? OR to_user = ? ORDER BY id DESC LIMIT 1",[Auth::user()->id,Auth::user()->id]);
    session(['lastId' => $lastmessage[0]->id ]);
      return [$theselectedusers,$messagesarray];
      
        
    }
    function RightSide($id = null){
     $themainuserid = Auth::user()->id;
     $sql = "SELECT * FROM `messages` WHERE from_user = ? OR to_user = ? ORDER BY id DESC LIMIT 1";
     $thelastmessage = DB::select($sql, [$themainuserid,$themainuserid]);
     /* here we et the id we will use*/
     if($id){
      $thesubuserid = $id;
     }else{
      if($thelastmessage[0]->from_user !== $themainuserid){
        $thesubuserid = $thelastmessage[0]->from_user;
      }else{
        $thesubuserid = $thelastmessage[0]->to_user;
      }
     }
      /* here we et the id we will use*/
      $messages = DB::select('SELECT * FROM `messages` WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?) ',[$themainuserid,$thesubuserid,$thesubuserid,$themainuserid]);
      
     
     return $messages;
    }
}
