<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    // 목록
    public function index()
    {
        $managers = DB::table('manager')->orderBy('id', 'desc')->get();
        return view('admin.company.manager.index', compact('managers'));
    }

    // 등록 폼
    public function create()
    {
        return view('admin.company.manager.create');
    }

    // 등록 저장
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:manager,email',
            'hp'       => 'required|max:100',
            'password' => 'required|min:8|confirmed',
            'is_admin' => 'required|in:0,1',
        ], [
            'name.required'      => '이름을 입력해주세요.',
            'email.required'     => '이메일을 입력해주세요.',
            'email.unique'       => '이미 등록된 이메일입니다.',
            'hp.required'        => '연락처를 입력해주세요.',
            'password.required'  => '비밀번호를 입력해주세요.',
            'password.min'       => '비밀번호는 8자 이상이어야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'is_admin.required'  => '권한을 선택해주세요.',
        ]);

        DB::table('manager')->insert([
            'name'       => $request->name,
            'rank'       => $request->rank,       // ← 추가
            'email'      => $request->email,
            'hp'         => $request->hp,
            'password'   => Hash::make($request->password),
            'is_admin'   => $request->is_admin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.manager.index')
                         ->with('success', '관리자가 등록되었습니다.');
    }

    // 수정 폼
    public function edit($id)
    {
        $manager = DB::table('manager')->where('id', $id)->first();
        if (!$manager) {
            return redirect()->route('admin.manager.index')
                             ->with('error', '존재하지 않는 관리자입니다.');
        }
        return view('admin.company.manager.edit', compact('manager'));
    }

    // 수정 저장
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:manager,email,' . $id,
            'hp'       => 'required|max:100',
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'required|in:0,1',
        ], [
            'name.required'      => '이름을 입력해주세요.',
            'email.required'     => '이메일을 입력해주세요.',
            'email.unique'       => '이미 등록된 이메일입니다.',
            'hp.required'        => '연락처를 입력해주세요.',
            'password.min'       => '비밀번호는 8자 이상이어야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        ]);

        $data = [
            'name'       => $request->name,
            'rank'       => $request->rank,       // ← 추가
            'email'      => $request->email,
            'hp'         => $request->hp,
            'is_admin'   => $request->is_admin,
            'updated_at' => now(),
        ];

        // 비밀번호 입력시에만 변경
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('manager')->where('id', $id)->update($data);

        return redirect()->route('admin.manager.index')
                         ->with('success', '관리자 정보가 수정되었습니다.');
    }

    // 삭제
    public function destroy($id)
    {
        $manager = DB::table('manager')->where('id', $id)->first();

        if ($manager->is_admin == 1) {
            return redirect()->route('admin.manager.index')
                             ->with('error', '마스터 계정은 삭제할 수 없습니다.');
        }

        DB::table('manager')->where('id', $id)->delete();
        DB::table('auth_permission')->where('manager_id', $id)->delete();

        return redirect()->route('admin.manager.index')
                         ->with('success', '관리자가 삭제되었습니다.');
    }
}