@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>권한 관리</h2>
    </div>
    <div class="admin-contents">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($managers->isEmpty())
            <div class="alert-error">등록된 일반 관리자가 없습니다.</div>
        @else

        {{-- 관리자 선택 --}}
        <div style="margin-bottom:20px; display:flex; align-items:center; gap:10px;">
            <select id="managerSelect" style="height:36px; border:1px solid #ddd; border-radius:4px; padding:0 10px; width:300px;">
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                @endforeach
            </select>
        </div>

        {{-- 관리자별 권한 폼 --}}
        @foreach($managers as $manager)
        <div id="manager_{{ $manager->id }}" style="display:none;">
            <form action="{{ route('admin.auth_config.update') }}" method="POST">
                @csrf
                <input type="hidden" name="manager_id" value="{{ $manager->id }}">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:200px;">메뉴</th>
                            <th style="width:150px;">권한없음</th>
                            <th style="width:150px;">보기</th>
                            <th style="width:150px;">설정</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $parents = $menus->whereNull('parent_key'); @endphp

                        @foreach($parents as $parent)
                            {{-- 상위 메뉴 --}}
                            <tr style="background:#f0f4ff;">
                                <td style="font-weight:700; padding-left:15px;">{{ $parent->menu_name }}</td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $parent->menu_key }}" value="0"
                                        {{ ($permMap[$manager->id][$parent->menu_key] ?? 0) == 0 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $parent->menu_key }}" value="1"
                                        {{ ($permMap[$manager->id][$parent->menu_key] ?? 0) == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $parent->menu_key }}" value="2"
                                        {{ ($permMap[$manager->id][$parent->menu_key] ?? 0) == 2 ? 'checked' : '' }}>
                                </td>
                            </tr>

                            {{-- 하위 메뉴 --}}
                            @foreach($menus->where('parent_key', $parent->menu_key) as $child)
                            <tr>
                                <td style="padding-left:30px; color:#666;">
                                    <i class="fas fa-angle-right" style="margin-right:5px;"></i>
                                    {{ $child->menu_name }}
                                </td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $child->menu_key }}" value="0"
                                        {{ ($permMap[$manager->id][$child->menu_key] ?? 0) == 0 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $child->menu_key }}" value="1"
                                        {{ ($permMap[$manager->id][$child->menu_key] ?? 0) == 1 ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="radio" name="auth_{{ $manager->id }}_{{ $child->menu_key }}" value="2"
                                        {{ ($permMap[$manager->id][$child->menu_key] ?? 0) == 2 ? 'checked' : '' }}>
                                </td>
                            </tr>
                            @endforeach

                        @endforeach
                    </tbody>
                </table>

                <div class="form-buttons">
                    <button type="submit" class="btn-submit">권한 저장</button>
                </div>
            </form>
        </div>
        @endforeach

        @endif

    </div>
</div>
</div>
</div>

<script>
// 페이지 로드시 첫번째 관리자 자동 표시
window.addEventListener('load', function() {
    const select = document.getElementById('managerSelect');
    if (select && select.value) {
        document.getElementById('manager_' + select.value).style.display = 'block';
    }
});

document.getElementById('managerSelect').addEventListener('change', function() {
    document.querySelectorAll('[id^="manager_"]').forEach(el => el.style.display = 'none');
    const id = this.value;
    if (id) {
        document.getElementById('manager_' + id).style.display = 'block';
    }
});
</script>

@include('admin.inc.admin_footer')