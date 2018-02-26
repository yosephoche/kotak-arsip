@extends('app.layouts.main')

@section('title', 'Bantuan - Hapus Surat Keluar')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Hapus Surat Keluar</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Hapus Surat Keluar</h2>
			<p>Berikut adalah cara menghapus surat keluar:</p>

			<h3>1. Klik icon titik tiga pada bagian kanan surat keluar lalu pilih <b>Hapus</b></h3>
			<div class="row">
				<div class="col-md-12 col-lg-8 col-lg-offset-2">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-keluar-hapus-1.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Surat Keluar</i>
					</div>
					<br>
				</div>
			</div>
			<h3>2. Klik tombol <b>Hapus</b></h3>
			<div class="row">
				<div class="col-md-offset-3 col-md-6 col-lg-4 col-lg-offset-4">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-keluar-hapus-2.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Modal Window Hapus Surat Keluar</i>
					</div>
					<br>
				</div>
			</div>
			<p>Data surat keluar yang telah dihapus akan disimpan pada menu <b>Sampah</b>. Anda dapat mengembalikan data yang telah dihapus semisal jika terjadi kesalahan.</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_outgoing_mail_create') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Tambah/Sunting Surat Keluar </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_file') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Berkas &nbsp;<i class="fa fa-arrow-right"></i></h4>
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