<!DOCTYPE html>
<html lang="ko">
<head>
@include('inc.function.head')

</head>
<body>
<header>
<div class="header-wrap">
	<div class="top-menu">
		<a href="{{ url('/') }}">
			<h1 class="logo"><img src="{{ asset('images/logo.png') }}"></h1>
		</a>
		<div class="member-menu">
		@if(session('member_id'))
        <a href="{{ url('/mypage') }}"><li class="black login"><i class="fas fa-user"></i> {{ session('member_nick') }} 환영합니다.</li></a>
        <a href="{{ route('logout') }}"><li class="black login"><i class="fas fa-sign-in-alt"></i> 로그아웃</li></a>
    	@else
        <a href="{{ url('/login') }}"><li class="black login"><i class="fas fa-sign-in-alt"></i> 로그인</li></a>
        <a href="{{ url('/register') }}"><li class="register bg-gold radius-5"><i class="fa-solid fa-user"></i> 회원가입</li></a>
    	@endif
    	<a href="{{ url('') }}"><li class="register bg-gold radius-5"><i class="fas fa-download"></i> 앱다운로드</li></a>
<!--			<li class="bg-white"><a href="{{ url('/market/cart') }}">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="35" height="35" fill="none">
				<path
					d="M5 8h14l-1.5 10.5a1.5 1.5 0 0 1-1.5 1.5h-9a1.5 1.5 0 0 1-1.5-1.5L5 8z"
					stroke="#333333"
					stroke-width="0.9"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
				<path
					d="M9 8V6a3 3 0 0 1 6 0v2"
					stroke="#333333"
					stroke-width="0.9"
					stroke-linecap="round"
					stroke-linejoin="round"
				/>
				</svg>
			</a></li>-->
		</div>
		<!-- 햄버거 버튼 (모바일) -->
      <button class="hamburger-btn" id="hamburgerBtn" aria-label="메뉴 열기">
        <span></span><span></span><span></span>
      </button>
	</div>
</div>
</header>
<!-- 오버레이 -->
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
 
<!-- 모바일 드로어 -->
<div class="mobile-drawer" id="mobileDrawer">
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

<div id="container">
