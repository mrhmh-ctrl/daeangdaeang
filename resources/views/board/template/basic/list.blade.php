{!! $board_header !!}
<link rel="stylesheet" href="{{ asset('css/board/basic.css') }}">

<div id="board-wrap">
<div class="board-inner">

    {{-- 게시판 타이틀 --}}
    <div class="board-title">
        <h2>{{ $board->board_name }}</h2>
    </div>

    {{-- 카테고리 --}}
    @if($categories->count() > 0)
    <div class="board-category">
        <a href="{{ route('board.list', $board->board_id) }}" class="{{ !request('category_id') ? 'active' : '' }}">전체</a>
        @foreach($categories as $cat)
            <a href="{{ route('board.list', [$board->board_id, 'category_id' => $cat->id]) }}"
               class="{{ request('category_id') == $cat->id ? 'active' : '' }}">{{ $cat->category_name }}</a>
        @endforeach
    </div>
    @endif

    {{-- 알림 --}}
    @if(session('success'))
        <div class="board-alert success">{{ session('success') }}</div>
    @endif

    {{-- 게시글 목록 --}}
    <table class="board-table">
        <thead>
            <tr>
                @if($board->use_num === 'Y') <th class="num">번호</th> @endif
                @if($board->use_category === 'Y') <th class="category">카테고리</th> @endif
                @if($board->use_title === 'Y') <th class="title">제목</th> @endif
                @if($board->use_writer === 'Y') <th class="writer">작성자</th> @endif
                @if($board->use_date === 'Y') <th class="date">날짜</th> @endif
                @if($board->use_view_count === 'Y') <th class="hit">조회</th> @endif
                @if($board->use_recommend_col === 'Y') <th class="recommend">추천</th> @endif
            </tr>
        </thead>
        <tbody>
            {{-- 공지 --}}
            @foreach($notices as $notice)
            <tr class="notice-row">
                @if($board->use_num === 'Y') <td class="num"><span class="notice-badge">공지</span></td> @endif
                @if($board->use_category === 'Y') <td class="category">{{ $notice->category->category_name ?? '' }}</td> @endif
                @if($board->use_title === 'Y')
                <td class="title">
                    <a href="{{ route('board.view', [$board->board_id, $notice->id]) }}">
                        {{ $notice->title }}
                        @if($notice->comment_count > 0) <span class="comment-count">[{{ $notice->comment_count }}]</span> @endif
                        @if($notice->file_count > 0) <span class="file-icon">📎</span> @endif
                    </a>
                </td>
                @endif
                @if($board->use_writer === 'Y') <td class="writer">{{ $notice->writer_name }}</td> @endif
                @if($board->use_date === 'Y') <td class="date">{{ $notice->created_at->format('Y-m-d') }}</td> @endif
                @if($board->use_view_count === 'Y') <td class="hit">{{ number_format($notice->view_count) }}</td> @endif
                @if($board->use_recommend_col === 'Y') <td class="recommend">{{ $notice->recommend_count }}</td> @endif
            </tr>
            @endforeach

            {{-- 일반 게시글 --}}
            @forelse($posts as $post)
            <tr>
                @if($board->use_num === 'Y') <td class="num">{{ $posts->total() - ($posts->currentPage() - 1) * $posts->perPage() - $loop->index }}</td> @endif
                @if($board->use_category === 'Y') <td class="category">{{ $post->category->category_name ?? '' }}</td> @endif
                @if($board->use_title === 'Y')
                <td class="title">
                    <a href="{{ route('board.view', [$board->board_id, $post->id]) }}">
                        @if($post->is_secret === 'Y') 🔒 @endif
                        {{ $post->title }}
                        @if($post->comment_count > 0) <span class="comment-count">[{{ $post->comment_count }}]</span> @endif
                        @if($post->file_count > 0) <span class="file-icon">📎</span> @endif
                    </a>
                </td>
                @endif
                @if($board->use_writer === 'Y') <td class="writer">{{ $post->writer_name }}</td> @endif
                @if($board->use_date === 'Y')
                <td class="date">
                    @if($post->created_at->isToday())
                        {{ $post->created_at->format('H:i') }}
                    @else
                        {{ $post->created_at->format('Y-m-d') }}
                    @endif
                </td>
                @endif
                @if($board->use_view_count === 'Y') <td class="hit">{{ number_format($post->view_count) }}</td> @endif
                @if($board->use_recommend_col === 'Y') <td class="recommend">{{ $post->recommend_count }}</td> @endif
            </tr>
            @empty
            <tr>
                <td colspan="10" class="no-data">등록된 게시글이 없습니다.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 페이지네이션 --}}
    @if($posts->hasPages())
    <div class="board-pagination">
        {{ $posts->appends(request()->query())->links() }}
    </div>
    @endif

    {{-- 검색 --}}
    <div class="board-search">
        <form action="{{ route('board.list', $board->board_id) }}" method="GET">
            @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif
            <select name="sfl">
                <option value="title" {{ request('sfl') == 'title' ? 'selected' : '' }}>제목</option>
                <option value="content" {{ request('sfl') == 'content' ? 'selected' : '' }}>내용</option>
                <option value="title_content" {{ request('sfl') == 'title_content' ? 'selected' : '' }}>제목+내용</option>
                <option value="writer_name" {{ request('sfl') == 'writer_name' ? 'selected' : '' }}>작성자</option>
            </select>
            <input type="text" name="sword" value="{{ request('sword') }}" placeholder="검색어 입력">
            <button type="submit">검색</button>
        </form>
    </div>

    {{-- 글쓰기 버튼 --}}
    <div class="board-buttons">
        @if(session('member_id'))
            <a href="{{ route('board.post', $board->board_id) }}" class="btn-write">글쓰기</a>
        @endif
    </div>

</div>
</div>

{!! $board_footer !!}