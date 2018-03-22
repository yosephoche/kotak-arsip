@extends('app.layouts.main')

@section('title', 'Pencarian')

@section('contents')

	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascSearch = 'true';
		if (@$_GET['sort'] == 'search' AND @$_GET['asc'] == 'true') {
			$ascSearch = 'false';
		}

		$ascSubject = 'true';
		if (@$_GET['sort'] == 'subject' AND @$_GET['asc'] == 'true') {
			$ascSubject = 'false';
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				@if (@$_GET['q'] != '')
					<li>Hasil pencarian dari "<b>{{ $_GET['q'] }}</b>"</li>
				@else
					<li>Hasil pencarian</li>
				@endif
				<li class="pull-right"><a href="#" data-toggle="modal" data-target="#filterModal"><i class="fa fa-sliders"></i> &nbsp;Filter</a></li>
			</ul>
		</div>

		<ul class="current-filter">
			@if (isset($_GET['fulltext']) && $_GET['fulltext'] != null)
				<li>{{ $_GET['fulltext'] }} <a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'search', 'asc' => $ascSearch]) }}&fulltext=&type={{ @$_GET['type'] }}&start={{ @$_GET['start'] }}&end={{ @$_GET['end'] }}&storage={{ @$_GET['storage'] }}&storagesub={{ @$_GET['storagesub'] }}">&times;</a></li>
			@endif

			@if (isset($_GET['type']) && $_GET['type'] != null)
				<li>
					@if ($_GET['type'] == 'incoming_mail')
						Surat Masuk
					@elseif ($_GET['type'] == 'outgoing_mail')
						Surat Keluar
					@elseif ($_GET['type'] == 'file')
						Berkas
					@endif
					&nbsp;<a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'search', 'asc' => $ascSearch]) }}&fulltext={{ @$_GET['fulltext'] }}&type=&start={{ @$_GET['start'] }}&end={{ @$_GET['end'] }}&storage={{ @$_GET['storage'] }}&storagesub={{ @$_GET['storagesub'] }}">&times;</a>
				</li>
			@endif

			@if (isset($_GET['start']) && $_GET['start'] != null)
				<li>Tanggal {{ $_GET['start'] }} - {{ $_GET['end'] }} <a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'search', 'asc' => $ascSearch]) }}&fulltext={{ @$_GET['fulltext'] }}&type={{ @$_GET['type'] }}&start=&end=&storage={{ @$_GET['storage'] }}&storagesub={{ @$_GET['storagesub'] }}">&times;</a></li>
			@endif

			@if (isset($_GET['storage']) && $_GET['storage'] != null)
				<li>{{ @$storage_name->name }} <a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'search', 'asc' => $ascSearch]) }}&fulltext={{ @$_GET['fulltext'] }}&type={{ @$_GET['type'] }}&start={{ @$_GET['start'] }}&end={{ @$_GET['end'] }}&storage=&storagesub=">&times;</a></li>
			@endif
		</ul>

		<table class="table table-hover" v-if="json.search != ''">
			<tr>
				<th class="{{ @$_GET['sort'] == 'search' ? 'sort' : '' }}">
					<a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'search', 'asc' => $ascSearch]) }}&fulltext={{ @$_GET['fulltext'] }}&type={{ @$_GET['type'] }}&start={{ @$_GET['start'] }}&end={{ @$_GET['end'] }}&storage={{ @$_GET['storage'] }}&storagesub={{ @$_GET['storagesub'] }}">Judul Arsip</a>
					@if (@$_GET['sort'] == 'search')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th class="view-tablet-only {{ @$_GET['sort'] == 'subject' ? 'sort' : '' }}">
					<a href="{{ route('search', ['q' => @$_GET['q'] ,'sort' => 'subject', 'asc' => $ascSubject]) }}&fulltext={{ @$_GET['fulltext'] }}&type={{ @$_GET['type'] }}&start={{ @$_GET['start'] }}&end={{ @$_GET['end'] }}&storage={{ @$_GET['storage'] }}&storagesub={{ @$_GET['storagesub'] }}">Keterangan</a>
					@if (@$_GET['sort'] == 'subject')
						@if (@$_GET['asc'] == 'true')
							<i class="fa fa-angle-down i-sort"></i>
						@elseif (@$_GET['asc'] == 'false')
							<i class="fa fa-angle-up i-sort"></i>
						@endif
					@endif
				</th>
				<th width="150px">Jenis Arsip</th>
			</tr>
			<tr class="item" v-for="val in json.search" @click="detailSidebar(val, $event)">
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id + '?read_direct=true'" v-html="val.search"></a>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + val._id + '?read_direct=true'" v-html="val.search"></a>
					</div>
					<div v-if="val.type == 'file'">
						<a v-bind:href="'{{ route('file_detail') }}/' + val._id + '?read_direct=true'" v-html="val.search"></a>
					</div>
				</td>
				<td class="view-tablet-only" v-if="val.subject !== null">
					<div v-if="val.type == 'incoming_mail'">
						<span v-html="val.subject"></span>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<span v-html="val.subject"></span>
					</div>
					<div v-if="val.type == 'file'">
						<span v-html="val.desc"></span>
					</div>
				</td>
				<td class="view-tablet-only" v-else>-</td>
				<td>
					<div v-if="val.type == 'incoming_mail'">
						<span class="color-blue">Surat Masuk</span>
					</div>
					<div v-if="val.type == 'outgoing_mail'">
						<span class="color-green">Surat Keluar</span>
					</div>
					<div v-if="val.type == 'file'">
						<span class="color-gray">Berkas</span>
					</div>
				</td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li v-if="val.type == 'incoming_mail'">
							<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + val._id">Lihat Detail</a>
						</li>
						<li v-if="val.type == 'outgoing_mail'">
							<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + val._id">Lihat Detail</a>
						</li>

						<li v-if="val.type == 'incoming_mail'">
							<a v-bind:href="'{{ route('incoming_mail_move') }}/' + val._id">Sunting</a>
						</li>
						<li v-if="val.type == 'outgoing_mail'">
							<a v-bind:href="'{{ route('outgoing_mail_move') }}/' + val._id">Sunting</a>
						</li>

						<li><a href="#" data-toggle="modal" data-target="#deleteModal" class="text-danger" v-bind:data-id="val._id">Hapus</a></li>
					</ul>
				</td>
			</tr>

		</table>

		<div class="text-center" v-else>
			<hr>
			<br>
			<br>
			<br>
			<br>
			<img src="{{ url('assets/app/img/icons') }}/no_file.png" alt="" width="300px">
			<br>
			<br>
			<br>
			Data tidak ditemukan
		</div>

		<?php
			$link = GlobalClass::removeGetParam('page');
		?>

		@if ($archieve->lastPage() > 1)
		<div class="text-center">
			<hr class="m-t-0">
			<ul class="pagination-custom">
				<li class="{{ ($archieve->currentPage() == 1) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() - 1) }}">Sebelumnya</a>
				</li>
				@for ($i = 1; $i <= $archieve->lastPage(); $i++)
					<li class="{{ ($archieve->currentPage() == $i) ? ' active' : '' }}">
						<a href="{{ $link.'&page='.$i }}">{{ $i }}</a>
					</li>
				@endfor
				<li class="{{ ($archieve->currentPage() == $archieve->lastPage()) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() + 1) }}" >Selanjutnya</a>
				</li>
			</ul>
		</div>
		@endif
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
	<div class="modal fade modal-filter" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="shareLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('search') }}" method="get">
					<input type="hidden" name="q" value="{{ $_GET['q'] }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="shareLabelModal">Filter Pencarian</h4>
					</div>
					<div class="modal-body" style="border-top: 1px solid #ddd">
						<div class="form-group">
							<label for="">Full text (<i>Fitur OCR memungkinkan Anda mencari teks dalam gambar hasil scan</i>)</label>
							<input name="fulltext" class="form-control" value="{{ @$_GET['fulltext'] }}">
						</div>


						<div class="form-group">
							<label for="">Jenis Arsip</label>
							<select name="type" id="" class="form-control">
								<option value="">Semua Jenis Arsip</option>
								<option value="incoming_mail" {{ @$_GET['type'] == 'incoming_mail' ? 'selected' : '' }}>Surat Masuk</option>
								<option value="outgoing_mail" {{ @$_GET['type'] == 'outgoing_mail' ? 'selected' : '' }}>Surat Keluar</option>
								<option value="file" {{ @$_GET['type'] == 'file' ? 'selected' : '' }}>Berkas</option>
							</select>
						</div>

						<div class="form-group">
							<label for="">Rentang waktu diarsipkan</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="form-control" name="start" value="{{ @$_GET['start'] }}" />
								<span class="input-group-addon" style="border-left: 0; border-right: 0">sampai</span>
								<input type="text" class="form-control" name="end" value="{{ @$_GET['end'] }}" />
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Penyimpanan Arsip</label>
									<select name="storage" id="storage" class="form-control">
										<option value="">Semua Penyimpanan</option>
										@foreach ($storage as $s)
											<option value="{{ $s->_id }}">{{ $s->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-12" id="subshow">
								<div class="form-group">
									<label for="">Sub Penyimpanan Arsip</label>
									<select name="storagesub" id="substorage" class="form-control">
									</select>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Filter</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('search_delete') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="text" class="hidden" id="delete-val" value="">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus data ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- End Modal -->
@endsection

@section('template')
	<!-- No select data in table -->
	<template id="no-select">
		<div class="no-select text-center">
			<img src="{{ asset('assets/app/img/icons/select_file.png') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select no-border" style="height: calc(100vh - 160px)">
				<div class="item" v-if="detail.search">
					<div v-if="detail.type == 'incoming_mail'">
						<label>Asal Surat</label>
						<div class="value" v-html="detail.search"></div>
					</div>
					<div v-if="detail.type == 'outgoing_mail'">
						<label>Tujuan Surat</label>
						<div class="value" v-html="detail.search"></div>
					</div>
					<div v-if="detail.type == 'file'">
						<label>Judul Berkas</label>
						<div class="value" v-html="detail.search"></div>
					</div>
				</div>
				<div class="item" v-if="detail.desc">
					<label>Keterangan</label>
					<div v-html="detail.desc"></div>
				</div>
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
				<div class="item" v-if="detail.date">
					<div v-if="detail.type == 'incoming_mail'">
						<label>Tanggal Masuk</label>
					</div>
					<div v-if="detail.type == 'outgoing_mail'">
						<label>Tanggal Keluar</label>
					</div>
					<div v-if="detail.type == 'file'">
						<label>Tanggal Unggah</label>
					</div>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<div v-if="detail.type == 'incoming_mail'">
					<a v-bind:href="'{{ route('incoming_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
				</div>
				<div v-if="detail.type == 'outgoing_mail'">
					<a v-bind:href="'{{ route('outgoing_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
				</div>
				<div v-if="detail.type == 'file'">
					<a v-bind:href="'{{ route('file_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
				</div>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/pencarian.js') }}"></script>
	<script>
		<?php
			/* Sort By */
			$sortKey = '_id';
			if (@$_GET['sort'] == 'search') {
				$sortKey = 'search';
			} else if (@$_GET['sort'] == 'subject') {
				$sortKey = 'subject';
			}

			/* Ascending or Descending */
			$asc = 'false';
			if (@$_GET['asc'] == 'true') {
				$asc = 'true';
			}

			/* Pagination */
			$page = 1;
			if (@$_GET['page']) {
				$page = $_GET['page'];
			}

		?>
		getDataSearch('{{ route("api_search", ["q" => @$_GET["q"]]) }}&sort={{ $sortKey }}&asc={{ $asc }}&page={{ $page }}&fulltext={{ @$_GET["fulltext"] }}&type={{ @$_GET["type"] }}&start={{ @$_GET["start"] }}&end={{ @$_GET["end"] }}&storage={{ @$_GET["storage"] }}&storagesub={{ @$_GET["storagesub"] }}', 'search');

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		/* Date Picker */
		$('#datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		/* Storage */
		$('#subshow').hide();
		$('#storage').on('change', function(e){
			var storage_id = e.target.value;
			/* ajax */
			$.get('{{ route("search") }}/dropdown?storage_id=' + storage_id, function(data){
				if (data == 0) {
					$('#subshow').hide();
					$('#subshow').find('select').empty();
				} else {
					$('#subshow').show();
					$('#substorage').empty();
					$('#substorage').append('<option value="">Semua Sub Penyimpanan</option>');
					$.each(data, function(index, substorageObj){
						$('#substorage').append('<option value="'+substorageObj._id+'">'+substorageObj.name+'</option>');
					});
				}
			});
		});

		@if (@$_GET['q'] != '')
			$('input[name="q"]').val("{!! @$_GET["q"] !!}");
		@endif
	</script>
@endsection