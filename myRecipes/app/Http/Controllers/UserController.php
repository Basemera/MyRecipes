<?php
namespace App\Http\Controllers;

use App\Models\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function createUser(Request $request) {
//        $this->validate($request, [
//            'username' => 'required',
//            'email' => 'required|email',
//            'password' => 'required'
//        ]);
    return 'Successfully registered'.response()->json( Users::createUser($request), 201);
    }

    public function getAllUsers() {
        return response()->json(Users::getAllUsers(), 200);
    }

    public function getSingleUser($username) {
        $single = Users::getSingleUser($username);
        return response()->json($single, 200);
    }

    public function deleteUser($username) {
        return Users::deleteUser($username);
    }
}

?>