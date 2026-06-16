@include('lib.daeangdaeang')
@include('main_header')
<div class="main-wrap">
	<div class="main-visual">
		<h1 class="pt-50 pb-20">댕댕닷컴에서 <strong>반려견 정보</strong>를 찾고계신가요?</h1>
	</div>
	<div class="main-search-wrap">
		<nav>
			<ul>
				<div class="main-menu-item">
					<li class="radius-10"></li>
					<p class="text-center">견종백과</p>
				</div>
				<div class="main-menu-item">
					<li class="radius-10"></li>
					<p class="text-center">동네업체</p>
				</div>
				<div class="main-menu-item">
					<li class="radius-10"></li>
					<p class="text-center">생활정보</p>
				</div>
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
			<p class="">인기 검색어 : </p>
		</div>
	</div>
</div>
@include('footer')