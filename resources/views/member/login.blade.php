@include('inc.member_header')
<div id="login_wrap">
    <div class="login_inner">
        <h1>댕댕닷컴 계정으로 로그인</h1>

        @if($errors->has('login'))
            <p style="color:#f44336; font-size:13px; text-align:center;">{{ $errors->first('login') }}</p>
        @endif

        <fieldset>
            <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                @csrf
                <div class="login-field">

                    @if($login_type === 'id')
                        <label>아이디</label>
                        <input class="login-hp" name="user_id" value="{{ old('user_id') }}" placeholder="아이디 입력">
                    @else
                        <label>이메일</label>
                        <input class="login-hp" name="email" value="{{ old('email') }}" placeholder="이메일 입력">
                    @endif

                    <label style="margin-top:10px;">비밀번호</label>
                    <input type="password" class="login-hp" name="password" placeholder="비밀번호 입력">

                    <input type="submit" class="login-btn" value="로그인">
                    <h2>또는</h2>
                    <input type="submit" class="etc-btn" value="구글계정 로그인">
                    <input type="submit" class="etc-btn" value="카카오계정 로그인">

                    <div class="member-field">
                        <p>계정이 없으신가요?<br>계정을 만들어 보세요.</p>
                        <a href="{{ url('/register') }}"><strong>회원가입하기</strong></a>
                    </div>
                </div>
            </form>
        </fieldset>
    </div>
</div>
@include('inc.member_footer')