@extends('app.layouts.main')

@section('title', 'Member')

@section('contents')
	<div class="body">
		<h4>Members Edit</h4>
		<br>
		<form action="{{ route('member_update', ['id' => $member->_id ]) }}" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group hidden">
						<label for="">ID</label>
						<input type="hidden" name="_method" value="put">
						<input id="form-id" type="text" class="form-control" disabled>
						<input type="hidden" name="id_company" value="{{ Auth::user()->id_company }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>

					<div class="form-group">
						<label for="">Nama</label>
						<input type="text" name="name" value="{{ $member->name }}" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<div class="form-group">
						<label for="">Email</label>
						<input type="text" name="email" value="{{ $member->email }}" class="form-control" data-validation="required email" data-validation-error-msg-required="* Wajib diisi" data-validation-error-msg-email="* Mohon masukkan email yang valid">
					</div>

					<div class="form-group">
						<label for="">Jabatan</label>
						<input type="text" name="position" value="{{ $member->position }}" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<div class="form-group">
						<label for="">No. Telepon</label>
						<input type="text" name="phone" value="{{ $member->phone }}" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<div class="form-group">
						<label for="">Hak Akses Pengguna</label>
						<select name="status" class="form-control">
							<option value="admin">Admin</option>
							<option value="member">Member</option>
						</select>
					</div>

					<div class="form-group">
						<label for=""><span>Ganti </span>Foto Profil</label>
						<input type="file" name="photo[]" class="form-control">
					</div>

					<div class="form-group">
						<label for=""><span>Ubah </span>Password</label>
						<div class="input-group">
							<input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default see-password" data-name="password" style="height: 34px"><i class="fa fa-eye"></i></button>
							</span>
						</div>
					</div>

					<div class="form-footer">
						<a href="{{ route('member') }}" class="btn btn-default close-form">Kembali</a>
						<button class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection