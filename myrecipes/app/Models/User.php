<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;


class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['username', 'email', 'password'];

    /**
     * Create a new token.
     *
     * @param \App\Models\User $user
     * @return string
     */
    static function jwt(User $user) {
        if (!$user->id) {
            return "user doesnot exist";
        }
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 3600*3600
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Method to return the user's categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasmany
     */
    public function categories() {
        return $this->hasMany('App\Models\Category', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentor() {
        return $this->hasMany('App\Models\Comments');
    }

}
?>