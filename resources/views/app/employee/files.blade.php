@extends('app.layouts.main')

@section('title', 'Arsip '.$user->name)

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascName = 'true';
		if (@$_GET['sort'] == 'name' AND @$_GET['asc'] == 'true') {
			$ascName = 'false';
		}

		$ascdate = 'true';
		if (@$_GET['sort'] == 'date' AND @$_GET['asc'] == 'true') {
			$ascdate = 'false';
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('employee') }}">Arsip {{ $user->name }}</a></li>
			</ul>
		</div>

		<table class="table table-hover" v-if="json.employee != ''">
			<thead>
				<tr>
					<th class="{{ @$_GET['sort'] == 'name' ? 'sort' : '' }} no-border">
						<a href="{{ route('employee', ['sort' => 'name', 'asc' => $ascName]) }}">Judul Berkas</a>
						@if (@$_GET['sort'] == 'name')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
					<th class="view-tablet-only no-border">Keterangan</th>
					<th class="view-tablet-only {{ @$_GET['sort'] == 'date' ? 'sort' : '' }} no-border" colspan="2">
						<a href="{{ route('employee', ['sort' => 'date', 'asc' => $ascdate]) }}">Tanggal Diarsipkan</a>
						@if (@$_GET['sort'] == 'date')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
				</tr>
			</thead>
			<tbody class="item no-border" v-for="val in json.employee" v-on:click="detailSidebar(val, $event)">
				
				<!-- Original File -->
				<td>
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'doc'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'docx'" src="{{ url('assets/app/img/icons/word.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Word">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'ppt'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'pptx'" src="{{ url('assets/app/img/icons/power_point.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Power Point">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'xls'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'xlsx'" src="{{ url('assets/app/img/icons/excel.png') }}" alt="" height="30px" style="margin-right: 10px" title="Mic. Excel">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'pdf'" src="{{ url('assets/app/img/icons/pdf-01.png') }}" alt="" height="30px" style="margin-right: 10px" title="PDF">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'jpg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'png'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
					<img class="img view-tablet-only" v-if="val.files[0].substr((val.files[0].lastIndexOf('.') + 1)).toLowerCase() === 'jpeg'" src="{{ url('assets/app/img/icons/image.png') }}" alt="" height="30px" style="margin-right: 10px" title="File Gambar">
					<a v-bind:href="'{{ route('employee_detail') }}/' + val._id" v-html="val.name"></a>
				</td>
				<td class="view-tablet-only" v-html="val.desc" v-if="val.desc != null"></td>
				<td v-else>-</td>
				<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
				<td class="text-right dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a v-bind:href="'{{ route('employee_detail') }}/' + val._id">Lihat Detail</a></li>
						<li><a href="#" data-toggle="modal" data-target="#editModal" :data-id="val._id" :data-name="val.name" :data-desc="val.desc">Sunting</a></li>
						<li><a type="button" data-toggle="modal" data-target="#deleteModal" v-bind:data-id="val._id" class="text-danger">Hapus</a></li>
					</ul>
				</td>
			</tbody>

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
			Belum ada berkas
		</div>
	</div>

	<aside class="ka-sidebar-detail">
		<a href="#" data-toggle="modal" data-target="#newModal" class="btn btn-primary btn-block">Tambah</a>

		<br>

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
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newLabelModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('employee_store') }}" method="post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="id_employee" value="{{ $user->_id }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="newLabelModal">Tambah Berkas</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<input type="text" name="name" class="form-control" placeholder="Judul Berkas" autocomplete="off">
						</div>
						<div class="form-group">
							<textarea name="desc" rows="5" class="form-control" placeholder="Deskripsi"></textarea>
						</div>
						<div class="form-group">
							<input type="file" name="file[]" class="form-control" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx">
						</div>
						<div class="form-group">
							<label>Penyimpanan Arsip <i>(opsional)</i></label>
							<div class="value">
								<select name="storage" id="storage" class="form-control">
									<option value="">Pilih Penyimpanan</option>
									@foreach ($storage as $s)
										<option value="{{ $s->_id }}">{{ $s->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group" id="subshow">
							<label>Sub Penyimpanan Arsip</label>
							<div class="value">
								<select name="storagesub" id="substorage" class="form-control">
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Tambahkan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('employee_update') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" class="form-control" name="id">

						<div class="form-group">
							<input type="text" name="name" class="form-control" placeholder="Judul Berkas">
						</div>

						<div class="form-group">
							<textarea name="desc" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
						</div>

						<div class="form-group">
							<label>Penyimpanan Arsip <i>(opsional)</i></label>
							<div class="value">
								<select name="storage" id="edit-storage" class="form-control">
									<option value="">Pilih Penyimpanan</option>
									@foreach ($storage as $s)
										<option value="{{ $s->_id }}">{{ $s->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group" id="edit-subshow">
							<label>Sub Penyimpanan Arsip</label>
							<div class="value">
								<select name="storagesub" id="edit-substorage" class="form-control">
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('employee_delete') }}" method="post">
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
			<div class="select">
				<div v-for="message in detail.share_info_shared" v-if="message.share_to.$oid == '{{ Auth::user()->id }}'">
					<div class="item item-highlight" v-if="message.message != null">
						<label>Pesan untuk Anda</label>
						<div class="value" v-html="message.message"></div>
					</div>
					<div class="item" v-if="detail.id_owner != null">
						<label>Dibagikan oleh</label>
						<div class="value" v-html="detail.owner[0].name"></div>
					</div>
				</div>
				<div class="item" v-if="detail.name">
					<label>Judul Berkas</label>
					<div class="value" v-html="detail.name"></div>
				</div>
				<div class="item" v-if="detail.desc">
					<label>Deskripsi</label>
					<div v-html="detail.desc"></div>
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
					<label>Tanggal Diarsipkan</label>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<a v-bind:href="'{{ route('employee_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/arsip-kepegawaian.js') }}"></script>
	<script>
		getDataEmployee('{{ route("api_employee", ["id" => $user->_id]) }}', 'employee');

		// Edit Modal
		$('#editModal').on('show.bs.modal', function (e) {
			var key = ['id', 'name', 'desc'];
			for (var i = 0; i < key.length; i++) {
				$(this).find('.form-control[name="' + key[i] + '"]').val($(e.relatedTarget).data(key[i]));
			}
		});

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		//Ajax Dropdown
		//Hide Sub Storage
		$('#subshow, #edit-subshow').hide();

		$('#storage').on('change', function(e){
			var storage_id = e.target.value;
			//ajax
			$.get('{{ route("employee_files_substorage") }}?storage_id=' + storage_id, function(data){
				if (data == 0) {
					$('#subshow').hide();
					$('#subshow').find('select').empty();
				} else {
					$('#subshow').show();
					$('#substorage').empty();
					$.each(data, function(index, substorageObj){
						$('#substorage').append('<option value="'+substorageObj._id+'">'+substorageObj.name+'</option>');
					});
				}
			});
		});
		$('#edit-storage').on('change', function(e){
			var storage_id = e.target.value;
			//ajax
			$.get('{{ route("employee_files_substorage") }}?storage_id=' + storage_id, function(data){
				if (data == 0) {
					$('#edit-subshow').hide();
					$('#edit-subshow').find('select').empty();
				} else {
					$('#edit-subshow').show();
					$('#edit-substorage').empty();
					$.each(data, function(index, substorageObj){
						$('#edit-substorage').append('<option value="'+substorageObj._id+'">'+substorageObj.name+'</option>');
					});
				}
			});
		});

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection