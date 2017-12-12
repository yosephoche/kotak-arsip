@extends('app.layouts.main')

@section('title', 'Bantuan - Pencarian')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Pencarian</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Pencarian</h2>
			<p>Anda dapat melakukan pencarian langsung pada bagian atas aplikasi berdasarkan subyek arsip seperti asal surat pada surat masuk dan tujuan surat pada surat keluar.</p>
			<div class="row">
				<div class="col-md-12">
					<img style="border-radius: 5px" class="img" src="{{ url('assets/app/img/help/pencarian-1.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Pencarian</i>
					</div>
					<br>
				</div>
			</div>
			<br>
			<p>Setelah Anda memasukkan kata kunci pencarian, silahkan tekan <b>Enter</b> dan Anda akan diarahkan pada halaman <a href="{{ route('search', ['q' => '']) }}">Pencarian</a> kemudian Anda akan melihat daftar arsip yang sesuai dengan kata kunci Anda.</p>
			<div class="row">
				<div class="col-md-offset-1 col-md-10">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/pencarian-2.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Halaman Pencarian</i>
					</div>
					<br>
				</div>
			</div>

			<br>

			<h2>Filter Pencarian</h2>
			<p>Pada halaman pencarian Anda akan melihat tombol filter, fungsinya adalah untuk lebih memperkuat kualitas pencarian Anda.</p>
			<div class="row">
				<div class="col-md-offset-4 col-md-4">
					<br>
					<div class="header-browser">
						<div class="red"></div>
						<div class="yellow"></div>
						<div class="green"></div>
					</div>
					<img class="img" src="{{ url('assets/app/img/help/pencarian-3.png') }}" alt=""><br>
					<br>
					<div class="text-center">
						<i class="img-caption">Filter Pencarian</i>
					</div>
					<br>
				</div>
			</div>
			<ul>
				<li><b>Full text</b> adalah filter dimana Anda dapat mencari arsip berdasarkan kontennya langsung. Hal ini dapat dilakukan berkat teknologi OCR yang mampu mendeteksi tulisan yang ada pada gambar.</li>
				<li><b>Jenis Arsip</b> adalah filter untuk mencari arsip berdasarkan jenisnya seperti surat masuk dan surat keluar.</li>
				<li><b>Rentang Waktu Diarsipkan</b> untuk menemukan arsip yang disimpan pada rentang waktu tertentu.</li>
				<li><b>Penyimpanan Arsip</b> untuk melihat arsip apa saja yang disimpan pada penyimpanan fisik Anda.</li>
			</ul>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_archives') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Jenis Arsip Lainnya </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_share') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Bagikan &nbsp;<i class="fa fa-arrow-right"></i></h4>
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