@extends('app.layouts.main')

@section('title', 'Storage')

@section('contents')
	<div class="body">
		<form action="{{ route('storage_sub_store') }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="id_storage" value="{{ $storage->_id }}">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Nama Sub Storage:</label>
						<input type="text" class="form-control" name="name" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<button class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</ul>
</div>
@endsection