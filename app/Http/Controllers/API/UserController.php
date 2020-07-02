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

return $this->sendResponse($users, 'Users Retrieved Successfully.');

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
  
        return $this->sendError($validator->messages());  
        // return response()->json([
        //   'message' => $validator->messages(),
        // ]);
  
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
                'api_token' => $this->apiToken  
            ];


          return $this->sendResponse($data, 'loggedin Successfully');

            // return response()->json([
          //       'data' => $data,
          //       'message' => 'user created Success fully'
          // ]);
        } else {
          return response()->json([
            'message' => 'Registration failed, please try again.',
          ]);
        }
      }

}

public function GetUserById($id)
{

  $user =  User::find($id);

  if($user){

    return $this->sendResponse($user, 'User Retrieved Successfully.');

  }else{
    return $this->sendResponse($user, 'user not found');
   }
}


public function UpdateUser(Request $request,$id)
{
   $rules = [
      'name'     => 'required|min:3',
      'email'    => 'required',
      'password' => 'required|min:8',
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      
      return $this->sendError($validator->messages());       

    
    } else {
      
      $userArray = [
        'name'      => $request->name,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
        'api_token' => $this->apiToken
      ];

      $userReturn = [
        'name'      => $request->name,
        'email'     => $request->email,
        'api_token' => $this->apiToken,
      ];
 
      $user = User::where('id',$id)->update($userArray);
     
      if($user) {
        return $this->sendResponse($userReturn, 'User Updated Successfully.');
      } else {
      return response()->json([
          'message' => 'Registration failed, please try again.',
        ]);
      }

  }
  }



}