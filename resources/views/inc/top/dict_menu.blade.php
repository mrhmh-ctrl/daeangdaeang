<header>
    <ul>
        <a href="{{ url('/dogdict') }}">
            <div class="char-logo">
                <img src="{{ asset('images/daengdaeng_char.png') }}">
                <h1>애견사전</h1>
            </div>
        </a>
        <nav class="dogdict-list">
            <a href="{{ url('/dogdict/?c=dog_list') }}"><li>견종목록별</li></a>
            <a href="{{ url('/dogdict/?c=dog_training') }}"><li>강아지훈련법</li></a>
            <a href="{{ url('/dogdict/?c=first_aid') }}"><li>증상별 응급처치</li></a>
            <a href="{{ url('/dogdict/?c=medical') }}"><li>의학정보</li></a>
        </nav>
        <div class="member">			
            @if(session('member_id'))            
            <li class="login-btn"><a href="{{ route('logout') }}">로그아웃</a></li>
            @else
            <li class="login-btn"><a href="{{ url('/login') }}">로그인</a></li>
            @endif
            <!-- PC: 격자 아이콘 -->
            <div class="menu-wrap">
                <button class="menu-btn" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="2" y="2" width="9" height="9" rx="1"/>
                        <rect x="13" y="2" width="9" height="9" rx="1"/>
                        <rect x="2" y="13" width="9" height="9" rx="1"/>
                        <rect x="13" y="13" width="9" height="9" rx="1"/>
                    </svg>
                </button>
                <div class="menu-dropdown" id="menuDropdown">
                    <div class="menu-icons">
                        <a href="/dogdict/?c=dog_list">
							<div class="menu-icon-item">
								<a href=""><div class="icon"><img src="{{ asset('images/dogdict_ico.png') }}"> <h2>견종백과</h2></div></a>
								<a href=""><div class="icon"><img src="{{ asset('images/dogmarket_ico.png') }}"> <h2>애견용품</h2></div></a>
								<a href=""><div class="icon"><img src="{{ asset('images/dogshop_ico.png') }}"> <h2>업체정보</h2></div></a>
                                <a href=""><div class="icon"><img src="{{ asset('images/community_ico.png') }}"> <h2>커뮤니티</h2></div></a>
							</div>
						</a>																		
                    </div>
					<div class="menu-footer">	
						<a href="/"><li>처음으로</li></a>
					</div>						
				</div>
            </div>

            <!-- 모바일: 햄버거 버튼 -->
            <button class="dogdict-hamburger-btn" id="dogdictHamburgerBtn" aria-label="메뉴 열기">
                <span></span><span></span><span></span>
            </button>
        </div>
    </ul>
</header>
<div class="dogdict-overlay" id="dogdictOverlay" onclick="closeDogdictDrawer()"></div>

<!-- 모바일 드로어 (header 밖으로) -->
<div class="dogdict-drawer" id="dogdictDrawer">
	<a href="{{ url('/') }}">
		<h1 class="menu-logo"><img src="{{ asset('images/logo.png') }}"></h1>
	</a>
	<div class="drawer-member-btns">
    	<a href="/login" class="d-login"><i class="fas fa-sign-in-alt"></i> 로그인</a>
    	<a href="/register" class="d-reg"><i class="fa-solid fa-user"></i> 회원가입</a>
  	</div>
 
  <ul class="drawer-nav">
    <li><a href="{{ url('/dogdict') }}">견종백과 <span><i class="fa-solid fa-angle-right"></i></span></a></li>
    <li><a href="{{ url('/dogmarket') }}">애견용품 <span><i class="fa-solid fa-angle-right"></i></span></a></li>
    <li><a href="{{ url('/companyinfo') }}">업체정보 <span><i class="fa-solid fa-angle-right"></i></span></a></li>
    <li><a href="{{ url('/community') }}">커뮤니티 <span><i class="fa-solid fa-angle-right"></i></span></a></li>
  </ul>
  
  <ul class="drawer-footer">
	    <p>앱 설치 후 사용해보세요!</p>
        <a href="" class="d-app"><i class="fas fa-download"></i> 앱다운로드</a>
  </ul>
</div>

<div class="nav-mobile">
    <ul>
        <a href="{{ url('/dogdict/?c=dog_list') }}"><li>견종목록별</li></a>
        <a href="{{ url('/dogdict/?c=dog_training') }}"><li>강아지훈련법</li></a>
        <a href="{{ url('/dogdict/?c=first_aid') }}"><li>증상별 응급처치</li></a>
        <a href="{{ url('/dogdict/?c=medical') }}"><li>의학정보</li></a>
        <li>&nbsp;</li>
    </ul>
</div>

<script>
// PC 드롭다운
function toggleMenu() {
    document.getElementById('menuDropdown').classList.toggle('active');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.menu-wrap')) {
        document.getElementById('menuDropdown').classList.remove('active');
    }
});

// 모바일 드로어
const dogdictBtn = document.getElementById('dogdictHamburgerBtn');
const dogdictDrawer = document.getElementById('dogdictDrawer');
const dogdictOverlay = document.getElementById('dogdictOverlay');

dogdictBtn.addEventListener('click', function() {
    if (dogdictDrawer.classList.contains('open')) {
        closeDogdictDrawer();
    } else {
        dogdictDrawer.classList.add('open');
        dogdictOverlay.style.display = 'block';
        dogdictBtn.classList.add('open');
    }
});

function closeDogdictDrawer() {
    dogdictDrawer.classList.remove('open');
    dogdictOverlay.style.display = 'none';
    dogdictBtn.classList.remove('open');
}


</script>