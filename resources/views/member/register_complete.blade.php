@include('inc.member_header')
<div id="register-joinform-wrap">
   <p class="step">4/4</p>
		<h1>회원가입이 완료되었습니다.</h1>
		<div class="register-complete-message">
			귀하의 E-MAIL 주소는 입니다. <br>
			이메일주소로 로그인해주세요.
		</div>	
	<a href="{{ url('/login') }}" class="next_btn" id="joinBtn">로그인 페이지로</a>
</div>

@include('inc.member_footer')