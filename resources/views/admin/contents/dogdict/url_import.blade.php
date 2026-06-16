@include('admin.inc.admin_header')
            <div class="content-header">
                <h2>URL로 견종 가져오기</h2>
                <a href="{{ route('admin.dogdict.index') }}">← 목록</a>
            </div>
            <div class="admin-contents">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.dogdict.url_import.post') }}">
                @csrf
                <table class="admin-form-table">
                    <tr>
                        <th>카테고리</th>
                        <td>
                            <select name="category_id">
                                <option value="">선택</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->slug == 'dog_list' ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>견종명 *</th>
                        <td><input type="text" name="name" value="{{ old('name') }}" placeholder="예) 포메라니안" required></td>
                    </tr>
                    <tr>
                        <th>URL *</th>
                        <td>
                            <input type="text" name="url" value="{{ old('url') }}" placeholder="https://ko.wikipedia.org/wiki/포메라니안" style="width:500px;" required>
                            <p style="font-size:12px; color:#999; margin-top:5px;">위키백과 또는 다른 웹페이지 URL 입력</p>
                        </td>
                    </tr>
                </table>
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">가져오기 & 저장</button>
                    <a href="{{ route('admin.dogdict.index') }}" class="btn-cancel">취소</a>
                </div>
            </form>
        </div>
    </div>
</div>
@include('admin.inc.admin_footer')
