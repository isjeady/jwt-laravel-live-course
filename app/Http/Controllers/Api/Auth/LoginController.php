<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            $errorMsg = "error credenziali";
            return  $errorMsg;
        }

        return response()->json(['token' => $token]);
    }
}
