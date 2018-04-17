@extends('app.layouts.main')

@section('title', 'Bantuan - Teknologi OCR')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Teknologi OCR</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Apa itu OCR?</h2>
			<p>Optical Character Recognition(OCR) adalah teknologi yang berfungsi untuk mengekstrak tulisan pada gambar hasil scan menjadi teks yang dapat dimanipulasi. Dengan adanya OCR, gambar yang bertulisan tangan, tulisan mesin ketik atau teks komputer, dapat dimanipulasi. Teks hasil ekstrak dari OCR dapat dicari kata per kata atau per kalimat. Dan setiap teks dapat dimanipulasi, diganti, atau diberikan barcode.</p>
			<p>Pada KotakArsip teknologi OCR aktif saat Anda menambahkan arsip baru. Seluruh arsip di KotakArsip memiliki hasil ekstrak OCR di sistem sehingga Anda dapat melakukan pencarian secara <i>Fulltext</i> pada arsip Anda.</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Tentang KotakArsip </h4>
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