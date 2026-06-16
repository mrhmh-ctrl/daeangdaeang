<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // 회원 데이터를 가져오기 위해 필요

class AdminController extends Controller
{
    /**
     * 관리자 메인 페이지 
     */
    public function index()
    {
        // 뷰 파일: resources/views/admin/index.blade.php
        return view('admin.index');
    }	
    public function loginForm()
    {
        return view('admin.login');
    }
    /**
     * 회원 관리 리스트 
     */
    public function memberList()
    {
        // 회원 정보를 최신순으로 15개씩 페이징
        $members = User::orderBy('created_at', 'desc')->paginate(15);
        
        // 뷰 파일: resources/views/admin/member_list.blade.php
        return view('admin.member_list', compact('members'));
    }
}
?>