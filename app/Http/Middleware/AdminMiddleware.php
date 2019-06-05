<?php
namespace App\Http\Middleware;

use App\Libs\Util;
use App\Models\Model;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;


class AdminMiddleware extends Middleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $loginToken = $request->cookie(LOGINTOKEN);
        $loginData = explode('#', $loginToken);
        if (empty($loginData)) return redirect('/admin/login');
        $uid = end($loginData);
        $user = Model::User()->getUserById($uid);
        if (empty($user)) return redirect('/admin/login');
        $checkoutToken = Util::getLoginToken($request->getClientIp(), $user);
        if ($checkoutToken !== $loginToken) return redirect('/admin/login');
        /**
        $GLOBALS['uid'] = $uid;
        $insertData = [];
        $input = $request->all();
        $insertData['uid'] = $uid;
        $insertData['path']= $request->path();
        $insertData['method'] = $request->method();
        $insertData['ip'] = $request->ip();
        $insertData['create_time'] = time();
        $insertData['params'] = json_encode($input, JSON_UNESCAPED_UNICODE);
        Model::Log()->insertAdminLog($insertData);
         ***/
        //记录log
        return $next($request);
    }
}
