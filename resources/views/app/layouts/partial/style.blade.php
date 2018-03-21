<?php
	Cookie::queue('favicon', asset('assets/app/img/favicon.png'));
	Cookie::queue('font_awesome', asset('assets/app/libs/font-icons/font-awesome/css/font-awesome.min.css'));
	Cookie::queue('vue', asset('assets/app/css/bootstrap-vue.css'));
	Cookie::queue('main_css', asset('assets/app/css/app.min.css'));
?>
<link rel="icon" sizes="16x16" href="{{ !empty(Cookie::get('favicon')) ? Cookie::get('favicon') : asset('assets/app/img/favicon.png') }}" />

<!-- Font Icon -->
<link rel="stylesheet" href="{{ !empty(Cookie::get('font_awesome')) ? Cookie::get('font_awesome') : asset('assets/app/libs/font-icons/font-awesome/css/font-awesome.min.css') }}">

<!-- Bootstrap Vue -->
<link type="text/css" rel="stylesheet" href="{{ !empty(Cookie::get('vue')) ? Cookie::get('vue') : asset('assets/app/css/bootstrap-vue.css') }}"/>

<!-- Custom css -->
<link href="{{ !empty(Cookie::get('main_css')) ? Cookie::get('main_css') : asset('assets/app/css/app.min.css') }}" rel="stylesheet">