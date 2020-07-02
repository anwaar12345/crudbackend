<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DB;
use App\User;
use Validator;


class AuthController extends Controller
{
    //
    private $apiToken;
  public function __construct()
  {
   
    $this->apiToken = uniqid(base64_encode(str::random(60)));
  }
  /**
   * Client Login
   */
  public function CrudLogin(Request $request)
  {
    $rules = [
      'email'=>'required|email',
      'password'=>'required|min:8'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
     
      return response()->json([
        'message' => $validator->messages(),
      ]);
    } else {

      $user = User::where('email',$request->email)->first();
      if($user) {
        if( password_verify($request->password, $user->password) ) {
          $userArray = ['api_token' => $this->apiToken];
          $login = User::where('email',$request->email)->update($userArray);
          
          if($login) {
            return response()->json([
              'name'         => $user->name,
              'email'        => $user->email,
              'access_token' => $this->apiToken,
            ]);
          }
        } else {
          return response()->json([
            'message' => 'Invalid Password',
          ]);
        }
      } else {
        return response()->json([
          'message' => 'User not found',
        ]);
      }
    }
  }
  /**
   * Register
   */
  public function CrudRegister(Request $request)
  {
    $rules = [
      'name'     => 'required|min:3',
      'email'    => 'required|unique:users,email',
      'password' => 'required|min:8',
      'profile' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->messages(),
      ]);
    } else {
      
      $file_name =  str_replace(' ','_',$request->name).".".$request->profile->getClientOriginalExtension();
      if( $file_path = $request->profile->move(public_path().'/images/',$file_name)){


      $userArray = [
        'name'      => $request->name,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
        'api_token' => $this->apiToken,
        'profile' => $file_name
      ];

      
   
     
      $user = User::create($userArray);
  
     

      if($user) {
        return response()->json([
          'name'         => $request->name,
          'email'        => $request->email,
          'access_token' => $this->apiToken,
          'profile' => $file_name
        ]);
      } else {
        return response()->json([
          'message' => 'Registration failed, please try again.',
        ]);
      }
    }else{
      return response()->json([
        'message' => 'Image Moving Failed',
      ]);
    }
  }
  }
  /**
   * Logout
   */
  public function CrudLogout(Request $request)
  {
    $token = $request->header('Authorization');
    $user = User::where('api_token',$token)->first();
    if($user) {
      $userArray = ['api_token' => null];
      $logout = User::where('id',$user->id)->update($userArray);
      if($logout) {
        return response()->json([
          'message' => 'User Logged Out',
        ]);
      }
    } else {
      return response()->json([
        'message' => 'User not found',
      ]);
    }
  }






}
