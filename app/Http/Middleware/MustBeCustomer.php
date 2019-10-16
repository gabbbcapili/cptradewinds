<?php

namespace App\Http\Middleware;

use Closure;

class MustBeCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->user()){
            return redirect('/home');
        }
        if($request->user()->isCustomer()){
             return $next($request); 
        }else{
            abort(403, 'Unauthorized action.');
        }
       return redirect('/home');
    }
}
