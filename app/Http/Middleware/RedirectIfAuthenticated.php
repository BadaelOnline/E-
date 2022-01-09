<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param mixed ...$guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guard)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }
//        switch ($guard)
//        {
//            case 'employee-api':
//                if($user=Auth::guard($guard)->check())
//                {
//                    return redirect()->route('admin.dashboard');
//                }
//                break;
//            case 'user-api':
//                if($user=Auth::guard($guard)->check()) {
//                    $id = Auth::guard($guard)->user()->id;
//                    $user = User::with('TypeUser')->find($id);
//                    $types = $user->TypeUser()->get();
//                    foreach ($types as $type) {
//                        $name[] = $type['name'];
//
//                        switch ($type['name']) {
//                            case 'doctor':
//                                return redirect()->route('doctor_dashboard');
//                                break;
//                            case 'store_admin':
//                                return redirect()->route('store_dashboard');
//                                break;
//                            default:
//                                return route('profile');
//                                break;
//                        }
//                    }
//                }
//        }

        /*
        if (Auth::guard($guard)->check())
        {
            return redirect('/home');
        }
        */

        return $next($request);
    }
}
