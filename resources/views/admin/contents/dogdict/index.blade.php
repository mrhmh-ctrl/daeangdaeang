@include('admin.inc.admin_header')
         
        <div class="content-header">
        <h2>애견백과관리</h2>
        <div class="top-dict-buttons">
            <a href="{{ route('admin.dogdict.url_import') }}" class="btn-add"><li>+ URL로 가저오기</li></a>
            <a href="{{ route('admin.dogdict.import') }}" class="btn-add"><li>+ 위키 백과 가저오기 (견종)</li></a>
            <a href="{{ route('admin.dogdict.create') }}" class="btn-add"><li>+ 등록</li></a>
        </div>
        </div>
        <div class="admin-contents">
                {{-- 카테고리 탭 --}}
                <div class="category-menus">
                    <a href="{{ route('admin.dogdict.index') }}" class="{{ !request('c') ? 'active' : '' }}"><li>전체</li></a>
                    <a href="{{ route('admin.dogdict.index', ['c' => 'dog_list']) }}" class="{{ request('c') == 'dog_list' ? 'active' : '' }}"><li>견종목록별</li></a>
                    <a href="{{ route('admin.dogdict.index', ['c' => 'dog_training']) }}" class="{{ request('c') == 'dog_training' ? 'active' : '' }}"><li>강아지훈련법</li></a>
                    <a href="{{ route('admin.dogdict.index', ['c' => 'first_aid']) }}" class="{{ request('c') == 'first_aid' ? 'active' : '' }}"><li>증상별 응급처치</li></a>
                    <a href="{{ route('admin.dogdict.index', ['c' => 'medical']) }}" class="{{ request('c') == 'medical' ? 'active' : '' }}"><li>의학정보</li></a>
                </div>

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif
				
				<div class="top-function">
					<div class="total-count">
						전체 게시물 : {{ $dogs->total() }}개
					</div>
					<div class="search">
						<form method="GET" action="{{ route('admin.dogdict.index') }}">
							@if(request('c'))
								<input type="hidden" name="c" value="{{ request('c') }}">
							@endif
							<input type="text" name="q" value="{{ request('q') }}" placeholder="견종명 검색">
							<button type="submit">검색</button>
						</form>
					</div>
				</div>
				
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>카테고리</th>
                            <th>견종명</th>
                            <th>조회수</th>
                            <th>등록일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dogs as $dog)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dog->category->name ?? '-' }}</td>
                            <td>{{ $dog->name }}</td>
                            <td>{{ $dog->view_count }}</td>
                            <td>{{ $dog->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.dogdict.edit', $dog->id) }}" class="btn-edit">수정</a>
                                <form method="POST" action="{{ route('admin.dogdict.destroy', $dog->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('삭제하시겠습니까?')">삭제</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px;">등록된 데이터가 없습니다.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $dogs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@include('admin.inc.admin_footer')
