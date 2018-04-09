@extends('app.layouts.main')

@section('title', 'Perangkat Terhubung')

@section('contents')
	<style>
		.table>tbody>tr.active>td {
			background-color: #fff;
		}
	</style>

	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('status_list_device') }}">Perangkat Terhubung</a></li>
			</ul>
		</div>

		@if (count($devices) > 1)
			<br>
			<p>Berikut adalah perangkat-perangkat yang terhubung dengan akun Anda.</p>
			<div class="alert alert-warning">
				Jika Anda merasa tidak memiliki salah satu perangkat dibawah, silahkan klik hapus perangkat dan kami menyarankan Anda mengganti kata sandi demi keamanan akun Anda!<br><br>
				<a href="{{ route('setting') }}?tab=security" class="btn btn-primary">Ganti kata sandi</a>
			</div>
		@endif

		<table class="table">
			<tr>
				<th>Jenis Perangkat</th>
				<th class="view-tablet-only">Sistem Operasi</th>
				<th style="max-width:300px"></th>
			</tr>
			@foreach ($devices as $device)
				<tr class="item">
					<td>
						<?php
							$useragent = substr($device->user_agent,strpos($device->user_agent,'(')+1); // remove the first ( and everything before it
							$useragent = substr($useragent,0,strpos($useragent,';')); // remove the first ) and everything after it
							echo $useragent;
						?>
					</td>
					<td class="view-tablet-only">
						{{ GlobalClass::getOS($device->user_agent) }}
					</td>
					<td class="text-right">
						@if ($device->user_agent == $_SERVER['HTTP_USER_AGENT'])
							<span class="text-info">Terpakai saat ini</span>
						@else
							<a href="#" class="text-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $device->_id }}">Hapus Perangkat</a>
						@endif
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@endsection

@section('modal')
	<!-- Modals -->
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('status_list_device_delete') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus Perangkat</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus perangkat ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- End Modal -->
@endsection


@section('registerscript')
	<script>
		/* Delete Modal */
		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection