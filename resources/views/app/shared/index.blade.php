@extends('app.layouts.main')

@section('title', 'Surat Masuk')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascFrom = 'true';
		if (@$_GET['sort'] == 'from' AND @$_GET['asc'] == 'true') {
			$ascFrom = 'false';
		}

		$ascSubject = 'true';
		if (@$_GET['sort'] == 'subject' AND @$_GET['asc'] == 'true') {
			$ascSubject = 'false';
		}

		$type = "";
		$key = "";
		$disposition = "";
		if (Request::is('berbagi/surat/masuk*')) {
			$type = "Masuk";
			$key = "incoming";
			$disposition = "Disposisi";
			$key_disposition = "disposition";
		} elseif (Request::is('berbagi/surat/keluar*')) {
			$type = "Keluar";
			$key = "outgoing";
			$disposition = "Bagikan";
			$key_disposition = "shared";
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('shared_incoming_mail') }}">Berbagi</a></li>
			</ul>
		</div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" {{ Request::is('berbagi/surat/masuk*') ? 'class=active' : '' }}><a href="{{ route('shared_incoming_mail') }}">Surat Masuk</a></li>
			<li role="presentation" {{ Request::is('berbagi/surat/keluar*') ? 'class=active' : '' }}><a href="{{ route('shared_outgoing_mail') }}">Surat Keluar</a></li>
		</ul>

		<p>Saat seseorang berbagi arsip dengan Anda, Anda hanya diberi akses melihat arsip tersebut.</p>

		<hr>

		<table class="table table-hover" v-if="json.{{ $key }}Mail != ''">
			<tr>
				<th class="{{ @$_GET['sort'] == 'from' ? 'sort' : '' }}">
					<a href="{{ route('shared_incoming_mail', ['sort' => 'from', 'asc' => $ascFrom]) }}">Asal Surat</a>
					@if (@$_GET['sort'] == 'from')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th class="{{ @$_GET['sort'] == 'subject' ? 'sort' : '' }}">
					<a href="{{ route('shared_incoming_mail', ['sort' => 'subject', 'asc' => $ascSubject]) }}">Perihal</a>
					@if (@$_GET['sort'] == 'subject')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th class="view-tablet-only">Disposisi</th>
				<th class="view-tablet-only" width="120px">Tanggal Masuk</th>
				<th class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-ul"></i></a>
					<ul class="dropdown-menu pull-right">
						<li class="label-dropdown">Mode Tampilan</li>
						<li><a href="detail.html">List <i class="fa fa-list-ul pull-right"></i></a></li>
						<li><a href="#">Grid <i class="fa fa-th-large pull-right"></i></a></li>
					</ul>
				</th>
			</tr>

			@if (Request::is('berbagi/surat/masuk*'))
				<tr class="item" v-for="val in json.incomingMail" v-on:click="detailSidebar(val, $event)">
					<td><a v-bind:href="'{{ route('shared_incoming_mail_detail') }}/' + val._id" v-html="val.from"></a></td>
					<td v-html="val.subject"></td>
					<td class="view-tablet-only" v-if="val.share != ''" width="150px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index < 3 && disposisi.user[0] != null">
								<b-tooltip v-bind:content="disposisi.user[0].name" placement="bottom">
									<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + disposisi.user[0].photo + ')' }" v-if="disposisi.user[0].photo != ''"></div>
									<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.svg)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index == 0 && val.share.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.share.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a v-bind:href="'{{ route('shared_incoming_mail_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus Akses Saya</a></li>
						</ul>
					</td>
				</tr>
			@elseif (Request::is('berbagi/surat/keluar*'))
				<tr class="item" v-for="val in json.outgoingMail" v-on:click="detailSidebar(val, $event)">
					<td><a v-bind:href="'{{ route('shared_incoming_mail_detail') }}/' + val._id" v-html="val.to"></a></td>
					<td v-html="val.subject"></td>
					<td class="view-tablet-only" v-if="val.share != ''" width="150px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index < 3 && disposisi.user[0] != null">
								<b-tooltip v-bind:content="disposisi.user[0].name" placement="bottom">
									<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/' + disposisi.user[0].photo + ')' }" v-if="disposisi.user[0].photo != ''"></div>
									<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.svg)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index == 0 && val.share.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.share.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a v-bind:href="'{{ route('shared_outgoing_mail_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus Akses Saya</a></li>
						</ul>
					</td>
				</tr>
			@endif

		</table>

		<div class="text-center" v-else>
			<img src="{{ url('assets/app/img/icons') }}/no_file.svg" alt="" width="400px">
			<br>
			<br>
			<br>
			Belum ada data Surat {{ $type }}
		</div>
	</div>

	<aside class="ka-sidebar-detail">

		<div class="detail-info">

			<div v-if="detail !== ''">
				<detail :detail="detail"></detail>
			</div>
			<div v-else>
				<no-select></no-select>
			</div>

		</div>
	</aside>		
@endsection

@section('modal')
	<!-- Modals -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('shared_'.$key.'_mail_delete') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus Akses</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus akses Anda ke data ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('template')
	<!-- No select data in table -->
	<template id="no-select">
		<div class="no-select text-center">
			<img src="{{ asset('assets/app/img/icons/select_file.svg') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select no-border" style="height: calc(100vh - 160px); padding-top: 0">
				<h5 class="no-margin">Pratinjau</h5>
				<hr>

				<div v-for="message in detail.share" v-if="message.user[0]._id.$oid == '{{ Auth::user()->id }}'">
					<div class="item item-highlight" v-if="message.message != null">
						<label>Pesan {{ $disposition }}</label>
						<div class="value" v-html="message.message"></div>
					</div>
				</div>
				<div class="item" v-if="detail.id_user">
					<label>{{ $disposition }} dari</label>
					<div class="value" v-html="detail.userDetail[0]"></div>
				</div>
				@if (Request::is('berbagi/surat/masuk*'))
				<div class="item" v-if="detail.from">
					<label>Asal Surat</label>
					<div class="value" v-html="detail.from"></div>
				</div>
				@elseif (Request::is('berbagi/surat/keluar*'))
				<div class="item" v-if="detail.to">
					<label>Tujuan Surat</label>
					<div class="value" v-html="detail.to"></div>
				</div>
				@endif
				<div class="item" v-if="detail.reference_number">
					<label>Nomor Surat</label>
					<div class="value" v-html="detail.reference_number"></div>
				</div>
				<div class="item" v-if="detail.subject">
					<label>Perihal</label>
					<div class="value" v-html="detail.subject"></div>
				</div>
				<div class="item" v-if="detail.storage != ''">
					<label>Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storage"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.storagesub != ''">
					<label>Sub Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storagesub"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.share[0].user != ''">
					<label>{{ $disposition }}</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a :href="'{{ route('shared_'.$key.'_mail_'.$key_disposition.'_history') }}/' + detail._id" v-html="disposisi.user[0].name"></a></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.date">
					<label>Tanggal {{ $type }}</label>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<a v-bind:href="'{{ route('shared_'.$key.'_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	@if (Request::is('berbagi/surat/masuk*'))
		<script src="{{ asset('assets/app/vue/surat-masuk.js') }}"></script>
	@elseif (Request::is('berbagi/surat/keluar*'))
		<script src="{{ asset('assets/app/vue/surat-keluar.js') }}"></script>
	@endif
		
	<script>
		<?php
			// Sort By
			$sortKey = 'created_at';
			if (@$_GET['sort'] == 'from') {
				$sortKey = 'from';
			} else if (@$_GET['sort'] == 'subject') {
				$sortKey = 'subject';
			}

			// Ascending or Descending
			$asc = 'false';
			if (@$_GET['asc'] == 'true') {
				$asc = 'true';
			}
		?>

		@if (Request::is('berbagi/surat/masuk*'))
			getDataIncomingMail('{{ route("api_shared_incoming_mail", ["sort" => $sortKey]) }}&asc={{ $asc }}', 'incomingMail');
		@elseif (Request::is('berbagi/surat/keluar*'))
			getDataOutgoingMail('{{ route("api_shared_outgoing_mail", ["sort" => $sortKey]) }}&asc={{ $asc }}', 'outgoingMail');
		@endif

		$('#disposisiModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection