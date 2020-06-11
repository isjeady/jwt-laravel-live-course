<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Utils\CustomResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpFoundation\Response;


class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            $errorMsg = Lang::get('auth.credential_incorrect');
            return  CustomResponse::setFailResponse($errorMsg, Response::HTTP_NOT_ACCEPTABLE, []);
        }

        return response()->json(['token' => $token]);
    }
}
