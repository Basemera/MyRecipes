<?php
namespace App\Http\Middleware;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Models\User;

class AllUsersAuthMiddleware {
    public function handle($request, Closure $next) {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }
        $user = User::find($request->route()[2]['id']);
        if (!$user) {
            return response()->json([
                'error' => 'You are not authorized to access this resource'
            ], 400);
        } else{
            $request->auth = $user;
            return $next($request);
        }
    }
}