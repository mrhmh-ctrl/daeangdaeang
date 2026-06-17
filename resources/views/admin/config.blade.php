@include('admin.inc.admin_header')
                <div class="content-header">
                    <h2>환경설정</h2>
                    <div class="top-dict-buttons">
                        <a href="{{ route('admin.board.create') }}" class="btn-add"><li>+ 게시판 등록</li></a>
                    </div>
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

                <form action="{{ route('admin.config.update') }}" method="POST">
                    @csrf

                    {{-- 기본 설정 --}}
                    <div style="margin:20px 0 10px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">기본 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>사이트명 <span class="required">*</span></th>
                            <td>
                                <input type="text" name="site_name"
                                    value="{{ old('site_name', $configs['site_name']->config_value ?? '') }}"
                                    placeholder="사이트명 입력">
                                <span style="font-size:12px; color:#999; margin-left:8px;">브라우저 탭, 메일 등에 사용됩니다</span>
                            </td>
                        </tr>
                        <tr>
                            <th>관리자 이메일 <span class="required">*</span></th>
                            <td>
                                <input type="email" name="admin_email"
                                    value="{{ old('admin_email', $configs['admin_email']->config_value ?? '') }}"
                                    placeholder="admin@example.com">
                                <span style="font-size:12px; color:#999; margin-left:8px;">시스템 알림 메일 수신 주소</span>
                            </td>
                        </tr>
                        <tr>
                            <th>사이트 주소 <span class="required">*</span></th>
                            <td>
                            <input type="text" name="site_url"
                            value="{{ old('site_url', $configs['site_url']->config_value ?? '') }}"
                            placeholder="https://example.com">
                                <span style="font-size:12px; color:#999; margin-left:8px;"></span>
                            </td>
                        </tr>
                    </table>

                    {{-- 로그인 설정 --}}
                    <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">로그인 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>로그인 방식</th>
                            <td>
                                <label style="margin-right:20px; cursor:pointer;">
                                    <input type="radio" name="login_type" value="id"
                                        {{ old('login_type', $configs['login_type']->config_value ?? 'id') == 'id' ? 'checked' : '' }}>
                                    아이디로 로그인
                                </label>
                                <label style="cursor:pointer;">
                                    <input type="radio" name="login_type" value="email"
                                        {{ old('login_type', $configs['login_type']->config_value ?? 'id') == 'email' ? 'checked' : '' }}>
                                    이메일로 로그인
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>세션유지시간 (관리자)</th>
                            <td>
                            <input type="text" name="login_session_time_admin" 
                            value="{{ old('login_session_time_admin', $configs['login_session_time_admin']->config_value ?? '7200') }}" 
                            style="width:80px;"> 초
                            </td>
                        </tr>
                        <tr>
                            <th>세션유지시간 (사용자)</th>
                            <td>
                            <input type="text" name="login_session_time_user" 
                            value="{{ old('login_session_time_user', $configs['login_session_time_user']->config_value ?? '3600') }}" 
                            style="width:80px;">
                               초
                            </td>
                        </tr>                        
                    </table>

                    <div class="form-buttons">
                        <button type="submit" class="btn-submit">설정 저장</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@include('admin.inc.admin_footer')
