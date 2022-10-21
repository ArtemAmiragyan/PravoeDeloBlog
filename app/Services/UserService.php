<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    /**
     * @param User $user
     * @return array<string, string|User> $items
     */
    public function login(User $user): array
    {
        return [
            'user' => $user,
            'token' => $user->createToken('default')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        /** @var PersonalAccessToken $accessToken */
        $accessToken = $user->currentAccessToken();
        $accessToken->delete();
    }

    /**
     * @param array<string, string> $data
     * @return array<string, string|User> $items
     */
    public function register(array $data): array
    {
        $user = User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);

        return $this->login($user);
    }
}
