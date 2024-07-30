<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($role == 'admin' && auth()->user()->role_id != 1 && auth()->user()->role_id != 3) {
            abort(403);
           // return response()->json(['error'=>'You are not authorised to access admin panel since you are a user!!!!']);
           // return redirect()->back()->with('message', 'You are not authorised to access admin panel since you are a user!!!!');
           //return back();
        }

        if ($role == 'user' && auth()->user()->role_id != 2 && auth()->user()->role_id != 1) {
            abort(403);
           //return 'failed';
            //return response()->back(['error'=>'You are not authorised to access user panel since you are an admin!!!!']);
           // return redirect()->back()->with('message', 'You are not authorised to access user panel since you are an admin!!!!');
           //return back();

        }

        return $next($request);
    }
}
