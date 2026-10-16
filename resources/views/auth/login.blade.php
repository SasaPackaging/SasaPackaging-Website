{{-- admin\login.blade.php
     A stand-alone admin sign-in page: a normal form (no JavaScript and no extra CSS/JS files are needed to log in).
     It posts to /admin/login (Admin\AdminController@login). Errors come back through $errors. --}}
@php
$logo = file_exists(base_path('public/assets/main/images/sasa-logo.png')) ? 'sasa-logo.png' : 'logo.svg';
@endphp
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<title>Admin sign in | SASA Packaging</title>
	<style>
		:root { --ink:#1f2b22; --muted:#5b6b5f; --sage:#84a282; --sage-dark:#4d6b4f; --cream:#fdf9f3; --line:rgba(31,45,34,.18); }
		* { box-sizing: border-box; }
		body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px; background:var(--cream); color:var(--ink); font:16px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; }
		.card { width:100%; max-width:420px; padding:40px 34px; background:#fff; border:1px solid rgba(31,45,34,.08); border-radius:28px; box-shadow:0 24px 60px rgba(31,45,34,.10); }
		.logo { display:block; height:56px; width:auto; margin:0 auto 22px; }
		h1 { margin:0 0 6px; font-size:1.55rem; text-align:center; }
		.sub { margin:0 0 26px; color:var(--muted); font-size:.95rem; text-align:center; }
		label { display:block; margin:0 0 8px; font-weight:600; font-size:.9rem; }
		.field { margin-bottom:18px; }
		input[type=email], input[type=password], input[type=text] { width:100%; padding:14px 16px; border:1px solid var(--line); border-radius:14px; font:inherit; color:var(--ink); background:#fff; }
		input:focus { outline:none; border-color:var(--sage); box-shadow:0 0 0 4px rgba(132,162,130,.25); }
		.pw { position:relative; }
		.pw input { padding-right:76px; }
		.pw button { position:absolute; right:8px; top:50%; transform:translateY(-50%); border:0; background:none; color:var(--sage-dark); font:600 .85rem system-ui,sans-serif; cursor:pointer; padding:8px; }
		.err { margin:0 0 18px; padding:12px 14px; border-radius:12px; background:#fdecea; color:#a1261b; font-size:.92rem; }
		.btn { width:100%; padding:15px; border:0; border-radius:999px; background:linear-gradient(135deg,#93b091,var(--sage)); color:#fff; font:700 1rem system-ui,sans-serif; cursor:pointer; box-shadow:0 10px 22px rgba(132,162,130,.4); }
		.btn:hover { background:linear-gradient(135deg,var(--sage),var(--sage-dark)); }
		.foot { margin-top:22px; text-align:center; color:var(--muted); font-size:.85rem; }
	</style>
</head>
<body>
	<main class="card">
		<img class="logo" src="{{ url('/public/assets/main/images/' . $logo) }}" alt="SASA Packaging" onerror="this.style.display='none'">
		<h1>Admin sign in</h1>
		<p class="sub">Please sign in to manage the website.</p>

		@if ($errors->any())
			<div class="err" role="alert">{{ $errors->first() }}</div>
		@endif

		<form method="post" action="{{ url('/admin/login') }}">
			@csrf
			<div class="field">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
			</div>
			<div class="field">
				<label for="password">Password</label>
				<div class="pw">
					<input type="password" id="password" name="password" autocomplete="current-password" required>
					<button type="button" id="pwToggle" aria-label="Show password">Show</button>
				</div>
			</div>
			<button type="submit" class="btn">Sign in</button>
		</form>

		<p class="foot">&copy; {{ date('Y') }} SASA Packaging</p>
	</main>

	<script>
		/* optional extra: show / hide the password (the form works without this) */
		(function () {
			var b = document.getElementById('pwToggle'), i = document.getElementById('password');
			if (!b || !i) { return; }
			b.addEventListener('click', function () {
				var show = i.type === 'password';
				i.type = show ? 'text' : 'password';
				b.textContent = show ? 'Hide' : 'Show';
			});
		})();
	</script>
</body>
</html>