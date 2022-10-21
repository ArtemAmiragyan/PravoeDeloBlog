<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controller;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param  UserService $userService
     * @return array<string, string|User> $items
     */
    public function __invoke(RegisterRequest $request, UserService $userService): array
    {
        return $userService->register($request->validated());
    }
}
