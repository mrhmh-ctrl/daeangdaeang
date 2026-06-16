@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>견종 일괄 Import</h2>
        <a href="{{ route('admin.dogdict.index') }}">← 목록</a>
    </div>
    <div class="admin-contents">
        <div class="import-wrap">
                    <div class="import-step">
                        <h3>1단계 - 카테고리 선택</h3>
					<select id="categoryId">
						@foreach($categories as $category)
							@if($category->slug == 'dog_list')
								<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
							@endif
						@endforeach
					</select>
                    </div>

                    <div class="import-step">
                        <h3>2단계 - 위키 견종 목록 가져오기</h3>
                        <button type="button" id="btnFetch" class="btn-submit">목록 가져오기</button>
                        <span id="fetchStatus"></span>
                    </div>

                    <div class="import-step" id="stepBreeds" style="display:none;">
                        <h3>3단계 - 견종 선택 후 Import</h3>
                        <div style="margin-bottom:10px;">
                            <button type="button" onclick="selectAll(true)" class="btn-edit">전체선택</button>
                            <button type="button" onclick="selectAll(false)" class="btn-edit">전체해제</button>
                            <button type="button" id="btnImport" class="btn-submit">선택 Import</button>
                            <span id="importStatus"></span>
                        </div>
                        <div id="breedList" style="height:400px; overflow-y:scroll; border:1px solid #ddd; padding:10px;"></div>
                    </div>

                    <div class="import-step3">
                        <h3>진행상황</h3>
                        <div style="background:#f5f5f5; border-radius:4px; height:20px; margin-bottom:10px;">
                            <div id="progressBar" style="background:#3a8afd; height:20px; border-radius:4px; width:0%; transition:width 0.3s;"></div>
                        </div>
                        <div id="logWrap" style="height:400px; overflow-y:scroll; border:1px solid #ddd; padding:10px; font-size:12px; font-family:monospace;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
var breeds = [];
var csrfToken = '{{ csrf_token() }}';
var fetchUrl = '{{ route("admin.dogdict.import.fetch") }}';
var importUrl = '{{ route("admin.dogdict.import.run") }}';

document.getElementById('btnFetch').addEventListener('click', function() {
    document.getElementById('fetchStatus').textContent = '가져오는중...';
    fetch(fetchUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            breeds = data.breeds;
            document.getElementById('fetchStatus').textContent = data.count + '개 견종 발견';
            var wrap = document.getElementById('breedList');
            wrap.innerHTML = '';
            breeds.forEach(function(b) {
                wrap.innerHTML += '<label style="display:block; padding:3px 0;"><input type="checkbox" class="breed-check" value="' + b + '" checked> ' + b + '</label>';
            });
            document.getElementById('stepBreeds').style.display = 'block';
        } else {
            document.getElementById('fetchStatus').textContent = '실패: ' + data.message;
        }
    })
    .catch(function(err) {
        document.getElementById('fetchStatus').textContent = '오류: ' + err.message;
    });
});

function selectAll(checked) {
    document.querySelectorAll('.breed-check').forEach(function(cb) {
        cb.checked = checked;
    });
}

document.getElementById('btnImport').addEventListener('click', async function() {
    var categoryId = document.getElementById('categoryId').value;
    if (!categoryId) {
        alert('카테고리를 선택해주세요!');
        return;
    }

    var selected = [];
    document.querySelectorAll('.breed-check:checked').forEach(function(cb) {
        selected.push(cb.value);
    });

    if (selected.length === 0) {
        alert('견종을 선택해주세요!');
        return;
    }

    var log = document.getElementById('logWrap');
    var btn = document.getElementById('btnImport');
    btn.disabled = true;
    log.innerHTML = '';

    for (var i = 0; i < selected.length; i++) {
        var breed = selected[i];
        var percent = Math.round((i + 1) / selected.length * 100);
        document.getElementById('progressBar').style.width = percent + '%';
        document.getElementById('importStatus').textContent = (i + 1) + '/' + selected.length;

        var res = await fetch(importUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ title: breed, category_id: categoryId })
        });

        var data = await res.json();
        var color = data.success ? '#2e7d32' : '#c62828';
        log.innerHTML += '<div style="color:' + color + '">[' + (i + 1) + '/' + selected.length + '] ' + data.message + '</div>';
        log.scrollTop = log.scrollHeight;

        await new Promise(function(r) { setTimeout(r, 500); });
    }

    btn.disabled = false;
    document.getElementById('importStatus').textContent = '완료!';
});
</script>
@include('admin.inc.admin_footer')