<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LoginController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
    }

    /**
     * @param LoginRequest $request
     * @return array<string, string|User> $items
     */
    public function __invoke(LoginRequest $request): array
    {
        return $this->userService->login($request->getLoginUser());
    }

    /**
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void
    {
        $this->userService->logout($request->user());
    }

    public function user(Request $request): User
    {
        return $request->user();
    }
}
