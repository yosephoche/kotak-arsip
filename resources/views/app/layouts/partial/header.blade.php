<header class="ka-menus">
	<ul class="list-unstyled">
		<li class="label-menus">Arsip</li>
		<li {{ Request::is('surat/masuk*') ? 'class=active' : '' }}><a href="{{ route('incoming_mail') }}">Surat Masuk</a></li>
		<li {{ Request::is('surat/keluar*') ? 'class=active' : '' }}><a href="{{ route('outgoing_mail') }}">Surat Keluar</a></li>
		<li><button type="button" class="new-type">+ Jenis Arsip</button></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li class="label-menus">Konfigurasi</li>
		<li {{ Request::is('storage*') ? 'class=active' : '' }}><a href="{{ route('storage') }}">Penyimpanan Arsip</a></li>
		<li {{ Request::is('member*') ? 'class=active' : '' }}><a href="{{ route('member') }}">Anggota</a></li>
		<li {{ Request::is('setting*') ? 'class=active' : '' }}><a href="{{ route('setting') }}">Pengaturan</a></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li><a href="#"><i class="fa fa-trash-o"></i> &nbsp;Sampah</a></li>
	</ul>
</header>