<?php

namespace Smart\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPassword
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
        $user = $request->user();

        if($request->pwd != $request->pwdConfirm){
            
            return response()->json(ajax_arr('两次输入的新密码不一致', 500));
        }
        $oldPwd = Hash::make($request->oldPwd);

        if(!Auth::guard()->attempt(
            ['username' => $request->user()->username , 'email' => $request->user()->username, 'password' => $request->oldPwd]
        )){

            return response()->json(ajax_arr('原密码错误', 500));
        }
        $request->offsetSet('password', $request->pwd);

        return $next($request);

    }

    
}
