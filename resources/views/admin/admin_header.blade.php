<!DOCTYPE html>
<html lang="ko">
 <head>
  <meta charset="UTF-8">
  <title>댕댕닷컴 관리자</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}?ver={{ time() }}" />
 </head>
 <body>
<div id="admin">
	<div class="admin-header">
		<div class="logo">
			<a href="{{ url('/admin/') }}"><h1>관리자</h1></a>
		</div>
		<div class="top-menu">
			<ul class="align-left">
			<a href="{{ url('/') }}"><li>MySite</li></a>
				<li>환경설정</li>			
				<li>직원게시판</li>						
			</ul>
			<ul class="align-right">
				<li></li>
				<li>로그아웃</li>
			</ul>
		</div>
	</div>
<div id="container">
	<div class="container-left">
	<nav>
		<li>직원관리</li>
		<li>회원관리</li>
	</nav>
	<div class="container-right">	
