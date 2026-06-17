@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>SEO 설정</h2>
    </div>
    <div class="admin-contents">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.seo_config.update') }}" method="POST">
            @csrf

            {{-- 기본 SEO 설정 --}}
            <div style="margin:20px 0 10px;">
                <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">기본 SEO 설정</h4>
            </div>
            <table class="admin-form-table">
                <tr>
                    <th>홈페이지 타이틀</th>
                    <td>
                        <input type="text" name="meta_title"
                            value="{{ old('meta_title', $configs['meta_title']->config_value ?? '') }}"
                            placeholder="페이지 제목">
                        <span style="font-size:12px; color:#999; margin-left:8px;">브라우저 탭 및 검색결과에 표시됩니다 (권장 60자 이내)</span>
                    </td>
                </tr>
                <tr>
                    <th>Meta Description</th>
                    <td>
                        <input type="text" name="meta_description"
                            value="{{ old('meta_description', $configs['meta_description']->config_value ?? '') }}"
                            placeholder="페이지 설명">
                        <span style="font-size:12px; color:#999; margin-left:8px;">검색결과 설명 문구 (권장 160자 이내)</span>
                    </td>
                </tr>
                <tr>
                    <th>Meta Keywords</th>
                    <td>
                        <input type="text" name="meta_keywords"
                            value="{{ old('meta_keywords', $configs['meta_keywords']->config_value ?? '') }}"
                            placeholder="키워드1, 키워드2, 키워드3">
                        <span style="font-size:12px; color:#999; margin-left:8px;">쉼표로 구분하여 입력</span>
                    </td>
                </tr>
                <tr>
                    <th>Canonical URL</th>
                    <td>
                        <input type="text" name="canonical_url"
                            value="{{ old('canonical_url', $configs['canonical_url']->config_value ?? '') }}"
                            placeholder="https://daengdaeng.com">
                        <span style="font-size:12px; color:#999; margin-left:8px;">중복 URL 방지용 대표 주소</span>
                    </td>
                </tr>
                <tr>
                <th>파비콘 설정</th>
                <td>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div>
                            <input type="file" name="favicon" accept=".ico,.png,.jpg,.jpeg,.svg" id="faviconInput">
                            <input type="hidden" name="favicon_current" value="{{ $configs['favicon']->config_value ?? '' }}">
                            <div style="font-size:12px; color:#999; margin-top:4px;">권장: 32x32 또는 64x64 / .ico .png 형식</div>
                        </div>

                        {{-- 미리보기 --}}
                        <div id="faviconPreview" style="display:flex; align-items:center; gap:8px;">
                            @if(!empty($configs['favicon']->config_value ?? ''))
                                <img id="faviconImg" src="{{ $configs['favicon']->config_value }}"
                                    style="width:32px; height:32px; border:1px solid #ddd; padding:2px;">
                                <span style="font-size:12px; color:#999;">현재 파비콘</span>
                            @else
                                <img id="faviconImg" src="" style="width:32px; height:32px; border:1px solid #ddd; padding:2px; display:none;">
                                <span id="faviconNone" style="font-size:12px; color:#999;">등록된 파비콘 없음</span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Meta Robots</th>
                <td>
                    <select name="robots" style="height:36px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px;">
                        <option value="index,follow"    {{ old('robots', $configs['robots']->config_value ?? '') == 'index,follow'    ? 'selected' : '' }}>index, follow (기본 - 크롤링 허용)</option>
                        <option value="noindex,follow"  {{ old('robots', $configs['robots']->config_value ?? '') == 'noindex,follow'  ? 'selected' : '' }}>noindex, follow (색인 제외)</option>
                        <option value="noindex,nofollow" {{ old('robots', $configs['robots']->config_value ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow (완전 차단)</option>
                    </select>
                    <span style="font-size:12px; color:#999; margin-left:8px;">검색엔진 크롤링 규칙 설정</span>
                </td>
                </tr>
                <th>Robots.txt 설정</th>
                <td>
                    <textarea name="robots_txt"
                        style="width:500px; height:150px; border:1px solid #ddd; border-radius:4px; padding:8px; font-family:monospace;">{{ old('robots_txt', $configs['robots_txt']->config_value ?? '') }}</textarea>
                    <div style="margin-top:6px;">
                        <form action="{{ route('admin.seo_config.robots_sync') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-edit">robots.txt 파일 적용</button>
                        </form>
                        <span style="font-size:12px; color:#999; margin-left:8px;">저장 후 실제 파일에 반영합니다</span>
                    </div>
                </td>
            </tr>
            </table>

            {{-- OG 설정 --}}
            <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">OG (Open Graph) 설정</h4>
            </div>
            <table class="admin-form-table">
                <tr>
                    <th>OG Title</th>
                    <td>
                        <input type="text" name="og_title"
                            value="{{ old('og_title', $configs['og_title']->config_value ?? '') }}"
                            placeholder="SNS 공유시 제목">
                        <span style="font-size:12px; color:#999; margin-left:8px;">카카오, 페이스북 등 SNS 공유 제목</span>
                    </td>
                </tr>
                <tr>
                    <th>OG Description</th>
                    <td>
                        <input type="text" name="og_description"
                            value="{{ old('og_description', $configs['og_description']->config_value ?? '') }}"
                            placeholder="SNS 공유시 설명">
                        <span style="font-size:12px; color:#999; margin-left:8px;">카카오, 페이스북 등 SNS 공유 설명</span>
                    </td>
                </tr>
                <tr>
                    <th>OG Image</th>
                    <td>
                        <input type="text" name="og_image"
                            value="{{ old('og_image', $configs['og_image']->config_value ?? '') }}"
                            placeholder="/images/og.jpg">
                        <span style="font-size:12px; color:#999; margin-left:8px;">SNS 공유시 표시될 이미지 경로 (권장 1200x630)</span>
                    </td>
                </tr>
            </table>

            <div class="form-buttons">
                <button type="submit" class="btn-submit">설정 저장</button>
            </div>
            <script>
                document.getElementById('faviconInput').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('faviconImg');
                        const none = document.getElementById('faviconNone');

                        img.src = e.target.result;
                        img.style.display = 'block';
                        if (none) none.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                });
                </script>
        </form>

    </div>
</div>
</div>
</div>
@include('admin.inc.admin_footer')