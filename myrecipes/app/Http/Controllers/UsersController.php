<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {

    /**
     * Add a user to the database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createUser(Request $request) {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
            $hashedPassword = Hash::make($request->password);
            $newUser = new User;
            $newUser->username = $request->username;
            $newUser->email = $request->email;
            $newUser->password = $hashedPassword;
            $user = $newUser->save();

        return response()->json($newUser, 201);
    }

    /**
     * Return all users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers() {
        $allUsers = [];
        $users = User::all();
        foreach ($users as $user) {
        $userDetail = [
                'id'=>$user->id,
                'username' => $user->username,
                'email' => $user->email,
            ];
            array_push($allUsers, $userDetail);
        }
        return response()->json($allUsers, 200);
    }

    /**
     * @param Request $request
     * @param $id - id of user to be updated
     * @return \Illuminate\Http\JsonResponse - details of user
     */
    public function update(Request $request, $id) {
        $user = User::findorFail($id);
        if (!$user) {
            return response()->json('User doesnot exist', 404);
        }
        $user->update($request->all());
        return response()->json($user, 200);
    }

    /**
     * Return details of a single user
     *
     * @param $id - id of user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleUser($id) {
        return response()->json(User::findorFail($id), 200);
    }

    /**
     *  Delete a user
     *
     * @param $id - id of user to be deleted
     */
    public function deleteUser($id) {
        User::findorFail($id)->delete();
        response()->json('successfully deleted', 200);
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\Models\User   $user
     *
     * @return mixed
     */
    public function authenticate(Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }
        if (Hash::check($request->password, $user->password)) {

            return response()->json([
                'token' => User::jwt($user)
            ], 200);
        }
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * Login in a user and return token
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logIn(Request $request) {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        return $this->authenticate($request);
    }

    public function getUserCategories($id) {
        return User::find($id)->categories;
    }

}

?>