@include('admin.inc.admin_header')
                <div class="content-header">
                    <h2>게시판 관리</h2>
                    <div class="top-dict-buttons">
                        <a href="{{ route('admin.board.create') }}" class="btn-add"><li>+ 게시판 등록</li></a>
                    </div>
                </div>
                <div class="admin-contents">

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                <div class="top-function">
                    <div class="total-count">전체 게시판 : {{ $boards->total() }}개</div>
                    <div class="search">
                        <form method="GET" action="{{ route('admin.board.index') }}">
                            <select name="status" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                                <option value="">전체</option>
                                <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>사용</option>
                                <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>미사용</option>
                            </select>
                            <select name="search_field" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                                <option value="board_name" {{ request('search_field') == 'board_name' ? 'selected' : '' }}>게시판명</option>
                                <option value="board_id"   {{ request('search_field') == 'board_id'   ? 'selected' : '' }}>게시판ID</option>
                            </select>
                            <input type="text" name="search_keyword" value="{{ request('search_keyword') }}" placeholder="검색어 입력">
                            <button type="submit">검색</button>
                        </form>
                    </div>
                </div>

                <form id="bulk_form" action="{{ route('admin.board.bulk') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" id="bulk_action_value">
                    <div style="display:flex; align-items:center; gap:5px; margin-bottom:8px;">
                        <select id="bulk_action" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                            <option value="">일괄 처리</option>
                            <option value="use">사용으로 변경</option>
                            <option value="unuse">미사용으로 변경</option>
                            <option value="delete">선택 삭제</option>
                        </select>
                        <button type="button" onclick="doBulkAction()" style="height:34px; padding:0 16px; background:#444; color:#fff; border:0; border-radius:4px; font-size:13px; cursor:pointer;">적용</button>
                        <span id="checked_count" style="font-size:12px; color:#666;">0개 선택됨</span>
                    </div>

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>No</th>
                                <th>게시판명</th>
                                <th>게시판ID</th>
                                <th>유형</th>
                                <th>게시글수</th>
                                <th>목록권한</th>
                                <th>쓰기권한</th>
                                <th>댓글</th>
                                <th>파일</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boards as $board)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $board->id }}" class="board-check"></td>
                                <td>{{ $boards->total() - ($boards->currentPage() - 1) * $boards->perPage() - $loop->index }}</td>
                                <td><a href="{{ route('admin.board.edit', $board->id) }}">{{ $board->board_name }}</a></td>
                                <td><code>{{ $board->board_id }}</code></td>
                                <td>{{ $board->board_type == 1 ? '일반' : ($board->board_type == 2 ? '갤러리' : 'QnA') }}</td>
                                <td>{{ number_format($board->posts_count ?? 0) }}</td>
                                <td>{{ $board->list_level == 0 ? '전체' : 'LV.'.$board->list_level }}</td>
                                <td>{{ $board->write_level == 0 ? '전체' : 'LV.'.$board->write_level }}</td>
                                <td>{{ $board->use_comment == 'Y' ? '✔' : '-' }}</td>
                                <td>{{ $board->use_file == 'Y' ? '✔' : '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.board.toggleStatus', $board->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="padding:3px 10px; border-radius:3px; border:1px solid #ddd; cursor:pointer; font-size:12px; background:{{ $board->status == 'Y' ? '#2e7d32' : '#999' }}; color:#fff;">
                                            {{ $board->status == 'Y' ? '사용' : '미사용' }}
                                        </button>
                                    </form>
                                </td>                                
                                <td>
                                    <a href="{{ route('admin.board.edit', $board->id) }}" class="btn-edit">수정</a>
                                    <button type="button" class="btn-delete" onclick="deleteBoard({{ $board->id }}, '{{ $board->board_name }}')">삭제</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" style="text-align:center; padding:30px;">등록된 게시판이 없습니다.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>

                {{ $boards->appends(request()->query())->links() }}

            </div>
        </div>
    </div>
</div>

{{-- 삭제 모달 --}}
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:8px; padding:30px; width:400px;">
        <h4 style="margin-bottom:15px;">게시판 삭제</h4>
        <p><strong id="delete_board_name"></strong></p>
        <p style="color:#d70000; font-size:13px;">삭제시 모든 게시글과 댓글이 함께 삭제됩니다.<br>정말 삭제하시겠습니까?</p>
        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
            <button onclick="closeModal()" style="padding:8px 20px; background:#f0f0f0; border:0; border-radius:4px; cursor:pointer;">취소</button>
            <form id="delete_form" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="padding:8px 20px; background:#d70000; color:#fff; border:0; border-radius:4px; cursor:pointer;">삭제</button>
            </form>
        </div>
    </div>
</div>

<script>
const checkAll = document.getElementById('check_all');
const checkboxes = document.querySelectorAll('.board-check');
const checkedCount = document.getElementById('checked_count');

checkAll.addEventListener('change', function() {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateCount();
});
checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

function updateCount() {
    const cnt = document.querySelectorAll('.board-check:checked').length;
    checkedCount.textContent = cnt + '개 선택됨';
}

function doBulkAction() {
    const action = document.getElementById('bulk_action').value;
    const checked = document.querySelectorAll('.board-check:checked');
    if (!action) return alert('처리할 작업을 선택해주세요.');
    if (checked.length === 0) return alert('선택된 게시판이 없습니다.');
    if (action === 'delete' && !confirm(`선택한 ${checked.length}개 게시판을 삭제하시겠습니까?`)) return;
    document.getElementById('bulk_action_value').value = action;
    document.getElementById('bulk_form').submit();
}

function deleteBoard(id, name) {
    document.getElementById('delete_board_name').textContent = '「' + name + '」';
    document.getElementById('delete_form').action = '/admin/board/' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@include('admin.inc.admin_footer')
