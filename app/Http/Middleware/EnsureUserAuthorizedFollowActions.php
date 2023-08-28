<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAuthorizedFollowActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
        {
            $followerUserId = $request->route('follower_user_id');
            $loggedInUserId = auth()->id();

            if ($followerUserId != $loggedInUserId) {
                return response()->json(['message' => 'Unauthorized action'], 403);
            }

            return $next($request);
        }
}
