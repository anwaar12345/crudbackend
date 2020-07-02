<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;

class UserController extends Controller
{
    //
    private $apiToken;
    public function __construct()
    {
     
      $this->apiToken = uniqid(base64_encode(str::random(60)));
    }

public function index(){
$users = User::all();
if($users->count() > 0) {

return $this->sendResponse($users, 'User Retrieved Successfully.');

// return response()->json([
//     'data'         => $users,
//     'message' => 'Users Retrieved SuccessFully'
//   ]);

}else{

  return $this->sendResponse($users, 'no users found');
    // return response()->json([
    //     'data' => $users,
    //     'message' => 'No User Found'
    //     ]);
}
}


public function CreateUser(Request $request)
{


    $rules = [
        'name'     => 'required',
        'email'    => 'required|unique:users,email',
        'password' => 'required|min:8'
      ];
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
        return response()->json([
          'message' => $validator->messages(),
        ]);
      } else {
        $userArray = [
          'name'      => $request->name,
          'email'     => $request->email,
          'password'  => Hash::make($request->password),
          'api_token' => $this->apiToken
        ];
  
        $user = User::create($userArray);
    
        if($user) {
            $data = [
                'name'         => $request->name,
                'email'        => $request->email,
            ];
          return response()->json([
                'data' => $data,
                'message' => 'user created Success fully'
          ]);
        } else {
          return response()->json([
            'message' => 'Registration failed, please try again.',
          ]);
        }
      }

}





}