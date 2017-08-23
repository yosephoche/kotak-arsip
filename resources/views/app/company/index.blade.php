@extends('app.layouts.main')

@section('title', 'Company')

@section('contents')
	<div class="ka-main">
		<p>Company Setting</p>
		<form action="{{ route('company_update', ['id' => $company->_id]) }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="PUT">
			<div class="item">
				<label>Kode Perusahaan</label>
				<div class="value"><input type="text" class="form-control" name="code" value="{{ $company->code }}" readonly></div>
			</div>
			<div class="item">
				<label>Nama Agensi</label>
				<div class="value"><input type="text" class="form-control" name="name" value="{{ $company->name }}"></div>
			</div>
			<div class="item">
				<label>Alamat Agensi</label>
				<div class="value"><input type="text" class="form-control" name="address" value="{{ $company->address }}"></div>
			</div>
			<div class="item">
				<label>Telepon</label>
				<div class="value"><input type="text" class="form-control" name="phone" value="{{ $company->phone }}"></div>
			</div>
			<div class="item">
				<label>Email</label>
				<div class="value"><input type="email" class="form-control" name="email" value="{{ $company->email }}"></div>
			</div>
			<div class="item">
				<hr>
				<input type="file" class="hide" name="files" id="files">
				<div class="row">
					<div class="col-md-6">
						<a href="" class="btn btn-default btn-block">Batal</a>
					</div>
					<div class="col-md-6">
						<button class="btn btn-primary btn-block">Simpan</button>
					</div>
				</div>
			</div>
		</form>

	</div>
@endsection