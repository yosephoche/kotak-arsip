<header class="ka-menus">
	<?php $status = Auth::user()->status ?>
	<ul class="list-unstyled">
		<li class="label-menus">Arsip</li>
		<li {{ Request::is('surat/masuk*') ? 'class=active' : '' }}><a href="{{ route('incoming_mail') }}">Surat Masuk</a></li>
		<li {{ Request::is('surat/keluar*') ? 'class=active' : '' }}><a href="{{ route('outgoing_mail') }}">Surat Keluar</a></li>
		@if ($status == 'admin')
		<li {{ Request::is('arsip-kepegawaian*') ? 'class=active' : '' }}><a href="{{ route('employee') }}">Arsip Kepegawaian</a></li>
		@endif
		<li {{ Request::is('berkas*') ? 'class=active' : '' }}><a href="{{ route('file') }}">Berkas</a></li>
		<li {{ Request::is('folder*') ? 'class=active' : '' }}><a href="{{ route('folder') }}">Folder</a></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li class="label-menus">Konfigurasi</li>
		@if ($status == 'admin')
			<li {{ Request::is('penyimpanan-arsip*') ? 'class=active' : '' }}><a href="{{ route('storage') }}">Penyimpanan Arsip</a></li>
		@endif
		@if ($status == 'admin')
			<li {{ Request::is('anggota*') ? 'class=active' : '' }}><a href="{{ route('member') }}">Anggota</a></li>
		@endif
		<li {{ Request::is('pengaturan*') ? 'class=active' : '' }}><a href="{{ route('setting') }}">Pengaturan</a></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li {{ Request::is('sampah*') ? 'class=active' : '' }}><a href="{{ route('trash') }}"><i class="fa fa-trash-o"></i> &nbsp;Sampah</a></li>
	</ul>
</header>
<div class="overlay-menu"></div>