<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // 관리자 세션 유지시간 체크
        $session_time = DB::table('config')->where('config_key', 'login_session_time_admin')->value('config_value') ?? 7200;

        $last_activity = session('admin_last_activity');
        if ($last_activity && (time() - $last_activity > $session_time)) {
            session()->forget(['admin_logged_in', 'admin_last_activity']);
            return redirect()->route('admin.login')->with('error', '세션이 만료되었습니다. 다시 로그인해주세요.');
        }

        session(['admin_last_activity' => time()]);

        return $next($request);
    }
}