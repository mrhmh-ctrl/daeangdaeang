</div>
<footer>
	<div class="footer-container">
		<div class="footer-company-info">
		</div>	
	</div>
	<div class="footer-menu">
		<a href="{{ url('/notice') }}"><li>공지사항</li></a>
		<a href="{{ url('/policy/terms') }}"><li>이용약관</li></a>
		<a href="{{ url('/policy/privacy') }}"><li>개인정보처리방침</li></a>
		<a href="{{ url('/policy/location') }}"><li>위치기반서비스 이용약관</li></a>
		<a href="{{ url('/policy/youth') }}"><li>청소년보호정책</li></a>
		<a href="{{ url('/policy/email') }}"><li>이메일무단수집거부</li></a>
		<a href="{{ url('/customer') }}"><li>고객센터</li></a>
	</div>
</footer>
<script>
const drawer = document.getElementById('mobileDrawer');
const overlay = document.getElementById('drawerOverlay');
const btn = document.getElementById('hamburgerBtn');

btn.addEventListener('click', function () {
  if (drawer.classList.contains('open')) {
    closeDrawer();
  } else {
    openDrawer();
  }
});

function openDrawer() {
  drawer.classList.add('open');
  overlay.style.display = 'block';
  btn.classList.add('open');      /* 햄버거 → X */
}

function closeDrawer() {
  drawer.classList.remove('open');
  overlay.style.display = 'none';
  btn.classList.remove('open');   /* X → 햄버거 */
}
</script>
</body>
</html>
