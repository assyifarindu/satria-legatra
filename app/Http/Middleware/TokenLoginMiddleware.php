<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TokenLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('q');
        if ($token) {
            $user = $this->findUserByToken($token);
            if ($user) {
                $update = User::where('id', $user->id)->update([
                    'accessed_app' => '31',
                ]);

                if( $update) {
                    Auth::login($user);
                    return redirect($request->url());
                }else{
                    return redirect(env('ENV_SATRIA'));
                }
            } else {
                return redirect(env('ENV_SATRIA'));
            }
        }else{
            if(Auth::check()){
                return $next($request);
            }
            return redirect(env('ENV_SATRIA'));
        }
    }

    /**
     * Find user by token.
     *
     * @param  string  $token
     * @return \App\Models\User|null
     */
    protected function findUserByToken($token)
    {
        // Cek tokennya dulu
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get(env('ENV_SATRIA_API') . '/api/satria-user-profile');

        if ($response->successful() && $response->json('success')) {
            $userData = $response->json('data.user');
            if ($userData && isset($userData['id']) && isset($userData['personal_number'])) {
                // Cek apakah user punya akses ke aplikasi ini
                $responsePermission = Http::withOptions(['verify' => false])->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ])->get(env('ENV_SATRIA_API') . '/api/satria-get-permission-action-menu', [
                    'menu' => 'home',
                    'app' => 31,
                    'action' => 'v',
                ]);

                if ($responsePermission->successful() && $responsePermission->json('success')) {
                    $permissions = $responsePermission->json('data.response');
                    if (!empty($permissions)) {
                        return User::where('id', $userData['id'])->where('personal_number', $userData['personal_number'])->first();
                    }else {
                        return null;
                    }
                }else{
                    return User::where('id', $userData['id'])->where('personal_number', $userData['personal_number'])->first();
                }
            }
        }

        return null;
    }
}