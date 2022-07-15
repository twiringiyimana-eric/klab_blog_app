<?php
   namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;




class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:255',
             'email'=>'required|max:255',
             'password'=>'required|max:255'
        ]);
        $user = User::create([
            'name' => $request->name,
            
            'email' => $request->email,
            'role' => 'user',
            'password' => Hash::make($request->password)
        ]);
        $token=$user->createToken('myApp')->plainTextToken;
        $req=[
        'Token'=>$token,
        'user'=>$user
        ];
        return response()->json($req,201);
    }
    
    public function logout(Request $request){

     auth()->user()->tokens()->delete();
     return[
   'message'=>'logout'

     ];

    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('MyApp')->plainTextToken;
                $res = [
                    'token' => $token,
                    'user' => $user
                ];
                return response()->json($res, 200);
            } else
                return response()->json(['error' => 'Invalid password'], 401);
        } else
            return response()->json(['error' => 'User not found'], 404);
    }

}

