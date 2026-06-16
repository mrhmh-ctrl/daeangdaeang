<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = DB::table('config')->get()->keyBy('config_key');
        return view('admin.config', compact('configs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'   => 'required|max:100',
            'admin_email' => 'required|email',
            'login_type'  => 'required|in:id,email',
        ], [
            'site_name.required'   => '사이트명을 입력해주세요.',
            'site_name.max'        => '사이트명은 100자 이내로 입력해주세요.',
            'admin_email.required' => '관리자 이메일을 입력해주세요.',
            'admin_email.email'    => '올바른 이메일 형식으로 입력해주세요.',
            'login_type.required'  => '로그인 방식을 선택해주세요.',
        ]);

        $data = [
            'site_name'   => $request->site_name,
            'admin_email' => $request->admin_email,
            'login_type'  => $request->login_type,
        ];

        foreach ($data as $key => $value) {
            DB::table('config')->where('config_key', $key)->update(['config_value' => $value]);
        }

        return redirect()->route('admin.config.index')->with('success', '환경설정이 저장되었습니다.');
    }
}
