<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() == null){
            return redirect()->route('home');
        }

        if ($request->user()->role == 'user') {
           session()->flash('error',Alert::error('Oops!',"You Are not Authorised to access page"));
            return redirect()->route('account.profile');
        }
        return $next($request);
    }
}
