@extends('app.layouts.main')

@section('title', 'Anggota')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('member') }}">Anggota</a></li>
				<li>Tambah Anggota</li>
			</ul>
		</div>

		<hr>

		<form action="{{ route('member_store') }}" method="post" enctype="multipart/form-data" class="row">
			{{ csrf_field() }}
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group form-line">
							<label for="">Nama Lengkap</label>
							<input type="text" name="name" class="form-control" value="{!! old('name') !!}" autocomplete="off" required>
						</div>
					
						<div class="form-group form-line">
							<label for="">Email</label>
							<input type="email" name="email" class="form-control" value="{!! old('email') !!}" autocomplete="off" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group form-line">
							<label for="">No. Telpon/HP</label>
							<input type="text" name="phone" class="form-control" value="{!! old('phone') !!}" autocomplete="off" required>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group form-line">
							<label for="">Jabatan</label>
							<input type="text" name="position" class="form-control" value="{!! old('position') !!}" required>
						</div>
					</div>

					<!-- <div class="col-md-4">
						<div class="form-group form-line">
							<label for="">Status</label>
							<select class="form-control" name="status">
								<option>Pilih</option>
								<option value="anggota">Anggota</option>
								<option value="kepala-divisi">Kepala Divisi</option>
								<option value="operator">Operator</option>
								<option value="admin">Admin</option>
							</select>
						</div>
					</div> -->
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
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<br>
						<button class="btn btn-primary">Tambah</button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<aside class="ka-sidebar-detail">
		<div class="detail-info">
			<div class="select no-border" style="height: calc(100vh - 70px); padding-top: 0">
				<h5 class="no-margin">Pratinjau</h5>
				<hr>

				<div class="item" v-if="userPhoto == true">
					<label for="img" id="img-preview" class="value"></label>
				</div>

				<div class="item">
					<label>Nama</label>
					<div class="value" id="username">{!! old('name') != '' ? old('name') : '...' !!}</div>
				</div>

				<div class="item">
					<label>Email</label>
					<div class="value" id="useremail">{!! old('email') != '' ? old('email') : '...' !!}</div>
				</div>

				<div class="item">
					<label>No. Telpon/HP</label>
					<div class="value" id="userphone">{!! old('phone') != '' ? old('phone') : '...' !!}</div>
				</div>

				<div class="item">
					<label>Jabatan</label>
					<div class="value" id="userposition">{!! old('position') != '' ? old('position') : '...' !!}</div>
				</div>
			</div>
		</div>
	</aside>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/pengguna.js') }}"></script>
	<script>
		getDataUsers('#', 'users');

		function vmodel(name) {
			$('input[name="' + name + '"]').on('change keyup', function() {
				var val = $(this).val();
				if (val == '') {
					val = '...';
				}
				$('#user' + name).html(val);
			});
		}

		vmodel('name');
		vmodel('email');
		vmodel('phone');
		vmodel('position');
	</script>
@endsection