@extends('app.layouts.main')

@section('title', 'Member')

@section('contents')
	<div class="body">
		<div class="body-action">
			<h4>Members</h4>
			<br>
			<div class="right">
				<a href="{{ route('member_create') }}"><img src="{{ url('/resources/assets/app') }}/img/icons/svg/newdoc.svg">Tambah</a>
			</div>
		</div>

		<ul class="list-content">
			@forelse ($member as $m)
				<li class="list-item">
					<div class="icon">
						<div class="dp" style="background-image: url({{ url('resources') }}/assets/kotakarsip/img/data-img/pengguna/{{ $m->photo ? $m->photo : 'user.png' }});"></div>
					</div>
					<div class="property">
						<h2>{{ $m->name }}</h2>
						<div class="date">{{ $m->position }}</div>
					</div>
					<div class="action">
						<div class="btn-group" role="group" aria-label="...">
							<a href="{{ route('member_edit', ['id' => $m->_id]) }}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-delete" data-id="{{ $m->_id }}"><i class="fa fa-trash-o"></i></button>
						</div>
					</div>
				</li>
			@empty
				
			@endforelse
		</ul>
	</div>
@endsection

@section('modal')
	<div class="modal fade modal-custom modal-small" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<form action="{{ route('member_delete') }}" method="post" class="form-custom">
				{{ csrf_field() }}

				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle"></i>&nbsp; Hapus</h4>
					</div>
					<div class="modal-body">
						<div class="content">
							<input type="text" class="hidden" id="delete-val" value="">
							<input type="hidden" name="id">
							Apakah Anda yakin ingin menghapus user ini?
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