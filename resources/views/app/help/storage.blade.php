@extends('app.layouts.main')

@section('title', 'Bantuan - Penyimpanan Arsip')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Penyimpanan Arsip</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Penyimpanan Arsip</h2>
			<p>Untuk memudahkan Anda mencari arsip fisik, Anda dapat memasukkan data penyimpanan arsip fisik Anda pada halaman <a href="{{ route('storage') }}">Penyimpanan Arsip</a>. Data ini nantinya dapat Anda gunakan saat menginput arsip sehingga memudahkan pencarian nantinya. Berikut cara menambahkan penyimpanan arsip:</p>
			<h3>1. Pilih menu <a href="{{ route('storage') }}">Penyimpanan Arsip</a></h3>
			<div class="row">
				<div class="col-md-12">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/penyimpanan-arsip-1.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Penyimpanan Arsip</i>
					</div>
					<br>
				</div>
			</div>
			<h3>2. Kemudian klik tombol tambah dan akan tampil seperti pada gambar berikut:</h3>
			<p>Anda dapat memilih sesuai penyimpanan arsip yang Anda miliki, umumnya penyimpanan arsip berupa filling cabinet, rotary cabinet, lemari arsip, dan rak arsip.</p>
			<div class="row">
				<div class="col-md-12">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/penyimpanan-arsip-2.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Tambah Media Penyimpanan Baru</i>
					</div>
					<br>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_server') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Koneksi Server </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Surat Masuk &nbsp;<i class="fa fa-arrow-right"></i></h4>
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