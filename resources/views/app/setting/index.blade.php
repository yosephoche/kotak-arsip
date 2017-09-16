@extends('app.layouts.main')

@section('title', 'Setting')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif
	
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('setting') }}">Pengaturan</a></li>
			</ul>
		</div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="{{ @!$_GET['tab'] ? 'active' : '' }}"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab">Koneksi Server</a></li>
			<li role="presentation" class="{{ @$_GET['tab'] == 'account' ? 'active' : '' }}"><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab">Akun</a></li>
			<li role="presentation"><a href="#tab-3" aria-controls="tab-3" role="tab" data-toggle="tab">Keamanan</a></li>
			@if (Auth::user()->status == 'admin')
				<li role="presentation" class="{{ @$_GET['tab'] == 'company' ? 'active' : '' }}"><a href="#tab-4" aria-controls="tab-4" role="tab" data-toggle="tab">Perusahaan</a></li>
			@endif
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane {{ @!$_GET['tab'] ? 'active' : '' }}" id="tab-1">
				<?php 
					//Check IP Server
					$serverIP = getHostByName(getHostName());
				?>
				<p>Untuk mengakses kotakarsip di komputer lainnya dalam satu jaringan adalah sebagai berikut:</p>
				<blockquote><b>{{ $serverIP }}</b></blockquote>
			</div>
			<div role="tabpanel" class="tab-pane tab-account-setting {{ @$_GET['tab'] == 'account' ? 'active' : '' }}" id="tab-2">
				<h3>Informasi Akun Anda</h3>
				<table class="table">
					<tr>
						<td>Foto Profil</td>
						<td align="right"><div class="img-profile-setting" style="background-image: url({{ asset('assets/app/img/users').'/'.$user->photo }})"></div></td>
						<td width="100px" align="right">
							<form action="{{ route('update_user') }}" method="POST" enctype="multipart/form-data">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="{{ $user->_id }}">
								<input onchange="this.closest('form').submit()" type="file" id="files" name="photo[]" class="hide" accept=".jpg, .png, .jpeg, .pdf">
								<label for="files">Sunting</label>
							</form>
						</td>
					</tr>
					<tr>
						<td>Nama Lengkap</td>
						<td align="right">{{ $user->name }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="name" data-val="{{ $user->name }}" data-label="Sunting Nama Pengguna">Sunting</a></td>
					</tr>
					<tr>
						<td>Alamat Email</td>
						<td align="right">{{ $user->email }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="email" data-val="{{ $user->email }}" data-label="Sunting Email Pengguna">Sunting</a></td>
					</tr>
					<tr>
						<td>No. Telpon/Hp</td>
						<td align="right">{{ $user->phone }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="phone" data-val="{{ $user->phone }}" data-label="Sunting Telpon Pengguna">Sunting</a></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td align="right">{{ $user->position }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="position" data-val="{{ $user->position }}" data-label="Sunting Posisi Pengguna">Sunting</a></td>
					</tr>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane tab-account-setting" id="tab-3">
				<h3>Keamanan</h3>
				<table class="table">
					<tr>
						<td><b>Kata Sandi</b><br>Tetapkan kata sandi unik untuk melindungi akun KotakArsip Anda.</td>
						<td align="right"><a href="#" class="btn btn-default">Ganti Kata Sandi</a></td>
					</tr>
				</table>
			</div>
			
			<div role="tabpanel" class="tab-pane tab-account-setting {{ @$_GET['tab'] == 'company' ? 'active' : '' }}" id="tab-4">
				<h3>Perusahaan</h3>
				<table class="table">
					<tr>
						<td>Nama Perusahaan</td>
						<td align="right">{{ $company->name }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="name" data-val="{{ $company->name }}" data-label="Sunting Nama Perusahaan">Sunting</a></td>
					</tr>
					<tr>
						<td>Kode Perusahaan</td>
						<td align="right"><b>{{ $company->code }}</b></td>
						<td width="100px" align="right"></td>
					</tr>
					<tr>
						<td>Alamat Perusahaan</td>
						<td align="right">{{ $company->address }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="address" data-val="{{ $company->address }}" data-label="Sunting Alamat Perusahaan">Sunting</a></td>
					</tr>
					<tr>
						<td>No. Telpon/Hp</td>
						<td align="right">{{ $company->phone }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="phone" data-val="{{ $company->phone }}" data-label="Sunting Telpon Perusahaan">Sunting</a></td>
					</tr>
					<tr>
						<td>Email Perusahaan</td>
						<td align="right">{{ $company->email }}</td>
						<td width="100px" align="right"><a href="" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="email" data-val="{{ $company->email }}" data-label="Sunting Email Perusahaan">Sunting</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<aside class="ka-sidebar-detail">
		
	</aside>
@endsection

@section('modal')
	<!-- Update User -->
	<div class="modal fade" id="usereditModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('update_user') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Akun</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						<div class="form-group">
							<input type="text" name="name" id="inputuser" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Update Company -->
	<div class="modal fade" id="companyeditModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('update_company') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Akun</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						<div class="form-group">
							<input type="text" name="name" id="inputcompany" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('registerscript')
	<script>
		// Edit Modal User
		$('#usereditModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			var name = $(e.relatedTarget).data('name');
			var val = $(e.relatedTarget).data('val');
			var label = $(e.relatedTarget).data('label');
			$(this).find('input[name="id"]').val(id);
			$(this).find('#inputuser').attr('name', name);
			$(this).find('#inputuser').val(val);
			$(this).find('#editLabelModal').text(label);
		});

		// Edit Modal Company
		$('#companyeditModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			var name = $(e.relatedTarget).data('name');
			var val = $(e.relatedTarget).data('val');
			var label = $(e.relatedTarget).data('label');
			$(this).find('input[name="id"]').val(id);
			$(this).find('#inputcompany').attr('name', name);
			$(this).find('#inputcompany').val(val);
			$(this).find('#editLabelModal').text(label);
		});

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection