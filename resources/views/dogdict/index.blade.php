@include('inc.dogdict_header')

<div id="dogdict-container">
    @if(!request('c'))
    {{-- 메인 검색 화면 --}}
    <div class="dogdict-search">
        <h1>애견사전</h1>
        <fieldset>
            <form method="GET" action="/dogdict/dict_query">
                <input type="hidden" name="where" value="search">
                <input type="hidden" name="ie" value="utf8">
                <input type="text" class="keysearch" name="q">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <div class="popular">
                <ul>
                    <h2>추천 검색어</h2>
                    @foreach($popular as $dog)
                        <span>
                            <a href="/dogdict/dict_query?where=search&ie=utf8&q={{ urlencode($dog) }}">{{ $dog }}</a>
                        </span>
                    @endforeach
                </ul>
            </div>
        </fieldset>
    </div>
    @elseif(request('c') == 'dog_list')
	@include('dogdict.dog_list')	
	@elseif(request('c') == 'dog_training')    
	@include('dogdict.dog_training')
    @elseif(request('c') == 'first_aid')    
	@include('dogdict.first_aid')
    @elseif(request('c') == 'medical')
   	@include('dogdict.medical')
    @endif
</div>
@include('inc.dogdict_footer')