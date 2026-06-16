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
		    <a href="{{ url('/login') }}"><li class="black login"><i class="fas fa-sign-in-alt"></i> 로그인</li></a>
			<a href="{{ url('/register') }}"><li class="register bg-gold radius-5"><i class="fa-solid fa-user"></i> 회원가입</li></a>
			<a href="{{ url('') }}"><li class="register bg-gold radius-5"><i class="fas fa-download"></i> 앱다운로드</li></a>
			<li class="bg-white"><a href="{{ url('/market/cart') }}">
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
			</a></li>
		</div>
	</div>
</div>
</header>
