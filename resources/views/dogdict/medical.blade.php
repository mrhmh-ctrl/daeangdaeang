    {{-- 견종목록별 --}}
    <div class="dogdict-list-wrap">
        <h2>의학정보</h2>
        <div class="dog-grid">
            @forelse($dogs as $dog)
            <div class="dog-card">
				<a href="/dogdict/dict_query?where=search&ie=utf8&q={{ urlencode($dog->name) }}">
                    <h3>{{ $dog->name }}</h3>
				</a>
            </div>
            @empty
            <p>등록된 게시물이 없습니다.</p>
            @endforelse
        </div>
	{{ $dogs->appends(request()->query())->links() }}
    </div>