<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Crypt;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth_subdomain_url = config('app.constants.auth_subdomain_url');
        $app_subdomain_url = config('app.constants.app_subdomain_url');
        $token = $request->pt;
        $decrypted = Crypt::decryptString($token);

       if ($decrypted) {
        $response = Http::withToken($decrypted)->get($app_subdomain_url."/api/user");
        $loggedUserInfo = $response->body();
        $rel = json_decode($loggedUserInfo);
        $user = User::where('id',$rel->user_details->id)->first();
        return $next($request);

       }

        return Redirect::to($auth_subdomain_url);

        
        
        
    }
}
