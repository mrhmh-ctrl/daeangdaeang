<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostFile;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    private function getBoard($board_id)
    {
        return Board::where('board_id', $board_id)->where('status', 'Y')->firstOrFail();
    }

    private function skinView($board, $template)
    {
        $skin      = $board->skin ?: 'basic';
        $header    = $board->header_inc ? view('inc.' . $board->header_inc)->render() : '';
        $footer    = $board->footer_inc ? view('inc.' . $board->footer_inc)->render() : '';
        $css_path  = resource_path('views/board/template/' . $skin . '/css/style.css');
        $board_css = file_exists($css_path) ? file_get_contents($css_path) : '';
    
        view()->share('board_header', $header);
        view()->share('board_footer', $footer);
        view()->share('board_css', $board_css);
    
        return "board.template.{$skin}.{$template}";
    }
    public function list(Request $request, $board_id)
    {
        $board = $this->getBoard($board_id);
        $query = Post::where('board_id', $board->id)->where('status', 'Y')->where('is_notice', 'N');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('sword')) {
            $sfl  = $request->sfl ?? 'title';
            $word = '%' . $request->sword . '%';
            if ($sfl === 'title_content') {
                $query->where(function($q) use ($word) {
                    $q->where('title', 'like', $word)->orWhere('content', 'like', $word);
                });
            } elseif (in_array($sfl, ['title', 'content', 'writer_name'])) {
                $query->where($sfl, 'like', $word);
            }
        }

        $notices = Post::where('board_id', $board->id)
            ->where('status', 'Y')
            ->where('is_notice', 'Y')
            ->orderBy('created_at', 'desc')
            ->get();

        $posts      = $query->orderBy('created_at', 'desc')->paginate($board->list_count ?? 15);
        $categories = $board->use_category === 'Y' ? $board->categories()->active()->orderBy('sort_order')->get() : collect();

        return view($this->skinView($board, 'list'), compact('board', 'posts', 'notices', 'categories'));
    }

    public function view(Request $request, $board_id, $id)
    {
        $board = $this->getBoard($board_id);
        $post  = Post::where('board_id', $board->id)->where('id', $id)->where('status', 'Y')->firstOrFail();

        $post->increment('view_count');

        $files    = $post->files()->orderBy('sort_order')->get();
        $comments = $post->comments()->where('status', 'Y')->with('member')->orderBy('created_at')->get();
        $prev     = Post::where('board_id', $board->id)->where('status', 'Y')->where('id', '<', $id)->orderBy('id', 'desc')->first();
        $next     = Post::where('board_id', $board->id)->where('status', 'Y')->where('id', '>', $id)->orderBy('id', 'asc')->first();

        return view($this->skinView($board, 'view'), compact('board', 'post', 'files', 'comments', 'prev', 'next'));
    }

    public function postForm(Request $request, $board_id)
    {
        $board      = $this->getBoard($board_id);
        $categories = $board->use_category === 'Y' ? $board->categories()->active()->orderBy('sort_order')->get() : collect();
        return view($this->skinView($board, 'post'), compact('board', 'categories'));
    }

    public function editForm(Request $request, $board_id, $id)
    {
        $board      = $this->getBoard($board_id);
        $post       = Post::where('board_id', $board->id)->where('id', $id)->firstOrFail();
        $categories = $board->use_category === 'Y' ? $board->categories()->active()->orderBy('sort_order')->get() : collect();
        return view($this->skinView($board, 'post'), compact('board', 'post', 'categories'));
    }

    public function store(Request $request, $board_id)
    {
        $board = $this->getBoard($board_id);

        $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
        ], [
            'title.required'   => '제목을 입력해주세요.',
            'content.required' => '내용을 입력해주세요.',
        ]);

        $member = session('member_id') ? \App\Models\Member::find(session('member_id')) : null;

        $post = Post::create([
            'board_id'    => $board->id,
            'member_id'   => $member?->id,
            'category_id' => $request->category_id ?? null,
            'title'       => $request->title,
            'content'     => $request->content,
            'writer_name' => $member?->nick ?? '익명',
            'writer_ip'   => $request->ip(),
            'is_notice'   => 'N',
            'is_secret'   => $request->is_secret ?? 'N',
            'status'      => 'Y',
        ]);

        return redirect()->route('board.view', [$board_id, $post->id])->with('success', '게시글이 등록되었습니다.');
    }

    public function update(Request $request, $board_id, $id)
    {
        $board = $this->getBoard($board_id);
        $post  = Post::findOrFail($id);

        $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update([
            'category_id' => $request->category_id ?? null,
            'title'       => $request->title,
            'content'     => $request->content,
            'is_secret'   => $request->is_secret ?? 'N',
        ]);

        return redirect()->route('board.view', [$board_id, $post->id])->with('success', '게시글이 수정되었습니다.');
    }

    public function destroy(Request $request, $board_id, $id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('board.list', $board_id)->with('success', '게시글이 삭제되었습니다.');
    }

    public function commentStore(Request $request, $board_id, $post_id)
    {
        $request->validate([
            'content' => 'required|max:1000',
        ], [
            'content.required' => '댓글 내용을 입력해주세요.',
        ]);

        $member = session('member_id') ? \App\Models\Member::find(session('member_id')) : null;

        Comment::create([
            'post_id'     => $post_id,
            'member_id'   => $member?->id,
            'parent_id'   => $request->parent_id ?? null,
            'depth'       => $request->parent_id ? 1 : 0,
            'content'     => $request->content,
            'writer_name' => $member?->nick ?? '익명',
            'writer_ip'   => $request->ip(),
            'is_secret'   => $request->is_secret ?? 'N',
            'status'      => 'Y',
        ]);

        Post::find($post_id)->increment('comment_count');

        return redirect()->route('board.view', [$board_id, $post_id])->with('success', '댓글이 등록되었습니다.');
    }

    public function commentDestroy(Request $request, $board_id, $post_id, $comment_id)
    {
        Comment::findOrFail($comment_id)->delete();
        Post::find($post_id)->decrement('comment_count');
        return redirect()->route('board.view', [$board_id, $post_id])->with('success', '댓글이 삭제되었습니다.');
    }
}
