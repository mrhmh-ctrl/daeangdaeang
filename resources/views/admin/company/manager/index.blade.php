@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>관리자 목록</h2>
        <div class="top-function">
            <div></div>
            <a href="{{ route('admin.manager.create') }}">+ 관리자 등록</a>
        </div>
    </div>
    <div class="admin-contents">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>이름</th>
                    <th>직급</th>
                    <th>이메일</th>
                    <th>연락처</th>
                    <th>권한</th>
                    <th>마지막 로그인</th>
                    <th>등록일</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                @forelse($managers as $manager)
                    <tr>
                        <td>{{ $manager->id }}</td>
                        <td>{{ $manager->name }}</td>
                        <td>{{ $manager->rank }}</td>
                        <td>{{ $manager->email }}</td>
                        <td>{{ $manager->hp }}</td>
                        <td>
                            @if($manager->is_admin == 1)
                                <span style="color:#0b57d0; font-weight:700;">마스터</span>
                            @else
                                <span style="color:#666;">일반관리자</span>
                            @endif
                        </td>                        
                        <td>{{ $manager->last_login ?? '-' }}</td>
                        <td>{{ $manager->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.manager.edit', $manager->id) }}" class="btn-edit">수정</a>
                            @if($manager->is_admin == 0)
                                <form action="{{ route('admin.manager.destroy', $manager->id) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('정말 삭제하시겠습니까?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">삭제</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">등록된 관리자가 없습니다.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
</div>
</div>
@include('admin.inc.admin_footer')