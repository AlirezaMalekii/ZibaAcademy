<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class CheckUserActive
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
        if(auth()->check()){
            if(! (auth()->user()->otps()->where('used',true)->get()->first())){
                $phone=auth()->user()->phone;
                auth()->logout();
                return redirect()->route('lwo',compact('phone'));
            }
        }
        return $next($request);
    }
}
