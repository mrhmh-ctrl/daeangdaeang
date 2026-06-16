@include('inc.member_header')
<div id="register-wrap">
  <p class="step">1/4</p>
  <h1>댕댕닷컴 이용약관에<br>동의해 주세요</h1>
  <!-- 모두 동의 -->
  <div class="all-row" id="allRow">
    <span class="all-chk" id="allChk"><i class="ti ti-check"></i></span>
    <span class="all-label">모두 동의</span>
  </div>
  <p class="desc">서비스 이용에 필수적인 최소한의 개인정보 수집 및 이용, 본인확인, 위치정보 수집 및 이용, 광고성 정보 수신(선택) 및 마케팅 정보 수신(선택)동의를 포함합니다.</p>
  <!-- 항목 리스트 -->
  <div class="item-row" onclick="toggle(0)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(필수) 서비스 이용 약관</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <div class="item-row" onclick="toggle(1)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(필수) 개인정보처리 관련 고지사항</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <div class="item-row" onclick="toggle(2)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(필수) 위치기반서비스 이용약관</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <div class="item-row" onclick="toggle(3)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(필수) 만 14세 이상</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <div class="item-row" onclick="toggle(4)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(선택) 광고성 정보 수신 동의</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <div class="item-row" onclick="toggle(5)">
    <span class="item-left">
      <span class="chk-icon"><i class="ti ti-check"></i></span>
      <span class="item-label">(선택) 맞춤형 광고 목적 개인정보 수집 및 이용</span>
    </span>
    <i class="ti ti-chevron-right chevron"></i>
  </div>
  <button class="next_btn" id="joinBtn" disabled>회원가입하기</button>
</div>
<script>
const icons = document.querySelectorAll('#register-wrap .item-row .chk-icon');
const allChk = document.getElementById('allChk');
const joinBtn = document.getElementById('joinBtn');
const required = [0, 1, 2, 3]; // 필수 항목만 (선택 항목 4,5 제외)

function toggle(i) {
  icons[i].classList.toggle('on');
  syncAll();
}

document.getElementById('allRow').onclick = function () {
  const allOn = [...icons].every(el => el.classList.contains('on'));
  icons.forEach(el => allOn ? el.classList.remove('on') : el.classList.add('on'));
  syncAll();
};

function syncAll() {
  const allOn = [...icons].every(el => el.classList.contains('on'));
  allOn ? allChk.classList.add('on') : allChk.classList.remove('on');
  const reqDone = required.every(i => icons[i].classList.contains('on'));
  joinBtn.disabled = !reqDone;
  reqDone ? joinBtn.classList.add('active') : joinBtn.classList.remove('active');
}

syncAll();

joinBtn.addEventListener('click', function () {
  location.href = 'register_joinform';
});
</script>
@include('inc.member_footer')