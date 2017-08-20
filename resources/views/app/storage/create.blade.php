@extends('app.layouts.main')

@section('title', 'Storage')

@section('contents')
	<div class="body">
		<form action="{{ route('storage_store') }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="id_company" value="{{ Auth::user()->id_company }}">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Type</label>
						<select name="type" class="form-control">
							<option value="filling_cabinet">Filling Cabinet</option>
							<option value="rolling_cabinet">Rolling Cabinet</option>
							<option value="rak_arsip">Rak Arsip</option>
							<option value="card_cabinet">Card Cabinet</option>
						</select>
					</div>

					<div class="form-group">
						<label for="">Nomor Rak:</label>
						<input type="text" class="form-control" name="name" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<button class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</ul>
</div>
@endsection