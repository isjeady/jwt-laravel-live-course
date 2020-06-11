<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Utils\CustomResponse;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        auth()->logout();
        return CustomResponse::setSuccessResponse(Response::HTTP_OK, Lang::get('auth.logout'));
    }
}
