@extends('app.layouts.main')

@section('title', 'Bantuan - Disposisi Surat Masuk')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Disposisi Surat Masuk</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Disposisi Surat Masuk</h2>
			<p>Berikut adalah cara mendisposisi surat masuk:</p>

			<h3>1. Klik icon titik tiga pada bagian kanan surat masuk lalu pilih <b>Disposisi</b></h3>
			<div class="row">
				<div class="col-md-12 col-lg-8 col-lg-offset-2">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-masuk-disposisi-1.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Surat Masuk</i>
					</div>
					<br>
				</div>
			</div>
			<h3>2. Kemudian akan tampil modal window seperti gambar berikut</h3>
			<div class="row">
				<div class="col-md-offset-2 col-md-8 col-lg-6 col-lg-offset-3">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-masuk-disposisi-2.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Modal Window Disposisi Surat Masuk</i>
					</div>
					<br>
				</div>
			</div>

			<h3>3. Pilih rekan yang ingin didisposisi dan tambahkan pesan(opsional) tujuan disposisi</h3>
			<p style="margin-left: 20px">Rekan yang mendapatkan disposisi surat masuk hanya dapat melihat arsip tersebut.</p>

			<h3>4. Klik tombol <b>disposisi</b></h3>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail_delete') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Hapus Surat Masuk </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_outgoing_mail') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Surat Keluar &nbsp;<i class="fa fa-arrow-right"></i></h4>
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