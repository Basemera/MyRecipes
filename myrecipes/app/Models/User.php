<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;


class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['username', 'email', 'password'];

    static function getAllUsers() {
        $allUsers=[];
        $users = User::all();

        foreach ($users as $user) {
            $userDetail = [
                'username' => $user->username,
                'email' => $user->email,
            ];
            array_push($allUsers, $userDetail);
        }
        return $allUsers;
    }



    public function checkPassword($password) {

    }

    static function getSingleUser($username) {
        return User::where('username', '=', $username)->first();
    }

    static function deleteUser($username) {
        $id = User::where('username', '=', $username)->delete();
        return 'successfully deleted';
    }

    /**
     * Create a new token.
     *
     * @param  \App\Models\User   $user
     * @return string
     */
    static function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60*60
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    static function authenticate(Request $request) {
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

}
?>