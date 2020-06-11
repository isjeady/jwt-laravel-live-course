<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\UserRegisteredEvent;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use App\Listeners\UserRegisteredListener;

class RegisterController extends Controller
{

    public function action(RegisterRequest $request)
    {
        # code...
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        if ($user != null) {
            event(new UserRegisteredEvent($user));
        }

        return $user;
    }
}
