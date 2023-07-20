<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Functions;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $functions = new Functions;
      $returnedvals = $functions->Leftside();
      $theselectedusers  = $returnedvals[0];
      $messages = $returnedvals[1];
      $theRightMessages = $functions->RightSide();
        return view('home',compact('theselectedusers','messages','theRightMessages'));
    }
    public function addM(Request $request){
       if( $request->case){
        $lastmessage = DB::select("SELECT * FROM `messages` WHERE from_user = ? OR to_user = ? ORDER BY id DESC LIMIT 1",[Auth::user()->id,Auth::user()->id]);
       if(session("lastId") == $lastmessage[0]->id){
        return response()->json(['success'=>"no"]);
       }
       $functions = new Functions;
       $returnedvals = $functions->Leftside();
       $vals = $functions->RightSide();
       return response()->json(['success'=>"yes",'result'=>$vals,"left" => $returnedvals]);
       }
        DB::table('messages')->insert([
            'from_user' => Auth::user()->id,
            'to_user' => $request->id,
            'message' => $request->text
        ]);
        $functions = new Functions;
        $returnedvals = $functions->Leftside();
        $vals = DB::select('SELECT * FROM `messages` WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?) ORDER BY id ASC ',[Auth::user()->id,$request->id,$request->id,Auth::user()->id]);
        $lastmessage = DB::select("SELECT * FROM `messages` WHERE from_user = ? OR to_user = ? ORDER BY id DESC LIMIT 1",[Auth::user()->id,Auth::user()->id]);
        session(['lastId' => $lastmessage[0]->id ]);
        return response()->json(['success'=>"heello amssh shsajh",'result'=>$vals,"left" => $returnedvals]); 
    }
    public function update(Request $request) {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);
    
        // Update the user record in the database
        
        
        $user = User::findOrFail(Auth::user()->id);
      
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();
    
        // Redirect back to the user's profile
        return redirect()->route('editpro', ['id' => $user->id])
            ->with('success', 'User profile updated successfully');
            
    }
}
