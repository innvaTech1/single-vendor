<?php



namespace App\Http\Middleware;



use Closure;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class DemoHandler

{

    public function handle(Request $request, Closure $next)

    {


        if(Route::is('admin.login') || Route::is('store-login') || Route::is('admin.logout') || Route::is('seller.login') || Route::is('subscribe-request') || Route::is('send-contact-message')){

            return $next($request);

         }
        return $next($request);

    }

}

