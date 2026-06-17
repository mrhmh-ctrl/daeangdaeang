<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 로그인 인증용
use App\Models\User;

class AdminController extends Controller
{
    /**
     * 관리자 로그인 폼 (GET /admin/login)
     */
    public function loginForm()
    {
        // 이미 로그인한 상태면 관리자 메인으로 보냄
        if (Auth::check()) {
            return redirect('/admin');
        }
        return view('admin.login'); // resources/views/admin/login.blade.php
    }

    /**
     * 로그인 처리 (POST /admin/login)
     */
    public function loginSubmit(Request $request)
    {
        // 폼에서 전송된 아이디(이메일)와 비번 가져오기
        $credentials = $request->only('email', 'password');

        // 로그인 시도
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // 세션 고정 공격 방지
            return redirect()->intended('/admin'); // 가려던 페이지로 이동
        }

        // 로그인 실패 시 메시지와 함께 뒤로가기
        return back()->withErrors([
            'email' => '아이디 또는 비밀번호가 일치하지 않습니다.',
        ]);
    }

    /**
     * 관리자 메인 페이지 (GET /admin)
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * 회원 관리 리스트 (GET /admin/member_list)
     */
    public function memberList()
    {
        $members = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.member_list', compact('members'));
    }

    /**
     * 로그아웃 처리
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
