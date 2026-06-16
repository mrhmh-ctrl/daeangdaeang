<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\Manager::where('email', $request->email)
                    ->where('is_admin', 1)
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'admin_logged_in' => true,
                'admin_id'        => $user->id,
                'admin_name'      => $user->name,
            ]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => '이메일 또는 비밀번호가 올바르지 않습니다.',
        ])->withInput();
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id', 'admin_name']);
        return redirect()->route('admin.login');
    }
}
