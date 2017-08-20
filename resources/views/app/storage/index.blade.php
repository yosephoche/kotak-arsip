@extends('app.layouts.main')

@section('title', 'Storage')

@section('contents')
	<div class="body">
		<div class="body-action">
			<div class="sort">
				Urutkan berdasarkan&nbsp;
				<label class="select-text">
					<select>
						<option value="">Terbaru</option>
						<option value="">Asal Surat</option>
					</select>
				</label>
			</div>

			<div class="right">
				<a href="{{ route('storage_create') }}"><img src="{{ url('/resources/assets/app') }}/img/icons/svg/newdoc.svg">Tambah</a>
			</div>
		</div>

		<ul class="list-content">
			@forelse ($storage as $s)
				<li class="list-item" data-id="{{ $s->_id }}" data-rak="{{ $s->name }}">
					<div class="icon">
						<img src="{{ url('/resources/assets/app') }}/img/icons/svg/rak.svg" alt="Document">
					</div>
					<div class="property">
						<a href="{{ route('storage_sub', ['id' => $s->_id]) }}"><h2>{{ $s->name }}</h2></a>
						<div class="date">{{ $s->type }}</div>
					</div>
					<div class="action">
						<div class="btn-group" role="group" aria-label="...">
							<a href="{{ route('storage_edit', ['id' => $s->_id]) }}" type="button" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-delete" data-id="{{ $s->_id }}"><i class="fa fa-trash-o"></i></button>
						</div>
					</div>
				</li>
			@empty
				<li class="list-empty">
					<div class="title">Penyimpanan fisik anda kosong</div>
				</li>
			@endforelse
		</ul>
	</div>
@endsection

@section('modal')
	<div class="modal fade modal-custom modal-small" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<form action="{{ route('storage_delete') }}" method="post" class="form-custom">
				{{ csrf_field() }}

				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle"></i>&nbsp; Hapus</h4>
					</div>
					<div class="modal-body">
						<div class="content">
							<input type="text" class="hidden" id="delete-val" value="">
							<input type="hidden" name="id">
							Apakah Anda yakin ingin menghapus rak ini?
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
						<button type="submit" class="btn btn-danger">Ya</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('registerscript')
	<script>
		$('#modal-delete').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection