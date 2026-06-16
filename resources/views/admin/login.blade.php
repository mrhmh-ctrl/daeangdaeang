<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>댕댕닷컴 관리자 로그인</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/admin.css') }}?ver={{ time() }}" />
</head>
<body class="login">
	<div class="admin-login-wrap">
		<div class="admin-login-form">
			{{-- 에러 메시지 출력 --}}
			@if ($errors->any())
			<ul class="error-msg">
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
			</ul>
			@endif
			<h1>관리자 로그인</h1>
			<fieldset id="login">
			<form method="POST" action="{{ route('admin.login.submit') }}">
				<div class="form-group">
					<input class="login-form" type="text" name="admin_id" placeholder="관리자 아이디" required autofocus value="{{ old('admin_id') }}">
				</div>
                <div class="form-group">
				    <input class="login-form" type="password" name="admin_pw" placeholder="비밀번호" required>
		        </div>
				<button type="submit" class="btn-login">로그인</button>
			</form>
			</fieldset>
		</div>
	</div>
</body>
</html>