@extends('app.layouts.main')

@section('title', 'Setting')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('setting') }}">Pengaturan</a></li>
			</ul>
		</div>

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab">Koneksi Server</a></li>
			<li role="presentation"><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab">Akun</a></li>
			<li role="presentation"><a href="#tab-3" aria-controls="tab-3" role="tab" data-toggle="tab">Keamanan</a></li>
			<li role="presentation"><a href="#tab-4" aria-controls="tab-4" role="tab" data-toggle="tab">Perusahaan</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="tab-1">
				<p>Untuk mengakses kotakarsip di komputer lainnya dalam satu jaringan adalah sebagai berikut:</p>
				<blockquote><b>192.168.1.7</b></blockquote>
			</div>
			<div role="tabpanel" class="tab-pane tab-account-setting" id="tab-2">
				<h3>Informasi Akun Anda</h3>
				<table class="table">
					<tr>
						<td>Foto Profil</td>
						<td align="right"><div class="img-profile-setting" style="background-image: url({{ asset('assets/app/img/users').'/'.$user->photo }})"></div></td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>Nama Lengkap</td>
						<td align="right">{{ $user->name }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>Alamat Email</td>
						<td align="right">{{ $user->email }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>No. Telpon/Hp</td>
						<td align="right">{{ $user->phone }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td align="right">{{ $user->position }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
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
			<div role="tabpanel" class="tab-pane tab-account-setting" id="tab-4">
				<h3>Perusahaan</h3>
				<table class="table">
					<tr>
						<td>Nama Perusahaan</td>
						<td align="right">{{ $company->name }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>Kode Perusahaan</td>
						<td align="right"><b>{{ $company->code }}</b></td>
						<td width="100px" align="right"></td>
					</tr>
					<tr>
						<td>Alamat Perusahaan</td>
						<td align="right">{{ $company->address }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>No. Telpon/Hp</td>
						<td align="right">{{ $company->phone }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
					<tr>
						<td>Email Perusahaan</td>
						<td align="right">{{ $company->email }}</td>
						<td width="100px" align="right"><a href="">Sunting</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<aside class="ka-sidebar-detail">
		
	</aside>
@endsection