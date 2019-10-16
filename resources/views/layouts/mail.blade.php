<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>{{ env('PROJECT_NAME', 'QUEDYPROJECT') }} - @yield('title')</title>
</head>
<body>
<font style="font-family:Arial; font-size: 12px">
	@yield('content')
</font>


@include('mails.partials.footer')
</body>
</html>
