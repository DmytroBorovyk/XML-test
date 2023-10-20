<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * @throws \Exception
     */
    public function getUsers(): Response|Application|ResponseFactory
    {
        return $this->userService->getUsers();
    }
}
