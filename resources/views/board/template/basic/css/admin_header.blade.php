<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>댕댕닷컴 관리자</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}?ver=<?php echo time(); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" 
  href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/variable/pretendardvariable.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- css -->
</head>
<body>
<div id="wrap">
<div class="admin-header" id="device-pc">
	<div class="logo">
		<a href="{{ url('/admin/') }}"><h1>관리자</h1></a>
	</div>
	<div class="top-menu">
		<ul class="align-left">
			<li><a href="{{ url('/admin/config') }}">환경설정</a></li>			
			<li><a href="{{ url('/admin/member') }}">회원관리</a></li>
			<li><a href="{{ url('/admin/company_manage') }}">회사관리</a></li>
			<li><a href="{{ url('/admin/goods_manage') }}">상품관리</a></li>			
			<li><a href="{{ url('/admin/manager_board') }}">직원게시판</a></li>
		</ul>    
		<ul class="align-right">
			<li class="home"><a href="{{ url('/') }}" target="_blank">HOME</li></a>
			<a href="{{ url('/admin/manager_register') }}"><li>직원등록</li></a>
			<li class="logout"><a href="{{ route('admin.logout') }}">로그아웃</a></li>
		</ul>
	</div>
</div>
<div class="admin-header-mobile" id="device-mobile">
    <a href="{{ url('/admin/') }}"><h1 class="logo">관리자</h1></a>
    <div class="hamburger-menu" id="hamburgerBtn">
        <span></span>
        <span></span>
        <span></span>
    </div>
	<nav id="device-mobile">
    <ul>
		<div class="mobile-top-menu">
			<a href="{{ url('/') }}"><span>HOME</span></a>
			<a href="{{ route('admin.logout') }}"><span>로그아웃</span></a>
		</div>
        <li class="has-drop">
            <a href="#">회사관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">직원등록</a></li>
                <li><a href="#">조직도관리</a></li>
                <li><a href="#">직급관리</a></li>
                <li><a href="#">직원게시판</a></li>
            </ul>
        </li>	
		<li class="has-drop">
            <a href="{{ url('/admin/member') }}">회원관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">회원보기</a></li>
                <li><a href="#">회원등록</a></li>
                <li><a href="#">회원등급관리</a></li>
            </ul>
        </li>	
        <li class="has-drop">
            <a href="#">지역별 전체게시물 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">게시물보기</a></li>
            </ul>
        </li>
        <li class="has-drop">
            <a href="#">모임관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">모임목록</a></li>
            </ul>
        </li>
        <li class="has-drop">
            <a href="#">업체관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">업체목록</a></li>
                <li><a href="#">업체등록</a></li>
            </ul>
        </li>
		<li class="has-drop">
			<a href="#">애견백과관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
			<ul class="dropdown">
				<li><a href="{{ route('admin.dogdict.index') }}">백과목록</a></li>
				<li><a href="{{ route('admin.dogdict.create') }}">백과등록</a></li>
			</ul>
		</li>
        <li><a href="#">상품관리 <span class="arrow"><i class="fas fa-chevron-right"></i></span></a></li>
        <li class="has-drop">
            <a href="{{ route('admin.board.index') }}">게시판관리 <span class="arrow"><i class="fas fa-chevron-down"></i></span></a>
            <ul class="dropdown">
                <li><a href="#">게시판목록</a></li>
                <li><a href="#">게시판등록</a></li>
            </ul>
        </li>
        <li><a href="#">페이지관리 <span class="arrow"><i class="fas fa-chevron-right"></i></span></a></li>
        <li><a href="#">접속기록확인 <span class="arrow"><i class="fas fa-chevron-right"></i></span></a></li>
        <li><a href="#">통계확인 <span class="arrow"><i class="fas fa-chevron-right"></i></span></a></li>
    </ul>
	</nav>
</div>
<div id="container">
	<div class="container-left">
	<nav id="device-pc">
		<li>지역별 전체게시물</li>
		<li>모임관리</li>		
		<li>업체관리</li>		
		<li><a href="{{ route('admin.dogdict.index') }}">견종백과관리</a></li>
		<li>등록반려견현황</li>
        <li><a href="{{ route('admin.board.index') }}">게시판관리</a></li>
        <li>페이지관리</li>
		<li>팝업관리</li>
		<li>접속기록확인</li>
		<li>통계확인</li>
	</nav>
	<div class="container-right">	
