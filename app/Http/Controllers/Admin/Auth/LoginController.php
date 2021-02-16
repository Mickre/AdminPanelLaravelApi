<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->username,
                    'password' => $request->password,
                    'grant_type' => 'password',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json(['message' => 'Nieprawidłowe dane. Wprowadź poprawną nazwę lub hasło'], $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json(['message' => 'Twoje dane są nieprawidłowe. Spróbuj ponownie.'], $e->getCode());
            }
            return response()->json(['message' => 'Coś poszło nie tak.'], $e->getCode());
        }
    }
}
