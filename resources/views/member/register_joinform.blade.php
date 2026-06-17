@include('inc.member_header')
<div id="register-joinform-wrap">
  <p class="step">2/4</p>
		<h1>댕댕닷컴 이용을 위해<br>아래 정보들을 입력해주세요.</h1>
		<form action="{{ route('register.joinform.post') }}" method="POST" id="joinForm" onsubmit="return validateJoinForm()">
		@csrf
		@if($login_type === 'id')
		<div class="register-request-item">
		<label>아이디</label>
		<input type="text" class="register-form" name="user_id" id="user_id" value="{{ old('user_id') }}" placeholder="아이디 입력">
		@error('user_id')<p class="err-msg">{{ $message }}</p>@enderror
		</div>
		@endif
		@if($login_type === 'email')
		<div class="register-request-item">
			<label id="">E-MAIL</label>
			<input type="text" class="register-form" name="email" id="email" value="" placeholder="">
		</div>	
		@endif
		<div class="register-request-item">
		  <label>비밀번호</label>
		  <input type="password" class="register-form" name="password" value="" id="pwInput" oninput="checkStrength(this.value)">
		  <!-- 강도 그래프 -->
		  <div class="pw-strength-wrap">
			<div class="pw-strength-bar">
			  <div class="pw-bar-segment" id="seg0"></div>
			  <div class="pw-bar-segment" id="seg1"></div>
			  <div class="pw-bar-segment" id="seg2"></div>
			  <div class="pw-bar-segment" id="seg3"></div>
			</div>
			<span class="pw-strength-label" id="pwLabel"></span>
		  </div>
		  <p class="pw-rule">영문 + 숫자 + 특수문자 혼합 8자 이상</p>
		</div>
		<div class="register-request-item">
		  <label>비밀번호 확인</label>
		  <input type="password" class="register-form" name="password_confirmation" value="" id="pwConfirm" oninput="checkMatch()">
		  <p class="pw-match-msg" id="pwMatchMsg"></p>
		</div>
		<div class="register-request-item">
    	<label id="">생년월일</label>
    	<input type="date" class="register-date" name="birth" id="birth" value="{{ old('birth') }}" placeholder="1984-08-26" max="{{ date('Y-m-d') }}">
    	<p class="hint">예: 2000-01-01</p>
		</div>
		<div class="register-request-item">
			<label id="">이름</label>
			<input type="text" class="register-form" name="name" id="name" value="" placeholder="김댕댕">
		</div>	
		<div class="register-request-item">
			<label id="">휴대폰번호</label>
			<input type="text" class="register-form" name="hp" id="hp" value="" placeholder="01012345678" maxlength="11">
		</div>	
		<button type="submit" class="next_btn" id="joinBtn">추가정보입력</button>
		</form>
	</div>
</div>
<script>
function checkStrength(val) {
  let score = 0;
  if (val.length >= 8) score++;
  if (/[a-zA-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^a-zA-Z0-9]/.test(val)) score++;

  const colors = ['', '#f44336', '#ff9800', '#2196f3', '#4caf50'];
  const labels = ['', '매우 약함', '약함', '보통', '강함'];
  const labelColors = ['', '#f44336', '#ff9800', '#2196f3', '#4caf50'];

  for (let i = 0; i < 4; i++) {
    const seg = document.getElementById('seg' + i);
    seg.style.background = i < score ? colors[score] : '#e0e0e0';
  }

  const label = document.getElementById('pwLabel');
  label.textContent = val.length ? labels[score] : '';
  label.style.color = labelColors[score];

  checkMatch();
}

function checkMatch() {
  const pw = document.getElementById('pwInput').value;
  const confirm = document.getElementById('pwConfirm').value;
  const msg = document.getElementById('pwMatchMsg');
  if (!confirm) { msg.textContent = ''; return; }
  if (pw === confirm) {
    msg.textContent = '비밀번호가 일치합니다.';
    msg.style.color = '#4caf50';
  } else {
    msg.textContent = '비밀번호가 일치하지 않습니다.';
    msg.style.color = '#f44336';
  }
}

function validateJoinForm() {
    @if($login_type === 'id')
        if (document.getElementById('user_id').value.trim() === '') {
            alert('아이디를 입력해주세요.');
            document.getElementById('user_id').focus();
            return false;
        }
    @else
        if (document.getElementById('email').value.trim() === '') {
            alert('이메일을 입력해주세요.');
            document.getElementById('email').focus();
            return false;
        }
    @endif

    if (document.getElementById('pwInput').value.trim() === '') {
        alert('비밀번호를 입력해주세요.');
        document.getElementById('pwInput').focus();
        return false;
    }
    if (document.getElementById('pwConfirm').value.trim() === '') {
        alert('비밀번호 확인을 입력해주세요.');
        document.getElementById('pwConfirm').focus();
        return false;
    }
    if (document.getElementById('pwInput').value !== document.getElementById('pwConfirm').value) {
        alert('비밀번호가 일치하지 않습니다.');
        document.getElementById('pwConfirm').focus();
        return false;
    }
    if (document.getElementById('birth').value.trim() === '') {
        alert('생년월일을 입력해주세요.');
        document.getElementById('birth').focus();
        return false;
    }
    if (document.getElementById('name').value.trim() === '') {
        alert('이름을 입력해주세요.');
        document.getElementById('name').focus();
        return false;
    }
    if (document.getElementById('hp').value.trim() === '') {
        alert('휴대폰번호를 입력해주세요.');
        document.getElementById('hp').focus();
        return false;
    }

    return true;
}
</script>
@include('inc.member_footer')