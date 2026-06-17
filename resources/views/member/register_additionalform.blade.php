@include('inc.member_header')
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<div id="register-joinform-wrap">
    <p class="step">3/4</p>
    <h1>정확한 회원정보파악을 위해<br>아래 추가정보를 입력해주세요.<br>
    <span class="desc">닉네임 제외 빈항목 가능</span></h1>

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color:#f44336; font-size:13px;">{{ $error }}</p>
        @endforeach
    @endif

    <form action="{{ route('register.store') }}" method="POST" id="additionalForm" onsubmit="return validateAdditionalForm()">
    @csrf

        <div class="register-request-item">
            <label id="textred">* 닉네임</label>
            <input type="text" class="register-form" name="nick" id="nick" value="{{ old('nick') }}" placeholder="닉네임 입력">
        </div>

        <div class="register-request-item">
            <label>주소</label>
            <button type="button" class="zipsearch" onclick="execDaumPostcode()">우편번호 찾기</button>
            <div id="daumPostcodeLayer" style="display:none; width:100%; border:1px solid #ddd; margin-top:8px;"></div>
            <input type="text" class="register-form-zipcode" name="zip" id="zip" value="" placeholder="우편번호" readonly>
            <input type="text" class="register-form-address" name="addr1" id="addr1" value="" placeholder="기본주소" readonly>
            <input type="text" class="register-form-addressdetail" name="addr2" id="addr2" value="" placeholder="상세주소 입력">
        </div>

        <div class="register-request-item">
            <label>반려견 정보</label>
            <select name="dog_count" class="dogselect" id="dogselect" onchange="updateDogForms()">
                <option value="0" selected>없음</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ $i }}마리</option>
                @endfor
            </select>
            <p class="hint">* 키우는 강아지 마리수를 선택해주세요.</p>
        </div>

        <div id="dogFormsWrap"></div>

        <button type="submit" class="next_btn">회원가입완료</button>
    </form>
</div>

<script>
function execDaumPostcode() {
    const layer = document.getElementById('daumPostcodeLayer');
    layer.style.display = 'block';
    new daum.Postcode({
        oncomplete: function(data) {
            document.getElementById('zip').value   = data.zonecode;
            document.getElementById('addr1').value = data.roadAddress;
            layer.style.display = 'none';
        },
        width: '100%',
        height: '400px'
    }).embed(layer);
}

function updateDogForms() {
    const count = parseInt(document.getElementById('dogselect').value);
    const wrap = document.getElementById('dogFormsWrap');
    wrap.innerHTML = '';
    if (count === 0) return;
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
                    <input type="date" class="register-form" name="dog_birth_${i}" placeholder="YYYY-MM-DD" maxlength="10">
                </div>
            </div>
        `;
    }
}
updateDogForms();

function validateAdditionalForm() {
    if (document.getElementById('nick').value.trim() === '') {
        alert('닉네임을 입력해주세요.');
        document.getElementById('nick').focus();
        return false;
    }
    return true;
}
</script>
@include('inc.member_footer')