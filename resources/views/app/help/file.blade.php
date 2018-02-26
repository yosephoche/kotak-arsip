@extends('app.layouts.main')

@section('title', 'Bantuan - Berkas')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Berkas</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Berkas</h2>
			<p>Berkas adalah fitur dimana Anda dapat mengarsipkan file-file perusahaan yang sifatnya dapat diedit untuk keperluan arsip kedepannya. Sebagai contoh file format surat, format proposal, format laporan, dll</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_outgoing_mail_delete') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Hapus Surat Keluar</h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_archives') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Jenis Arsip Lainnya &nbsp;<i class="fa fa-arrow-right"></i></h4>
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