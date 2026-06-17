@include('inc.member_header')
<div id="register-joincomplete-wrap">
    <p class="step">4/4</p>
    <h1>회원가입이 완료되었습니다.</h1>
    <div class="register-complete-message">
        @if(session('registered_login_type') === 'id')
            귀하의 아이디는 <strong>{{ session('registered_user_id') }}</strong> 입니다. <br>
            아이디로 로그인해주세요.
        @else
            귀하의 E-MAIL 주소는 <strong>{{ session('registered_email') }}</strong> 입니다. <br>
            이메일주소로 로그인해주세요.
        @endif
    </div>
    <a href="{{ url('/login') }}" class="next_btn">로그인 페이지로</a>
</div>
@include('inc.member_footer')