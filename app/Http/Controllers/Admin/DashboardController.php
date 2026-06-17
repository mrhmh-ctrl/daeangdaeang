<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 방문자 통계 (sessions 기반)
        $today     = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $visit_today     = DB::table('sessions')->whereDate('last_activity', '>=', strtotime($today))->count();
        $visit_yesterday = DB::table('sessions')->whereBetween('last_activity', [strtotime($yesterday), strtotime($today)])->count();
        $visit_max       = max($visit_today, $visit_yesterday);

        // 최근 7일 방문자 (그래프용)
        $visit_chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i)->toDateString();
            $start = strtotime($date);
            $end   = strtotime($date . ' 23:59:59');
            $visit_chart[] = [
                'date'  => now()->subDays($i)->format('m/d'),
                'count' => DB::table('sessions')->whereBetween('last_activity', [$start, $end])->count(),
            ];
        }

        // 최근 등록 게시물
        $recent_posts = DB::table('posts')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 최근 가입 회원
        $recent_members = DB::table('member_list')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 전체 통계
        $total_posts   = DB::table('posts')->count();
        $total_members = DB::table('member_list')->count();
        $total_boards  = DB::table('boards')->count();
        $total_dogs    = DB::table('dog_dicts')->count();

        // 서버 상태 - CPU
        $cpu_usage = 0;
        if (PHP_OS_FAMILY === 'Linux') {
            $load      = sys_getloadavg();
            $cpu_cores = (int) shell_exec('nproc');
            $cpu_usage = $cpu_cores > 0 ? round(($load[0] / $cpu_cores) * 100, 1) : 0;
            $cpu_usage = min($cpu_usage, 100);
        }

        // 서버 상태 - RAM
        $ram_total = $ram_used = $ram_usage = 0;
        if (PHP_OS_FAMILY === 'Linux') {
            $meminfo = file_get_contents('/proc/meminfo');
            preg_match('/MemTotal:\s+(\d+)/', $meminfo, $m_total);
            preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $m_avail);
            $ram_total = isset($m_total[1]) ? round($m_total[1] / 1024 / 1024, 1) : 0;
            $ram_avail = isset($m_avail[1]) ? round($m_avail[1] / 1024 / 1024, 1) : 0;
            $ram_used  = round($ram_total - $ram_avail, 1);
            $ram_usage = $ram_total > 0 ? round(($ram_used / $ram_total) * 100, 1) : 0;
        }

        // 서버 상태 - HDD (/home/daengdaeng 기준)
        $hdd_total = $hdd_used = $hdd_usage = 0;
        if (PHP_OS_FAMILY === 'Linux') {
            $hdd_total = round(disk_total_space('/home/web/daengdaeng') / 1024 / 1024 / 1024, 1);
            $hdd_free  = round(disk_free_space('/home/web/daengdaeng') / 1024 / 1024 / 1024, 1);
            $hdd_used  = round($hdd_total - $hdd_free, 1);
            $hdd_usage = $hdd_total > 0 ? round(($hdd_used / $hdd_total) * 100, 1) : 0;
        }

        // 서버 상태 - MySQL (daengdaeng DB 사용량)
        $mysql_size  = DB::select("
            SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables
            WHERE table_schema = 'daengdaeng'
        ");
        $mysql_usage = $mysql_size[0]->size_mb ?? 0;

        return view('admin.index', compact(
            'visit_today', 'visit_yesterday', 'visit_max', 'visit_chart',
            'recent_posts', 'recent_members',
            'total_posts', 'total_members', 'total_boards', 'total_dogs',
            'cpu_usage', 'ram_total', 'ram_used', 'ram_usage',
            'hdd_total', 'hdd_used', 'hdd_usage',
            'mysql_usage'
        ));
    }
}