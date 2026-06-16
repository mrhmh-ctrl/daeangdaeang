@include('inc.member_header')
<div id="register-joinform-wrap">
  <p class="step">3/4</p>
		<h1>정확한 회원정보파악을 위해<br>아래 추가정보를 입력해주세요.<br>
		<span class="desc">닉네임 제외 빈항목 가능</span></h1>
		<div class="register-request-item">
			<label id="textred">* 닉네임</label>
			<input type="text" class="register-form" name="" value="" placeholder="">
		</div>	
		<div class="register-request-item">
			<label>주소</label>
			<button class="zipsearch">우편번호 찾기</button>
			<input type="text" class="register-form-zipcode" name="" value="" placeholder="" readonly>-			<input type="text" class="register-form-zipcode" name="" value="" placeholder="" readonly>
			<input type="text" class="register-form-address" name="" value="" placeholder="" readonly>
			<input type="text" class="register-form-addressdetail" name="" value="" placeholder="" readonly>
		</div>

		<div class="register-request-item">
		  <label>반려견 정보</label>
		  <select name="" class="dogselect" id="dogselect" onchange="updateDogForms()">
			<option value="0" selected>없음</option>
			<option value="1">1마리</option>
			<option value="2">2마리</option>
			<option value="3">3마리</option>
			<option value="4">4마리</option>
			<option value="5">5마리</option>
			<option value="6">6마리</option>
			<option value="7">7마리</option>
			<option value="8">8마리</option>
			<option value="9">9마리</option>
			<option value="10">10마리</option>
			<option value="11">11마리</option>
			<option value="12">12마리</option>
		  </select>
		  <p class="hint">* 키우는 강아지 마리수를 선택해주세요.</p>
		</div>

		<!-- 강아지 정보 반복 블록 (JS로 생성) -->
		<div id="dogFormsWrap"></div>
		<button class="next_btn" id="joinBtn">회원가입완료</button>
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
function updateDogForms() {
  const count = parseInt(document.getElementById('dogselect').value);
  const wrap = document.getElementById('dogFormsWrap');
  wrap.innerHTML = '';

  if (count === 0) return; // 없음 → display:none 효과

  for (let i = 1; i <= count; i++) {
    wrap.innerHTML += `
      <div class="dog-form-group">
        <p class="dog-title">#${i} 강아지 정보</p>
        <div class="register-request-item">
          <label>#${i} 견종</label>
          <input type="text" class="register-form" name="dog_breed_${i}" placeholder="견종을 입력하세요">
        </div>
        <div class="register-request-item">
          <label>#${i} 강아지 이름</label>
          <input type="text" class="register-form" name="dog_name_${i}" placeholder="이름을 입력하세요">
        </div>
        <div class="register-request-item">
          <label>#${i} 강아지 성별</label>
          <select class="register-form-select" name="dog_gender_${i}">
            <option value="">선택</option>
            <option value="M">수컷</option>
            <option value="F">암컷</option>
          </select>
        </div>
        <div class="register-request-item">
          <label>#${i} 강아지 생년월일</label>
          <input type="text" class="register-form" name="dog_birth_${i}" placeholder="YYYY-MM-DD" maxlength="10">
        </div>
      </div>
    `;
  }
}

// 페이지 로드 시 초기화
updateDogForms();

joinBtn.addEventListener('click', function () {
  location.href = 'register_complete';
});
</script>
@include('inc.member_footer')