@extends('app.layouts.main')

@section('title', 'Bantuan - Koneksi Server')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Koneksi Server</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Koneksi Server</h2>
			<p>Setelah server KotakArsip aktif, Anda dapat membuka KotakArsip di perangkat-perangkat lainnya yang terhubung 1 jaringan dengan server. Untuk mengakses KotakArsip di perangkat lain seperti laptop/smartphone Anda, Anda cukup mengakses IP dihalaman <a href="{{ route('setting') }}">pengaturan</a> pada browser Anda.</p>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/koneksi-server.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Pengaturan > Koneksi Server</i>
					</div>
					<br>
					<br>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_ocr') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Teknologi OCR </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_storage') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Penyimpanan Arsip &nbsp;<i class="fa fa-arrow-right"></i></h4>
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