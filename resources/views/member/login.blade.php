@include('inc.member_header')
<div id="login_wrap">
    <div class="login_inner">
        <h1>댕댕닷컴 계정으로 로그인</h1>

        @if($errors->has('login'))
            <p style="color:#f44336; font-size:13px; text-align:center;">{{ $errors->first('login') }}</p>
        @endif

        <fieldset>
            <form action="{{ route('login.post') }}" method="POST" autocomplete="off" id="loginForm" onsubmit="return validateLogin()">
                @csrf
                <div class="login-field">

                    @if($login_type === 'id')
                        <label>아이디</label>
                        <input class="login-hp" name="user_id" id="user_id" value="{{ old('user_id') }}" placeholder="아이디 입력">
                    @else
                        <label>이메일</label>
                        <input class="login-hp" name="email" id="email" value="{{ old('email') }}" placeholder="이메일 입력">
                    @endif

                    <label style="margin-top:10px;">비밀번호</label>
                    <input type="password" class="login-hp" name="password" id="password" placeholder="비밀번호 입력">

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

<script>
function validateLogin() {
    @if($login_type === 'id')
        const idVal = document.getElementById('user_id').value.trim();
        if (idVal === '') {
            alert('아이디를 입력해주세요.');
            document.getElementById('user_id').focus();
            return false;
        }
    @else
        const emailVal = document.getElementById('email').value.trim();
        if (emailVal === '') {
            alert('이메일을 입력해주세요.');
            document.getElementById('email').focus();
            return false;
        }
    @endif

    const pwVal = document.getElementById('password').value.trim();
    if (pwVal === '') {
        alert('비밀번호를 입력해주세요.');
        document.getElementById('password').focus();
        return false;
    }

    return true;
}
</script>
@include('inc.member_footer')