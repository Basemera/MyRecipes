<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {
    public function createUser(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
            $hashedPassword = Hash::make($request->password);
            $newUser = new User;
            $newUser->username = $request->username;
            $newUser->email = $request->email;
            $newUser->password = $hashedPassword;
            $newUser->save();

        return $newUser;
    }

    public function getAllUsers() {
        return response()->json(User::getAllUsers(), 200);
    }

    public function getSingleUser($username) {
        $single = User::getSingleUser($username);
        return response()->json($single, 200);
    }

    public function deleteUser($username) {
        return User::deleteUser($username);
    }

    public function logIn(Request $request) {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        return User::authenticate($request);
    }
}

?>