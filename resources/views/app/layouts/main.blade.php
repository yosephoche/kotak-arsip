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

	<script>
		function refreshToken(){
			$.get('refresh-csrf').done(function(data){
				$('input[name="_token"]').val(data); // the new token
			});
		}

		setInterval(refreshToken, 300000); // every 5 min

		autocompleteSearch('{{ route("api_search_autocomplete") }}?{{ rand(11111,99999) }}&q=');

		// 3 mean 3second
		alertTimeout(3);
	</script>

</body>

</html>