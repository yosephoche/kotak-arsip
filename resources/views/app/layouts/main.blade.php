<!doctype html>

<html lang="en">

<head>

	@include('app.layouts.partial.meta')

	<title>@yield('title', 'Kotak Arsip')</title>

	@include('app.layouts.partial.style')

	@yield('meta')

</head>


<body>
	<div class="page-loader">
		<img src="{{ asset('assets/app/img/load.gif') }}" alt="Loading...">
	</div>

	<div id="app">
		@include('app.layouts.partial.nav')

		<section class="ka-body">
			@include('app.layouts.partial.header')

			@yield('contents')

		</section>
		
		<!-- Modal -->
		@yield('modal')

	</div>

	<!-- Detail after select data in table -->
	@yield('template')
	
	<!-- Script -->
	@include('app.layouts.partial.script')
	
	@yield('registerscript')

</body>

</html>