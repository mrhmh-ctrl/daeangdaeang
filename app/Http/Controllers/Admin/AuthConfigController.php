<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthConfigController extends Controller
{
    public function index()
    {
        $menus       = DB::table('auth_config')->orderBy('sort_order')->get();
        $managers    = DB::table('manager')->where('is_admin', 0)->get();  // is_admin 0 = 일반관리자
        $permissions = DB::table('auth_permission')->get();

        // manager_id => menu_key => auth_level 구조로 변환
        $permMap = [];
        foreach ($permissions as $perm) {
            $permMap[$perm->manager_id][$perm->menu_key] = $perm->auth_level;
        }

        return view('admin.auth_config', compact('menus', 'managers', 'permMap'));
    }

    public function update(Request $request)
{
    $manager_id = $request->manager_id;  // 선택된 관리자만 저장
    $menus      = DB::table('auth_config')->get();

    foreach ($menus as $menu) {
        $key        = 'auth_' . $manager_id . '_' . $menu->menu_key;
        $auth_level = $request->input($key, 0);

        DB::table('auth_permission')->updateOrInsert(
            ['manager_id' => $manager_id, 'menu_key' => $menu->menu_key],
            ['auth_level' => $auth_level, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    return redirect()->route('admin.auth_config.index')
                     ->with('success', '권한 설정이 저장되었습니다.');
    }
}