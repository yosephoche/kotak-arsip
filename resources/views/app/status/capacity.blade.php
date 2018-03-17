@extends('app.layouts.main')

@section('title', 'Kapasitas')

@section('contents')
	<div class="ka-main">		
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="dashboard.html">Kapasitas</a></li>
			</ul>
		</div>

		<div class="row">
			<div class="col-lg-4 col-md-6">
				<div class="tile no-margin">
					<a href="#" class="add-storage">+<span>Tambah Kapasitas</span></a>
					<div class="tile-label">Total Kapasitas</div>
					<div class="tile-value">20 GB</div>
					<br>
					<div class="tile-info">Terpakai <b>{{ $size[0].' '.$size[1] }}</b> dari 20 GB</div>
					<div class="tile-progress" style="width: {{ $percentage }}%"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				Detail kapasitas berdasarkan jenis arsip
				<br>
				<br>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="tile">
					<div class="tile-label">Surat Masuk</div>
					<div class="tile-value">{{ $size_incoming_mail[0].' '.$size_incoming_mail[1] }}</div>
					<div class="tile-progress" style="width: {{ $percentage_incoming_mail }}%"></div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6">
				<div class="tile">
					<div class="tile-label">Surat Keluar</div>
					<div class="tile-value">{{ $size_outgoing_mail[0].' '.$size_outgoing_mail[1] }}</div>
					<div class="tile-progress" style="width: {{ $percentage_outgoing_mail }}%"></div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6">
				<div class="tile">
					<div class="tile-label">Arsip Kepegawaian</div>
					<div class="tile-value">{{ $size_employee[0].' '.$size_employee[1] }}</div>
					<div class="tile-progress" style="width: {{ $percentage_employee }}%"></div>
				</div>
			</div>

			<div class="col-md-4 col-sm-6">
				<div class="tile">
					<div class="tile-label">Berkas</div>
					<div class="tile-value">{{ $size_file[0].' '.$size_file[1] }}</div>
					<div class="tile-progress" style="width: {{ $percentage_file }}%"></div>
				</div>
			</div>
		</div>
	</div>
@endsection