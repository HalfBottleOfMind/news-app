<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Return authenticated user
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        return response(auth()->user()->load(['roles', 'permissions']));
    }
}
