<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    private function getSkins()
    {
        $path = resource_path('views/board/template');
        if (!is_dir($path)) return [];
        return array_values(array_filter(scandir($path), function($d) use ($path) {
            return $d !== '.' && $d !== '..' && is_dir($path . '/' . $d);
        }));
    }

    private function getIncFiles()
    {
        $path = resource_path('views/inc');
        if (!is_dir($path)) return [];
        $files = array_filter(scandir($path), function($f) use ($path) {
            return is_file($path . '/' . $f) && str_ends_with($f, '.blade.php');
        });
        return array_values(array_map(fn($f) => str_replace('.blade.php', '', $f), $files));
    }

    public function index(Request $request)
    {
        $query = Board::withCount('posts');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search_keyword')) {
            $field = in_array($request->search_field, ['board_name', 'board_id'])
                ? $request->search_field : 'board_name';
            $query->where($field, 'like', '%' . $request->search_keyword . '%');
        }

        $boards = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.board.boardlist', compact('boards'));
    }

    public function create()
    {
        $skins = $this->getSkins();
        $incs  = $this->getIncFiles();
        return view('admin.board.boardform', compact('skins', 'incs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'board_id'   => 'required|unique:boards,board_id|alpha_num|max:50',
            'board_name' => 'required|max:100',
        ], [
            'board_id.required'   => '게시판 ID를 입력해주세요.',
            'board_id.unique'     => '이미 사용중인 게시판 ID입니다.',
            'board_id.alpha_num'  => '게시판 ID는 영문+숫자만 가능합니다.',
            'board_name.required' => '게시판명을 입력해주세요.',
        ]);

        Board::create($request->all());

        return redirect()->route('admin.board.index')->with('success', '게시판이 등록되었습니다.');
    }

    public function edit($id)
    {
        $board = Board::findOrFail($id);
        $skins = $this->getSkins();
        $incs  = $this->getIncFiles();
        return view('admin.board.boardform', compact('board', 'skins', 'incs'));
    }

    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        $request->validate([
            'board_id'   => 'required|alpha_num|max:50|unique:boards,board_id,' . $id,
            'board_name' => 'required|max:100',
        ]);

        $board->update($request->all());

        return redirect()->route('admin.board.index')->with('success', '게시판이 수정되었습니다.');
    }

    public function destroy($id)
    {
        Board::findOrFail($id)->delete();
        return redirect()->route('admin.board.index')->with('success', '게시판이 삭제되었습니다.');
    }

    public function toggleStatus($id)
    {
        $board = Board::findOrFail($id);
        $board->update(['status' => $board->status === 'Y' ? 'N' : 'Y']);
        return redirect()->back()->with('success', '상태가 변경되었습니다.');
    }

    public function bulk(Request $request)
    {
        $ids    = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) {
            return redirect()->back()->with('error', '선택된 게시판이 없습니다.');
        }

        match($action) {
            'use'    => Board::whereIn('id', $ids)->update(['status' => 'Y']),
            'unuse'  => Board::whereIn('id', $ids)->update(['status' => 'N']),
            'delete' => Board::whereIn('id', $ids)->delete(),
            default  => null,
        };

        return redirect()->back()->with('success', '일괄 처리가 완료되었습니다.');
    }
}
