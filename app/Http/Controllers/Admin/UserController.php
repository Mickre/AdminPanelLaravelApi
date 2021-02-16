<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        return new UserResource(Auth::user());
    }

    public function update(UserRequest $request)
    {
        $user = Auth::user();

        $data = $request->only('name', 'email');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
            $user->update($data);
        } else {
            $user->update($data);
        }

        return new UserResource($user);
    }
}
