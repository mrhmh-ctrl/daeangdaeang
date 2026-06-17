@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>펫 정보 관리</h2>
    </div>
    <div class="admin-contents">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="top-function">
            <div class="total-count">전체 <strong>{{ $pets->total() }}</strong> 건</div>
            <form action="{{ route('admin.pet.index') }}" method="GET" class="search">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="펫명 / 견종 / 회원명">
                <button type="submit">검색</button>
            </form>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>회원명</th>
                    <th>닉네임</th>
                    <th>펫 이름</th>
                    <th>견종</th>
                    <th>성별</th>
                    <th>생년월일</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pets as $pet)
                <tr>
                    <td>{{ $pet->id }}</td>
                    <td>{{ $pet->member_name }}</td>
                    <td>{{ $pet->member_nick }}</td>
                    <td>{{ $pet->name ?? '-' }}</td>
                    <td>{{ $pet->breed ?? '-' }}</td>
                    <td>{{ $pet->gender === 'M' ? '수컷' : ($pet->gender === 'F' ? '암컷' : '-') }}</td>
                    <td>{{ $pet->birth ?? '-' }}</td>
                    <td>{{ $pet->created_at }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">등록된 펫 정보가 없습니다.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pets->appends(request()->query())->links() }}

    </div>
</div>
</div>
</div>
@include('admin.inc.admin_footer')