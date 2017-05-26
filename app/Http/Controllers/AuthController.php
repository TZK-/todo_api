<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    protected $request;

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(Request $request, JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login()
    {
        $this->validate($this->request, [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required',
        ]);

        try {
            if (!$token = $this->jwt->attempt($this->request->only('email', 'password'))) {
                return response()->json(['error' => 'user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 500);
        } catch (JWTException $e) {
            return response()->json(['error' => 'token_absent'], 422);
        }

        return response()->json([
            'token' => $token, 
            'user' => User::where('email', $this->request->get('email'))->first()
        ]);
    }

    public function register()
    {
        $this->validate($this->request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = new User;
        $user->email = $this->request->get('email');
        $user->password = $this->request->get('password');

        $user->save();

        return response()->json(['token' => $this->jwt->fromUser($user), 'user' => $user], 200);
    }
}