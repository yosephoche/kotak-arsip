@extends('app.layouts.main')

@section('title', 'Bantuan - Surat Masuk')

@section('contents')

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li>Surat Masuk</li>
			</ul>
		</div>

		<div class="help-content">
			<hr>
			<h2>Surat Masuk</h2>
			<p>Dengan kotakarsip Anda dapat dengan mudah memanajemen arsip surat masuk pada perusahaan Anda. Pada fitur ini Anda juga dapat melakukan disposisi surat kepada yang bersangkutan dan juga dapat melihat riwayat disposisi tersebut.</p>

			<div class="row">
				<div class="col-xs-6">
					<a href="{{ route('help_storage') }}" class="next-help pull-left">
						<span>Sebelumnya</span>
						<h4><i class="fa fa-arrow-left"></i>&nbsp; Penyimpanan Arsip </h4>
					</a>
				</div>
				<div class="col-xs-6">
					<a href="{{ route('help_incoming_mail_create') }}" class="next-help">
						<span>Selanjutnya</span>
						<h4>Tambah/Sunting Surat Masuk &nbsp;<i class="fa fa-arrow-right"></i></h4>
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