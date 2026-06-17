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
		</div>
	</div>
</div>
</header>
