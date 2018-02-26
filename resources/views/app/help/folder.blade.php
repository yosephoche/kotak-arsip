@extends('app.layouts.main')

@section('title', 'Bantuan - Folder')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Folder</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Folder</h2>
			<p>Fitur <b>Folder</b> digunakan untuk mengelompokkan arsip-arsip meskipun berbeda jenis agar memudahkan Anda dalam mencari arsip yang saling berkaitan satu sama lain.</p>
			<div class="row">
				<div class="col-md-offset-1 col-md-10 col-lg-6 col-lg-offset-3">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/folder.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Folder</i>
					</div>
					<br>
				</div>
			</div>
			<p>* Folder diinput saat Anda menambahkan/menyunting arsip</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_share') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Bagikan </h4>
					</a>
				</div>
			</div>
		</div>
	</div>

	<aside class="ka-sidebar-detail">
		<div class="detail-info">
			<div class="select no-border" style="height: calc(100vh - 70px);">
				@include('app.help.aside')
			</div>
		</div>
	</aside>
@endsection