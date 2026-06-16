@include('inc.main_header')
<div id="container">
	<div class="main-visual">
		<h1 class="pt-50 pb-20">댕댕닷컴에서 <strong>반려견 정보</strong>를 찾고계신가요?</h1>
	</div>
	<div class="main-search-wrap">
		<nav>
			<ul>
				<a href="{{ url('/dogdict') }}">
				<div class="main-menu-item">
					<li class="radius-10 dogdict"></li>
					<p class="text-center">애견사전</p>
				</div>
				</a>
				<a href="{{ url('/dogmarket') }}">
				<div class="main-menu-item">
					<li class="radius-10 dogmarket"></li>
					<p class="text-center">애견용품</p>
				</div>		
				</a>
				<a href="{{ url('/companyinfo') }}">
				<div class="main-menu-item">
					<li class="radius-10 dogshop"></li>
					<p class="text-center">업체정보</p>
				</div>
				</a>
				<a href="{{ url('/community') }}">
				<div class="main-menu-item">
					<li class="radius-10 community"></li>
					<p class="text-center">커뮤니티</p>
				</div>		
				</a>
			</ul>
		</nav>
		<div class="main-search">
			<ul>
				<fieldset>
					<legend>사이트 내 전체검색</legend>
					<form name="fsearchbox" method="get" action="" onsubmit="return fsearchbox_submit(this);">
					<input type="text" class="search-input" name="query" id="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
					<button type="submit" id="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
					</form>
				</fieldset>
			</ul>
		</div>
	</div>
</div>
@include('inc.footer')