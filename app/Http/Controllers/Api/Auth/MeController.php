<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateUserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __invoke(Request $request)
    {
        # code...
        return new PrivateUserResource($request->user());
    }
}
