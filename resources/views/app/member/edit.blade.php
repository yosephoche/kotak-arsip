@extends('app.layouts.main')

@section('title', 'Anggota')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('member') }}">Anggota</a></li>
				<li>Edit</li>
			</ul>
		</div>

		<hr>

		<form action="{{ route('member_update', ['id' => $member->_id ]) }}" method="post" enctype="multipart/form-data" class="row">
			{{ csrf_field() }}
			<input type="hidden" name="_method" value="put">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group form-line">
							<label for="">Nama Lengkap</label>
							<input type="text" name="name" value="{{ $member->name }}" class="form-control" autocomplete="off" required>
							@if ($errors->has('name'))
								<span class="help-block">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
						</div>
					
						<div class="form-group form-line">
							<label for="">Email</label>
							<input type="email" name="email" value="{{ $member->email }}" class="form-control" autocomplete="off" required>
							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group form-line">
							<label for="">No. Telpon/HP</label>
							<input type="text" name="phone" value="{{ $member->phone }}" class="form-control" pattern="[0-9]*" autocomplete="off" required>
							@if ($errors->has('phone'))
								<span class="help-block">
									<strong>{{ $errors->first('phone') }}</strong>
								</span>
							@endif
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group form-line">
							<label for="">Jabatan</label>
							<input type="text" name="position" value="{{ $member->position }}" class="form-control" required>
							@if ($errors->has('position'))
								<span class="help-block">
									<strong>{{ $errors->first('position') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group form-line">
							<label for="">Status</label>
							<select class="form-control" name="status" required>
								<?php 
									$status = ["anggota"=>"Anggota", "admin"=>"Admin"];
									foreach ($status as $key => $value) {
								?>
									<option value="{{ $key }}"  {{ $key == $member->status ? 'selected' : '' }}>{{ $value }}</option>
								<?php 
									}
								?>
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="form-group form-line">
							<label for="">Foto</label>
							<input type="file" name="photo[]" class="form-control" accept=".jpg, .png, .jpeg" onchange="readURL(this)" @change="userPhoto = true">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group form-line">
							<label for="">Kata Sandi</label>
							<input type="password" name="password" class="form-control">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group form-line">
							<label for="">Konfirmasi Kata Sandi</label>
							<input type="password" name="password_confirmation" class="form-control">
							@if ($errors->has('password_confirmation'))
								<span class="help-block">
									<strong>{{ $errors->first('password_confirmation') }}</strong>
								</span>
							@endif
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<br>
						<button class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<aside class="ka-sidebar-detail">
		<div class="detail-info">
			<div class="select no-border" style="height: calc(100vh - 70px); padding-top: 0">
				<h5 class="no-margin">Data saat ini</h5>
				<hr>

				<div class="item">
					<label for="img" id="img-preview" class="value">
						<img src="{{ url('assets/app/img/users').'/'.$member->photo }}" width="100%">
					</label>
				</div>

				<div class="item">
					<label>Nama</label>
					<div class="value">{{ $member->name }}</div>
				</div>

				<div class="item">
					<label>Email</label>
					<div class="value">{{ $member->email }}</div>
				</div>

				<div class="item">
					<label>No. Telpon/HP</label>
					<div class="value">{{ $member->phone }}</div>
				</div>

				<div class="item">
					<label>Jabatan</label>
					<div class="value">{{ $member->position }}</div>
				</div>
			</div>
		</div>
	</aside>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/pengguna.js') }}"></script>
	<script>
		getDataUsers('#', 'users');
	</script>
@endsection