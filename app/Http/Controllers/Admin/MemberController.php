<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    private function getLoginType()
    {
        return DB::table('config')->where('config_key', 'login_type')->value('config_value') ?? 'id';
    }

    // 회원 목록
    public function index(Request $request)
    {
        $query = Member::withCount('posts');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('search_keyword')) {
            $field = in_array($request->search_field, ['user_id', 'name', 'nick', 'email', 'hp'])
                ? $request->search_field : 'user_id';
            $query->where($field, 'like', '%' . $request->search_keyword . '%');
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);
        $login_type = $this->getLoginType();
        
        return view('admin.member.member_list', compact('members', 'login_type'));
    }

    // 회원 등록 폼
    public function create()
    {
        $login_type = $this->getLoginType();
        return view('admin.member.member_form', compact('login_type'));
    }

    // 회원 등록 처리
    public function store(Request $request)
    {
        $login_type = $this->getLoginType();

        $rules = [
            'name'     => 'required|max:30',
            'nick'     => 'required|unique:member_list,nick|max:30',
            'email'    => 'required|email|unique:member_list,email',
            'password' => 'required|min:4',
        ];

        if ($login_type === 'id') {
            $rules['user_id'] = 'required|unique:member_list,user_id|max:20';
        }

        $request->validate($rules, [
            'user_id.required'  => '아이디를 입력해주세요.',
            'user_id.unique'    => '이미 사용중인 아이디입니다.',
            'name.required'     => '이름을 입력해주세요.',
            'nick.required'     => '닉네임을 입력해주세요.',
            'nick.unique'       => '이미 사용중인 닉네임입니다.',
            'email.required'    => '이메일을 입력해주세요.',
            'email.unique'      => '이미 사용중인 이메일입니다.',
            'password.required' => '비밀번호를 입력해주세요.',
            'password.min'      => '비밀번호는 4자 이상 입력해주세요.',
        ]);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);

        if ($login_type === 'email') {
            $data['user_id'] = null;
        }

        Member::create($data);

        return redirect()->route('admin.member.index')->with('success', '회원이 등록되었습니다.');
    }

    // 회원 수정 폼
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $login_type = $this->getLoginType();
        return view('admin.member.member_form', compact('member', 'login_type'));
    }

    // 회원 수정 처리
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $login_type = $this->getLoginType();

        $rules = [
            'name'  => 'required|max:30',
            'nick'  => 'required|max:30|unique:member_list,nick,' . $id,
            'email' => 'required|email|unique:member_list,email,' . $id,
        ];

        $request->validate($rules, [
            'name.required'  => '이름을 입력해주세요.',
            'nick.required'  => '닉네임을 입력해주세요.',
            'nick.unique'    => '이미 사용중인 닉네임입니다.',
            'email.required' => '이메일을 입력해주세요.',
            'email.unique'   => '이미 사용중인 이메일입니다.',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $member->update($data);

        return redirect()->route('admin.member.index')->with('success', '회원정보가 수정되었습니다.');
    }

    // 회원 삭제
    public function destroy($id)
    {
        Member::findOrFail($id)->delete();
        return redirect()->route('admin.member.index')->with('success', '회원이 삭제되었습니다.');
    }

    // 상태 토글
    public function toggleStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->update(['status' => $member->status === 'Y' ? 'N' : 'Y']);
        return redirect()->back()->with('success', '상태가 변경되었습니다.');
    }

    // 일괄 처리
    public function bulk(Request $request)
    {
        $ids    = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) {
            return redirect()->back()->with('error', '선택된 회원이 없습니다.');
        }

        match($action) {
            'level_up'   => Member::whereIn('id', $ids)->increment('level'),
            'level_down' => Member::whereIn('id', $ids)->decrement('level'),
            'block'      => Member::whereIn('id', $ids)->update(['status' => 'B']),
            'unblock'    => Member::whereIn('id', $ids)->update(['status' => 'Y']),
            'delete'     => Member::whereIn('id', $ids)->delete(),
            default      => null,
        };

        return redirect()->back()->with('success', '일괄 처리가 완료되었습니다.');
    }
}
