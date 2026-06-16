@include('inc.member_header')
<div id="register-joinform-wrap">
  <p class="step">2/4</p>
		<h1>댕댕닷컴 이용을 위해<br>아래 정보들을 입력해주세요.</h1>
		<div class="register-request-item">
			<label id="">E-MAIL</label>
			<input type="text" class="register-form" name="" value="" placeholder="">
		</div>	
		<div class="register-request-item">
		  <label>비밀번호</label>
		  <input type="password" class="register-form" name="" value="" id="pwInput" oninput="checkStrength(this.value)">
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
		  <input type="password" class="register-form" name="" value="" id="pwConfirm" oninput="checkMatch()">
		  <p class="pw-match-msg" id="pwMatchMsg"></p>
		</div>
		<div class="register-request-item">
			<label id="">생년월일</label>
			<input type="date" class="register-date" name="" value="">
		</div>
		<div class="register-request-item">
			<label id="">이름</label>
			<input type="text" class="register-form" name="" value="" placeholder="김댕댕">
		</div>	
		<div class="register-request-item">
			<label id="">휴대폰번호</label>
			<input type="text" class="register-form" name="" value="" placeholder="01012345678" maxlength="11">
		</div>	
		<button class="next_btn" id="joinBtn">추가정보입력</button>
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
joinBtn.addEventListener('click', function () {
  location.href = 'register_additionalform';
});
</script>
@include('inc.member_footer')