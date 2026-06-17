@include('admin.inc.admin_header')
                <div class="content-header">
                    <h2>회원 관리</h2>
                    <div class="top-dict-buttons">
                        <a href="{{ route('admin.member.create') }}" class="btn-add"><li>+ 회원 등록</li></a>
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
                    <div class="total-count">전체 회원 : {{ $members->total() }}명</div>
                    <div class="search">
                        <form method="GET" action="{{ route('admin.member.index') }}">
                            <select name="status" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                                <option value="">전체</option>
                                <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>정상</option>
                                <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>탈퇴</option>
                                <option value="B" {{ request('status') == 'B' ? 'selected' : '' }}>정지</option>
                            </select>
                            <select name="level" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                                <option value="">레벨 전체</option>
                                @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>LV.{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="search_field" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                                <option value="user_id" {{ request('search_field') == 'user_id' ? 'selected' : '' }}>아이디</option>
                                <option value="name"    {{ request('search_field') == 'name'    ? 'selected' : '' }}>이름</option>
                                <option value="nick"    {{ request('search_field') == 'nick'    ? 'selected' : '' }}>닉네임</option>
                                <option value="email"   {{ request('search_field') == 'email'   ? 'selected' : '' }}>이메일</option>
                                <option value="hp"      {{ request('search_field') == 'hp'      ? 'selected' : '' }}>휴대폰</option>
                            </select>
                            <input type="text" name="search_keyword" value="{{ request('search_keyword') }}" placeholder="검색어 입력">
                            <button type="submit">검색</button>
                        </form>
                    </div>
                </div>

                <form id="bulk_form" action="{{ route('admin.member.bulk') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" id="bulk_action_value">
                    <div style="display:flex; align-items:center; gap:5px; margin-bottom:8px;">
                        <select id="bulk_action" style="height:34px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                            <option value="">일괄 처리</option>
                            <option value="level_up">레벨 올리기</option>
                            <option value="level_down">레벨 내리기</option>
                            <option value="block">정지</option>
                            <option value="unblock">정지 해제</option>
                            <option value="delete">선택 삭제</option>
                        </select>
                        <button type="button" onclick="doBulkAction()" style="height:34px; padding:0 16px; background:#444; color:#fff; border:0; border-radius:4px; font-size:13px; cursor:pointer;">적용</button>
                        <span id="checked_count" style="font-size:12px; color:#666;">0명 선택됨</span>
                    </div>

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>No</th>
                                @if($login_type === 'id')
                                <th>아이디</th>
                                @endif
                                @if($login_type === 'email')
                                <th>이메일</th>
                                @endif
                                <th>이름</th>
                                <th>닉네임</th>
                                @if($login_type === 'id')
                                <th>이메일</th>
                                @endif
                                @if($login_type === 'email')                                
                                @endif
                                <th>휴대폰</th>
                                <th>레벨</th>
                                <th>소셜</th>
                                <th>게시글</th>
                                <th>상태</th>
                                <th>가입일</th>
                                <th>최근로그인</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $member->id }}" class="member-check"></td>
                                <td>{{ $members->total() - ($members->currentPage() - 1) * $members->perPage() - $loop->index }}</td>
                                @if($login_type === 'id')                              
                                <td>
                                <a href="{{ route('admin.member.edit', $member->id) }}">{{ $member->user_id }}</a>
                                </td>
                                @endif
                                @if($login_type === 'email')                              
                                <td>{{ $member->email }}</td>
                                @endif                                     
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->nick }}</td> 
                                @if($login_type === 'email')                                                              
                                @endif                       
                                @if($login_type === 'id')                              
                                <td>{{ $member->email }}</td>                                
                                @endif                                                                                               
                                <td>{{ $member->hp ? preg_replace('/(\d{3})(\d{3,4})(\d{4})/', '$1-$2-$3', $member->hp) : '-' }}</td>                                <td>LV.{{ $member->level }}</td>
                                <td>{{ $member->social_type ? strtoupper($member->social_type) : '-' }}</td>
                                <td>{{ number_format($member->posts_count ?? 0) }}</td>
                                <td>
                                    @if($member->status == 'Y') <span style="color:#2e7d32; font-weight:600;">정상</span>
                                    @elseif($member->status == 'N') <span style="color:#999;">탈퇴</span>
                                    @elseif($member->status == 'B') <span style="color:#d70000; font-weight:600;">정지</span>
                                    @endif
                                </td>
                                <td>{{ $member->created_at ? $member->created_at->format('Y-m-d') : '-' }}</td>
                                <td>{{ $member->login_at ? $member->login_at->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.member.edit', $member->id) }}" class="btn-edit">수정</a>
                                    <button type="button" class="btn-delete" onclick="deleteMember({{ $member->id }}, '{{ $member->user_id }}')">삭제</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" style="text-align:center; padding:30px;">등록된 회원이 없습니다.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>

                {{ $members->appends(request()->query())->links() }}

            </div>
        </div>
    </div>
</div>

<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:8px; padding:30px; width:400px;">
        <h4 style="margin-bottom:15px;">회원 삭제</h4>
        <p><strong id="delete_member_id"></strong></p>
        <p style="color:#d70000; font-size:13px;">삭제하면 복구가 불가능합니다.<br>정말 삭제하시겠습니까?</p>
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
const checkboxes = document.querySelectorAll('.member-check');
const checkedCount = document.getElementById('checked_count');

checkAll.addEventListener('change', function() {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateCount();
});
checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

function updateCount() {
    const cnt = document.querySelectorAll('.member-check:checked').length;
    checkedCount.textContent = cnt + '명 선택됨';
}

function doBulkAction() {
    const action = document.getElementById('bulk_action').value;
    const checked = document.querySelectorAll('.member-check:checked');
    if (!action) return alert('처리할 작업을 선택해주세요.');
    if (checked.length === 0) return alert('선택된 회원이 없습니다.');
    if (action === 'delete' && !confirm(`선택한 ${checked.length}명을 삭제하시겠습니까?`)) return;
    document.getElementById('bulk_action_value').value = action;
    document.getElementById('bulk_form').submit();
}

function deleteMember(id, userId) {
    document.getElementById('delete_member_id').textContent = '「' + userId + '」';
    document.getElementById('delete_form').action = '/admin/member/' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@include('admin.inc.admin_footer')
