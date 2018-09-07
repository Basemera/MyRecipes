<?php
//namespace App\helpers;
namespace App\Http\helpers;

use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Http\Request;
class currentUser {
    public function getCurrentUser($request) {
        $token = $request->header('Authorization');
        $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        $currentUser = User::find($credentials->sub);
        return $currentUser;
    }
}
