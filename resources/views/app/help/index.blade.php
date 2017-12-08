@extends('app.layouts.main')

@section('title', 'Bantuan - Tentang KotakArsip')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Tentang KotakArsip</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Tentang KotakArsip</h2>
			<p>KotakArsip adalah aplikasi untuk membantu manajemen arsip pada perusahaan. KotakArsip menggunakan teknologi Optical Character Recognition(OCR) untuk mengidentifikasi tulisan dalam gambar hasil scan sehingga meningkatkan kualitas pencarian arsip nantinya.</p>
			<h3>Manfaat yang Anda dapatkan:</h3>
			<div class="row featured">
				<div class="col-md-4">
					<img src="{{ url('assets/app/img/help/manfaat-1.png') }}" alt="Arsip" width="100%">
					<h3>Digitalisasi Arsip</h3>
					<p>Digitalisasi arsip solusi pengarsipan modern. Dapatkan berbagai manfaatnya!</p>
				</div>
				<div class="col-md-4">
					<img src="{{ url('assets/app/img/help/manfaat-2.png') }}" alt="Arsip" width="100%">
					<h3>Mudah dalam pencarian</h3>
					<p>Arsip telah terdigitalisasi sehingga lebih memudahkan pencarian</p>
				</div>
				<div class="col-md-4">
					<img src="{{ url('assets/app/img/help/manfaat-3.png') }}" alt="Arsip" width="100%">
					<h3>Hemat Waktu</h3>
					<p>KotakArsip akan mengurangi waktu Anda dalam memanajemen arsip</p>
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