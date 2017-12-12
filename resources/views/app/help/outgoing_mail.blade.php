@extends('app.layouts.main')

@section('title', 'Bantuan - Surat Keluar')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Surat Keluar</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Surat Keluar</h2>
			<p>Dengan kotakarsip Anda dapat dengan mudah memanajemen arsip surat keluar pada perusahaan Anda. Dengan fitur ini masalah-masalah seperti lupa nomor surat keluar terakhir tidak akan terjadi lagi.</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail_disposition') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Disposisi Surat Masuk </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_outgoing_mail_create') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Tambah/Sunting Surat Keluar &nbsp;<i class="fa fa-arrow-right"></i></h4>
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