<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enum\UserStatus;


class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            $dateAfter = DB::table('sub_count')
                ->where('user_id', auth()->id())
                ->orderBy('id', 'desc')
                ->first();

            if ($dateAfter) {
                $d_date = Carbon::parse($dateAfter->expires_at)->toDateString();

                if (now()->toDateString() >= $d_date) {

                    DB::table('users')
                        ->where('id', auth()->id())
                        ->update([
                            'role_id' => UserStatus::Guest
                        ]);

                    DB::table('sub_count')
                        ->where('id', $dateAfter->id)
                        ->update([
                            'status' => 'notactive'
                        ]);
                }
            }
        }

        return $next($request);
    }
}
