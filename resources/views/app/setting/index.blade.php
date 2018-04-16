@extends('app.layouts.main')

@section('title', 'Setting')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	@if ( Session::has('error') ) 
		<div class="alert-custom alert-custom-danger"><i class="fa fa-times-circle"></i>{{ session('error') }}</div>
	@endif
	
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('setting') }}">Pengaturan</a></li>
			</ul>
		</div>

		@if (Auth::user()->email != 'demo@kotakarsip.com')
			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<!-- <li role="presentation" class="{{ @!$_GET['tab'] ? 'active' : '' }}"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab">Koneksi Server</a></li> -->
				<li role="presentation" class="{{ @!$_GET['tab'] ? 'active' : '' }} {{ @$_GET['tab'] == 'account' ? 'active' : '' }}"><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab">Akun</a></li>
				<li role="presentation" class="{{ @$_GET['tab'] == 'security' ? 'active' : '' }}"><a href="#tab-3" aria-controls="tab-3" role="tab" data-toggle="tab">Keamanan</a></li>
				@if (Auth::user()->status == 'admin')
					<li role="presentation" class="{{ @$_GET['tab'] == 'company' ? 'active' : '' }}"><a href="#tab-4" aria-controls="tab-4" role="tab" data-toggle="tab">Perusahaan</a></li>
				@endif
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<!-- <div role="tabpanel" class="tab-pane {{ @!$_GET['tab'] ? 'active' : '' }}" id="tab-1">
					<?php 
						/* Check IP Server */
						$serverIP = getHostByName(getHostName());
					?>
					<p>Untuk mengakses kotakarsip di komputer lainnya dalam satu jaringan adalah sebagai berikut:</p>
					<blockquote><b>{{ $serverIP }}</b></blockquote>
				</div> -->
				<div role="tabpanel" class="tab-pane tab-account-setting {{ @!$_GET['tab'] ? 'active' : '' }} {{ @$_GET['tab'] == 'account' ? 'active' : '' }}" id="tab-2">
					<h3>Informasi Akun Anda</h3>
					<table class="table">
						<tr>
							<td>Foto Profil</td>
							<td align="right">
								@if ($user->photo != '')
									<div class="img-profile-setting" style="background-image: url({{ asset('assets/app/img/users').'/'.$user->photo }})"></div>
								@else
									<div class="img-profile-setting" style="background-image: url({{ asset('assets/app/img/icons') }}/user-white.png)"></div>
								@endif
							</td>
							<td width="100px" align="right">
								<form action="{{ route('update_user') }}" method="POST" enctype="multipart/form-data">
									{{ csrf_field() }}
									<input type="hidden" name="id" value="{{ $user->_id }}">
									<input onchange="this.closest('form').submit()" type="file" id="files" name="photo[]" class="hide" accept=".jpg, .png, .jpeg, .pdf">
									<label for="files"><a style="font-weight: normal;cursor: pointer;">Sunting</a></label>
								</form>
							</td>
						</tr>
						<tr>
							<td>Nama Lengkap</td>
							<td align="right">{{ $user->name }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="name" data-val="{{ $user->name }}" data-label="Sunting Nama Pengguna">Sunting</a></td>
						</tr>
						<tr>
							<td>Alamat Email</td>
							<td align="right">{{ $user->email }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="email" data-val="{{ $user->email }}" data-label="Sunting Email Pengguna">Sunting</a></td>
						</tr>
						<tr>
							<td>No. Telpon/Hp</td>
							<td align="right">{{ $user->phone }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="phone" data-val="{{ $user->phone }}" data-label="Sunting Telpon Pengguna">Sunting</a></td>
						</tr>
						<tr>
							<td>Jabatan</td>
							<td align="right">{{ $user->position }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#usereditModal" data-id="{{ $user->_id }}" data-name="position" data-val="{{ $user->position }}" data-label="Sunting Posisi Pengguna">Sunting</a></td>
						</tr>
					</table>
				</div>
				<div role="tabpanel" class="tab-pane tab-account-setting {{ @$_GET['tab'] == 'security' ? 'active' : '' }}" id="tab-3">
					<h3>Keamanan</h3>
					<table class="table">
						<tr>
							<td><b>Kata Sandi</b><br>Tetapkan kata sandi unik untuk melindungi akun KotakArsip Anda.</td>
							<td align="right"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#passwordeditModal" data-id="{{ $user->_id }}" data-label="Sunting Kata Sandi">Ganti Kata Sandi</a></td>
						</tr>
						<tr>
							<td><b>2 Langkah Verifikasi</b><br>Aktifkan fitur ini untuk meningkatkan keamanan akun Anda.</td>
							<td align="right">
								@if (Auth::user()->twostepauth == 1)
									<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#twostepauth-remove" data-id="{{ $user->_id }}" data-label="Non-aktifkan 2 Langkah Verifikasi">Non-aktifkan</a>
								@else
									<a href="#" class="btn btn-default" data-toggle="modal" data-target="#twostepauth" data-id="{{ $user->_id }}" data-label="Aktifkan 2 Langkah Verifikasi">Aktifkan</a>
								@endif
							</td>
						</tr>

						@if (Auth::user()->twostepauth == 1)
							<tr>
								<td colspan="2" style="border-top: 0; padding-top: 0 !important;">
									<table class="table table-bordered">
										<tr>
											<th style="background-color: #f5f5f5">Jenis Perangkat</th>
											<th class="view-tablet-only" style="background-color: #f5f5f5">Sistem Operasi</th>
											<th style="width: 200px; background-color: #f5f5f5">Akses</th>
										</tr>
										@foreach ($devices as $device)
											<tr class="item">
												<td>
													<?php
														$useragent = substr($device->user_agent,strpos($device->user_agent,'(')+1); // remove the first ( and everything before it
														$useragent = substr($useragent,0,strpos($useragent,')')); // remove the first ) and everything after it
														if (substr_count($useragent, ';') == 2) {
															$useragent = substr($useragent,strpos($useragent,';') + 1);
															$useragent = substr($useragent,strpos($useragent,';') + 1);
															if ($useragent == ' x64' || $useragent == ' x86') {
																$useragent = 'Computer Windows';
															}
														} else if (substr_count($useragent, ';') > 2) {
															$useragent = substr($useragent,strpos($useragent,';') + 1);
															$useragent = substr($useragent,strpos($useragent,';') + 1);
															$useragent = substr($useragent,0,strpos($useragent,';'));
															if ($useragent == ' x64' || $useragent == ' x86') {
																$useragent = 'Computer Windows';
															}
														} else {
															$useragent = substr($useragent,0,strpos($useragent,';'));
														}
														echo $useragent;
													?>
												</td>
												<td class="view-tablet-only">
													{{ GlobalClass::getOS($device->user_agent) }}
												</td>
												<td>
													@if ($device->user_agent == $_SERVER['HTTP_USER_AGENT'])
														<span class="text-info">Terpakai saat ini</span>
													@else
														<a href="#" class="text-danger" data-toggle="modal" data-target="#deleteDevice" data-id="{{ $device->_id }}">Hapus Perangkat</a>
													@endif
												</td>
											</tr>
										@endforeach
									</table>
								</td>
							</tr>
						@endif
					</table>
				</div>
				
				<div role="tabpanel" class="tab-pane tab-account-setting {{ @$_GET['tab'] == 'company' ? 'active' : '' }}" id="tab-4">
					<h3>Perusahaan</h3>
					<table class="table">
						<tr>
							<td>Nama Perusahaan</td>
							<td align="right">{{ $company->name }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="name" data-val="{{ $company->name }}" data-label="Sunting Nama Perusahaan">Sunting</a></td>
						</tr>
						<tr>
							<td>Kode Perusahaan</td>
							<td align="right"><b>{{ $company->code }}</b></td>
							<td width="100px" align="right"></td>
						</tr>
						<tr>
							<td>Alamat Perusahaan</td>
							<td align="right">{{ $company->address }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="address" data-val="{{ $company->address }}" data-label="Sunting Alamat Perusahaan">Sunting</a></td>
						</tr>
						<tr>
							<td>No. Telpon/Hp</td>
							<td align="right">{{ $company->phone }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="phone" data-val="{{ $company->phone }}" data-label="Sunting Telpon Perusahaan">Sunting</a></td>
						</tr>
						<tr>
							<td>Email Perusahaan</td>
							<td align="right">{{ $company->email }}</td>
							<td width="100px" align="right"><a href="#" data-toggle="modal" data-target="#companyeditModal" data-id="{{ $company->_id }}" data-name="email" data-val="{{ $company->email }}" data-label="Sunting Email Perusahaan">Sunting</a></td>
						</tr>
					</table>
				</div>
			</div>
		@else
			<div class="alert alert-warning">Anda dalam mode demo</div>
		@endif
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

	<!-- Update Password -->
	<div class="modal fade" id="passwordeditModal" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('update_password') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Sunting Akun</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						<div class="form-group">
							<label for="oldpassword">Kata Sandi Lama</label>
							<input type="password" name="old_password" class="form-control" id="oldpassword">
						</div>
						<div class="form-group">
							<label for="newpassword">Kata Sandi Baru</label>
							<input type="password" name="new_password" class="form-control" id="newpassword">
						</div>
						<div class="form-group">
							<label for="retypepassword">Ulangi Kata Sandi</label>
							<input type="password" name="confirm_password" class="form-control" id="retypepassword">
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

	<!-- Two Step Auth -->
	<div class="modal fade" id="twostepauth" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog" role="document" style="max-width: 400px">
			<div class="modal-content">
				<form action="{{ route('twostepauth_active') }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="useragent" value="{{ $_SERVER['HTTP_USER_AGENT'] }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Aktifkan 2 Langkah Verifikasi</h4>
						<hr>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-8 col-md-offset-2 text-center">
								<img src="{{ url('assets/app/img/icons/security.png') }}" width="80%">
								<br>
								<br>
								<br>
							</div>
							<div class="col-md-12 text-center">
								<h4 style="line-height: 1.6">Keamanan lebih kuat untuk Akun Anda.</h4>
								<p>Setiap perangkat baru yang masuk ke akun Anda akan mendapatkan syarat verifikasi kode unik yang nanti akan dikirimkan ke email Anda.</p>
								<br>
								<button class="btn btn-primary btn-lg">Aktifkan Sekarang</button>
								<br>
								<br>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Remove Two Step Auth -->
	<div class="modal fade" id="twostepauth-remove" tabindex="-1" role="dialog" aria-labelledby="editLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('twostepauth_remove') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabelModal">Non-aktifkan 2 Langkah Verifikasi</h4>
					</div>
					<div class="modal-body">
						Apakah Anda yakin akan menonaktifkan fitur 2 langkah Verifikasi? Hal ini dapat menurunkan keamanan akun Anda.
					</div>
					<div class="modal-footer">
						<button class="btn btn-danger">Non-aktifkan Sekarang</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Modals -->
	<div class="modal fade" id="deleteDevice" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('status_list_device_delete') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus Perangkat</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus perangkat ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- End Modal -->
@endsection

@section('registerscript')
	<script>
		/* Edit Modal User */
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

		/* Edit Modal Password */
		$('#passwordeditModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			var label = $(e.relatedTarget).data('label');
			$(this).find('input[name="id"]').val(id);
			$(this).find('#editLabelModal').text(label);
		});

		/* Edit Modal Company */
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

		/* 3 mean 3second */
		alertTimeout(3);

		/* Delete Device */
		$('#deleteDevice').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});
	</script>
@endsection