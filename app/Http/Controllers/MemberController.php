<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    private function getLoginType()
    {
        return DB::table('config')->where('config_key', 'login_type')->value('config_value') ?? 'id';
    }

    // 로그인 폼
    public function loginForm()
    {
        if (Session::has('member_id')) {
            return redirect('/');
        }
        $login_type = $this->getLoginType();
        return view('member.login', compact('login_type'));
    }

    // 로그인 처리
    public function login(Request $request)
    {
        $login_type = $this->getLoginType();

        if ($login_type === 'id') {
            $request->validate([
                'user_id'  => 'required',
                'password' => 'required',
            ], [
                'user_id.required'  => '아이디를 입력해주세요.',
                'password.required' => '비밀번호를 입력해주세요.',
            ]);
            $member = Member::where('user_id', $request->user_id)->first();
        } else {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ], [
                'email.required'    => '이메일을 입력해주세요.',
                'password.required' => '비밀번호를 입력해주세요.',
            ]);
            $member = Member::where('email', $request->email)->first();
        }

        if (!$member || !Hash::check($request->password, $member->password)) {
            return back()->withErrors(['login' => '아이디/비밀번호가 올바르지 않습니다.'])->withInput();
        }

        if ($member->status === 'N') {
            return back()->withErrors(['login' => '탈퇴한 회원입니다.']);
        }
        if ($member->status === 'B') {
            return back()->withErrors(['login' => '정지된 회원입니다. 관리자에게 문의하세요.']);
        }

        // 로그인 정보 업데이트
        $member->update([
            'login_ip' => $request->ip(),
            'login_at' => now(),
        ]);

        // 세션 저장
        Session::put('member_id', $member->id);
        Session::put('member_nick', $member->nick);
        Session::put('member_level', $member->level);

        return redirect('/');
    }

    // 로그아웃
    public function logout()
    {
        Session::forget(['member_id', 'member_nick', 'member_level']);
        return redirect('/login');
    }

    // 회원가입 약관
    public function register()
    {
        return view('member.register');
    }

    // 회원가입 폼 (기본정보)
    public function joinForm()
    {
        $login_type = $this->getLoginType();
        return view('member.register_joinform', compact('login_type'));
    }

    // 회원가입 폼 (추가정보)
    public function additionalForm()
    {
        // 세션에 기본정보 없으면 다시
        if (!Session::has('register_data')) {
            return redirect('/register_joinform');
        }
        return view('member.register_additionalform');
    }

    // 기본정보 세션 저장 후 추가정보로
    public function joinFormSubmit(Request $request)
    {
        $login_type = $this->getLoginType();

        $rules = [
            'email'    => 'required|email|unique:member_list,email',
            'password' => 'required|min:8|confirmed',
            'birth'    => 'required|date',
            'name'     => 'required|max:30',
            'hp'       => 'required|max:20',
        ];

        if ($login_type === 'id') {
            $rules['user_id'] = 'required|unique:member_list,user_id|max:20';
        }

        $request->validate($rules, [
            'user_id.required'   => '아이디를 입력해주세요.',
            'user_id.unique'     => '이미 사용중인 아이디입니다.',
            'email.required'     => '이메일을 입력해주세요.',
            'email.unique'       => '이미 사용중인 이메일입니다.',
            'password.required'  => '비밀번호를 입력해주세요.',
            'password.min'       => '비밀번호는 8자 이상 입력해주세요.',
            'password.confirmed' => '비밀번호가 일치하지 않습니다.',
            'birth.required'     => '생년월일을 입력해주세요.',
            'name.required'      => '이름을 입력해주세요.',
            'hp.required'        => '휴대폰번호를 입력해주세요.',
        ]);

        // 세션에 임시 저장
        Session::put('register_data', [
            'user_id'  => $request->user_id,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'birth'    => $request->birth,
            'name'     => $request->name,
            'hp'       => $request->hp,
        ]);

        return redirect('/register_additionalform');
    }

    // 회원가입 최종 완료
    public function store(Request $request)
    {
        $request->validate([
            'nick' => 'required|unique:member_list,nick|max:30',
        ], [
            'nick.required' => '닉네임을 입력해주세요.',
            'nick.unique'   => '이미 사용중인 닉네임입니다.',
        ]);

        $data = Session::get('register_data');
        if (!$data) {
            return redirect('/register_joinform');
        }

        // 회원 생성
        $member = Member::create([
            'user_id'  => $data['user_id'] ?? null,
            'email'    => $data['email'],
            'password' => $data['password'],
            'birth'    => $data['birth'],
            'name'     => $data['name'],
            'hp'       => $data['hp'],
            'nick'     => $request->nick,
            'zip'      => $request->zip ?? null,
            'addr1'    => $request->addr1 ?? null,
            'addr2'    => $request->addr2 ?? null,
            'level'    => 1,
            'status'   => 'Y',
        ]);

        // 반려견 정보 저장
        $dog_count = (int)($request->dog_count ?? 0);
        for ($i = 1; $i <= $dog_count; $i++) {
            if ($request->filled("dog_name_{$i}") || $request->filled("dog_breed_{$i}")) {
                MemberPet::create([
                    'member_id'  => $member->id,
                    'breed'      => $request->input("dog_breed_{$i}"),
                    'name'       => $request->input("dog_name_{$i}"),
                    'gender'     => $request->input("dog_gender_{$i}"),
                    'birth'      => $request->input("dog_birth_{$i}") ?: null,
                    'sort_order' => $i,
                ]);
            }
        }
        // 175번째 줄
        Session::forget('register_data');
        // ↓ 아래 3줄 추가
        Session::flash('registered_email',      $data['email']);
        Session::flash('registered_user_id',    $data['user_id'] ?? '');
        Session::flash('registered_login_type', $this->getLoginType());

        return redirect('/register_complete');
    }
}
