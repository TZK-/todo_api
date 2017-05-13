<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    public function test_token_generation()
    {
        $credentials = ['email' => 'test@example.com', 'password' => Hash::make('password')];

        $user = factory(User::class)->create($credentials);

        $this->json('POST', 'auth/login/', $credentials)
            ->seeStatusCode(200)
            ->seeJson(['token' => JWTAuth::fromUser($user)]);
    }
}
