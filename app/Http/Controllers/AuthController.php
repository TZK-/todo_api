<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required',
        ]);

        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], 500);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }

        return response()->json(compact('token'));
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

        return response()->json(['token' => JWTAuth::fromUser($user)], 200);
    }
}