@extends('app.layouts.main')

@section('title', 'Bantuan - Jenis Arsip Lainnya')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Jenis Arsip Lainnya</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Jenis Arsip Lainnya</h2>
			<p>Dengan fitur ini Anda dapat membuat format arsip selain surat masuk dan surat keluar. Caranya klik tombol <b>+ Jenis Arsip</b> pada menu.</p>
			<div class="row">
				<div class="col-md-offset-4 col-md-4">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/jenis-arsip-1.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Menu Navigasi</i>
					</div>
					<br>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_outgoing_mail_delete') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Hapus Surat Keluar</h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_search') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Pencarian &nbsp;<i class="fa fa-arrow-right"></i></h4>
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