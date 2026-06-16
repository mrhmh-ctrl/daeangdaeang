@include('inc.dogdict_header')
<div id="dogdict-container">
    <div class="search-inner">
        @forelse($results as $dog)
        <div class="dog-item">
            <div class="dog-info">
                <h3><a href="{{ route('dogdict.view', $dog->id) }}">{{ $dog->name }}</a></h3>
				<p>{!! preg_replace('/^[A-Za-z\s\(\)\[\]".,\-:\/0-9]+$/m', '', preg_replace('/==[^=]+==/','', $dog->content)) !!}</p>
            </div>
        </div>
        @empty
        <p>검색결과가 없습니다.</p>
        @endforelse
    </div>
</div>
@include('inc.dogdict_footer')