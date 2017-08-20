<header class="ka-menus">
	<ul class="list-unstyled">
		<li class="label-menus">Arsip</li>
		<li {{ Request::is('surat/masuk*') ? 'class=active' : '' }}><a href="{{ route('incoming_mail') }}">Surat Masuk</a></li>
		<li><a href="surat-keluar.html">Surat Keluar</a></li>
		<li><a href="surat-izin.html">Surat Perizinan</a></li>
		<li><button type="button" class="new-type">+ Jenis Arsip</button></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li class="label-menus">Konfigurasi</li>
		<li><a href="penyimpanan-arsip.html">Penyimpanan Arsip</a></li>
		<li><a href="anggota.html">Anggota</a></li>
		<li><a href="pengaturan.html">Pengaturan</a></li>
	</ul>

	<br>

	<ul class="list-unstyled">
		<li><a href="sampah.html"><i class="fa fa-trash-o"></i> &nbsp;Sampah</a></li>
	</ul>
</header>