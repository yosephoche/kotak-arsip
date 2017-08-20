@extends('app.layouts.main')

@section('title', 'Sub Storage')

@section('contents')
	<div class="body">
		<form action="{{ route('storage_sub_update', ['id' => $sub->_id]) }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="put">
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Nama Rak</label>
						<input type="text" class="form-control" name="name" value="{{ $sub->name }}" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<button class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</ul>
</div>
@endsection