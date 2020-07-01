<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //

public function index(){

$users = User::all();
if($users->count() > 0) {

return response()->json([
    'data'         => $users,
    'message' => 'Users Retrieved SuccessFully'
  ]);

}else{

    return response()->json([
        'data' => $users,
        'message' => 'No User Found'
        ]);

}



}

}