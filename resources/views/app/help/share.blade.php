@extends('app.layouts.main')

@section('title', 'Bantuan - Bagikan')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Bagikan</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Bagikan</h2>
			<p>Seluruh arsip yang didisposisi/dibagikan bersama Anda dapat dilihat di menu masing-masing berdasarkan jenis arsipnya. Berikut adalah cara membagikan arsip(khusus untuk surat masuk istilah yang digunakan adalah disposisi):</p>

			<h3>1. Klik icon titik tiga pada bagian kanan surat masuk lalu pilih <b>Bagikan</b></h3>
			<div class="row">
				<div class="col-md-12 col-lg-8 col-lg-offset-2">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/bagikan-2.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Arsip yang akan dibagikan</i>
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
					<img class="img" src="{{ url('assets/app/img/help/bagikan-3.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Modal Window Bagikan</i>
					</div>
					<br>
				</div>
			</div>

			<h3>3. Pilih rekan yang ingin dibagikan dan tambahkan pesan(opsional) tujuan dibagikan</h3>
			<p style="margin-left: 20px">Rekan yang dibagikan hanya dapat melihat arsip tersebut.</p>

			<h3>4. Klik tombol <b>Bagikan</b></h3>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_search') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Pencarian</h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_folder') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Folder &nbsp;<i class="fa fa-arrow-right"></i></h4>
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