<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'POS Accounts') | TFA7</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f1f5f9;color:#172033;font:16px/1.5 system-ui,sans-serif}header{background:#14253e;color:white;padding:20px 5%}header strong{font-size:22px}nav{display:flex;gap:24px;margin-top:10px}nav a{color:white}main{max-width:1000px;margin:32px auto;padding:0 20px}h1{font-size:28px}a{color:#0756a1}button,.button{display:inline-block;background:#0756a1;color:white;border:0;border-radius:6px;padding:10px 16px;text-decoration:none;cursor:pointer;font:inherit}.panel{background:white;padding:24px;border:1px solid #d4dce7;border-radius:10px}form.panel{max-width:650px}label{display:block;font-weight:600;margin-top:18px}input{display:block;width:100%;padding:10px;border:1px solid #8e9aad;border-radius:5px;font:inherit;margin-top:6px}input[aria-invalid=true]{border-color:#b42318}.error{color:#a01c12;font-size:14px}.alert{padding:14px 18px;margin-bottom:20px;border-radius:6px;background:#e2f3e8}.alert.error{background:#fff0ee}.help{color:#526073;font-size:14px}.actions{display:flex;gap:20px;align-items:center;margin-top:24px}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:13px;border-bottom:1px solid #dce3eb}th{background:#eef3f8}img.avatar{width:56px;height:56px;border-radius:50%;object-fit:cover}.pagination{display:flex;gap:20px;margin:20px 0}.heading{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap}
    </style>
</head>
<body>
<header><strong>Laravel POS</strong><nav aria-label="Accounts"><a href="{{ route('home') }}">Tasks home</a><a href="{{ route('tfa7.customers.index') }}">Customers</a><a href="{{ route('tfa7.users.index') }}">User accounts</a></nav></header>
<main>
    @if (session('success'))<div class="alert" role="status">{{ session('success') }}</div>@endif
    @if ($errors->any())
        <div class="alert error" role="alert"><strong>Please correct the following:</strong><ul>
        @foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach
        </ul></div>
    @endif
    @yield('content')
</main>
</body>
</html>
