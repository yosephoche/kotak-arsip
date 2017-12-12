@extends('app.layouts.main')

@section('title', 'Bantuan - Tambah/Sunting Surat Masuk')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Tambah/Sunting Surat Masuk</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Tambah Surat Masuk</h2>
			<p>Berikut adalah cara menambahkan surat masuk:</p>

			<h3>1. Pilih menu <a href="{{ route('incoming_mail') }}">Surat Masuk</a></h3>

			<h3>2. Klik tombol tambah untuk mengupload file surat masuk</h3>
			<p style="margin-left: 20px">Pada tahap ini Anda hanya diminta mengunggah 1 file gambar atau pdf dari surat masuk untuk keperluan OCR agar gambar dapat diubah menjadi teks.</p>

			<h3>3. Teks pada gambar akan masuk otomatis pada kolom isian sesuai label masing-masing</h3>
			<p style="margin-left: 20px">Jika teks pada gambar tidak terbaca maka isian akan kosong dan dapat diinput secara manual. Selain itu Anda dapat menambahkan beberapa info setelahnya seperti penyimpanan fisik arsip, <a href="{{ route('help_folder') }}">folder</a>, dan keterangan.</p>
			<div class="row">
				<div class="col-md-12">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-masuk-tambah.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Tambah Surat Masuk</i>
					</div>
					<br>
				</div>
			</div>

			<h3>4. Klik tombol <b>simpan</b></h3>

			<br>
			<br>

			<h2>Sunting Surat Masuk</h2>
			<p>Berikut adalah cara menyunting data surat masuk:</p>

			<h3>1. Klik icon titik tiga pada bagian kanan surat masuk lalu pilih <b>Sunting</b></h3>
			<div class="row">
				<div class="col-md-12">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/surat-masuk-sunting.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Surat Masuk</i>
					</div>
					<br>
				</div>
			</div>
			<h3>2. Kemudian ubah data yang Anda inginkan</h3>
			<h3>3. Klik tombol <b>simpan</b></h3>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Surat Masuk </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail_delete') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Hapus Surat Masuk &nbsp;<i class="fa fa-arrow-right"></i></h4>
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