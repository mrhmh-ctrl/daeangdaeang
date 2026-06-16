</div>
  </div>
  </div>
		<footer>
			<div class="copyright">
				Copyright ⓒ 2026 <strong>Dotcomsoft Inc.</strong> All RIghts Reserved.
			</div>
		</footer>		
		<script>
// 햄버거 메뉴
const hamburgerBtn = document.getElementById('hamburgerBtn');
const nav = document.querySelector('nav');

hamburgerBtn.addEventListener('click', function () {
    this.classList.toggle('active');
    nav.classList.toggle('open');
});

// 모바일 드롭다운
document.querySelectorAll('.has-drop > a').forEach(function (link) {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const parent = this.parentElement;
        const isOpen = parent.classList.contains('open');

        document.querySelectorAll('.has-drop').forEach(function (el) {
            el.classList.remove('open');
        });

        if (!isOpen) {
            parent.classList.add('open');
        }
    });
});

// 외부 클릭 시 닫기
document.addEventListener('click', function (e) {
    if (!hamburgerBtn.contains(e.target) && !nav.contains(e.target)) {
        hamburgerBtn.classList.remove('active');
        nav.classList.remove('open');
    }

    if (!e.target.closest('.has-drop')) {
        document.querySelectorAll('.has-drop').forEach(function (el) {
            el.classList.remove('open');
        });
    }
});

// PC 회사관리 호버 드롭다운
document.querySelectorAll('.has-dropdown').forEach(function(el) {
    el.addEventListener('mouseenter', function() {
        this.querySelector('.dropdown-menu').style.cssText = 'display:block !important; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; border-radius:4px; min-width:140px; box-shadow:0 4px 12px rgba(0,0,0,0.1); z-index:9999; padding:0px 0; width:180px;';
    });
    el.addEventListener('mouseleave', function() {
        this.querySelector('.dropdown-menu').style.cssText = 'display:none !important;';
    });
});
</script>
 </body>
</html>
