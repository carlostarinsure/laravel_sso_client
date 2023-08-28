<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use App\Models\DummyUser;

class SSOController extends Controller
{
    public function getLogin(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id'     => config('app.client_id'),
            'redirect_uri'  => config('app.url') . '/callback',
            'response_type' => 'code',
            'scope'         => '',
            'state'         => $state
        ]);

        return redirect(config('app.auth_server') . '/oauth/authorize?' . $query);
    }

    public function getCallback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(strlen($state) > 0 && $state === $request->state, InvalidArgumentException::class);

        $response = Http::asForm()->post(
            env('AUTH_SERVER') . '/oauth/token',
            [
                'grant_type'    => 'authorization_code',
                'client_id'     => config('app.client_id'),
                'client_secret' => config('app.client_secret'),
                'redirect_uri'  => config('app.url') . '/callback',
                'code'          => $request->code
            ]
        );

        $request->session()->put($response->json());
        return redirect(route('sso.connect'));
    }

    public function connectUser(Request $request)
    {
        $access_token = $request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
        ])->get(config('app.auth_server') . '/api/user');

        $userArray = $response->json();

        // Simulate user for auth login in app
        if (isset($userArray['email']) && $userArray['email']) {
            $dummyUser = new DummyUser($userArray);
            Auth::login($dummyUser);
        }

        return redirect(route('home'));

    }
}
