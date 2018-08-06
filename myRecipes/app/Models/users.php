<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Users extends Model
{
    protected $table = 'users';

    protected $fillable = ['username', 'email', 'password'];

    static function getAllUsers() {
        $allUsers=[];
        $users = Users::all();

        foreach ($users as $user) {
            $userDetail = [
                'username' => $user->username,
                'email' => $user->email,
            ];
            array_push($allUsers, $userDetail);
        }
        return $allUsers;
    }

    static function createUser(Request $request) {
        $newUser = Users::create($request->all());
        return $newUser;
    }

    static function getSingleUser($username) {
        return Users::where('username', '=', $username)->first();
    }

    static function deleteUser($username) {
    $id = Users::where('username', '=', $username)->delete();
    return 'successfully deleted';
    }
}
?>