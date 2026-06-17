<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('member_pets')
            ->join('member_list', 'member_pets.member_id', '=', 'member_list.id')
            ->select(
                'member_pets.*',
                'member_list.name as member_name',
                'member_list.nick as member_nick',
                'member_list.user_id as member_user_id'
            )
            ->orderBy('member_pets.id', 'desc');

        // 검색
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('member_pets.name', 'like', "%{$keyword}%")
                  ->orWhere('member_pets.breed', 'like', "%{$keyword}%")
                  ->orWhere('member_list.nick', 'like', "%{$keyword}%")
                  ->orWhere('member_list.name', 'like', "%{$keyword}%");
            });
        }

        $pets  = $query->paginate(20);
        $total = $query->count();

        return view('admin.member.petlist', compact('pets', 'total'));
    }
}