<ul class="link-help">
	<li {{ Request::is('bantuan') ? 'class=active' : '' }}><a href="{{ route('help') }}">Tentang KotakArsip</a></li>
	<li {{ Request::is('bantuan/teknologi-ocr') ? 'class=active' : '' }}><a href="{{ route('help_ocr') }}">Teknologi OCR</a></li>
	<li {{ Request::is('bantuan/koneksi-server') ? 'class=active' : '' }}><a href="{{ route('help_server') }}">Koneksi Server</a></li>
	<li {{ Request::is('bantuan/penyimpanan-arsip') ? 'class=active' : '' }}><a href="{{ route('help_storage') }}">Penyimpanan Arsip</a></li>
	<li {{ Request::is('bantuan/surat-masuk') ? 'class=active' : '' }}><a href="{{ route('help_incoming_mail') }}">Surat Masuk</a>
		<ul class="sub">
			<li {{ Request::is('bantuan/surat-masuk/tambah-sunting') ? 'class=active' : '' }}><a href="{{ route('help_incoming_mail_create') }}">Tambah/sunting</a></li>
			<li {{ Request::is('bantuan/surat-masuk/hapus') ? 'class=active' : '' }}><a href="{{ route('help_incoming_mail_delete') }}">Hapus</a></li>
			<li {{ Request::is('bantuan/surat-masuk/disposisi') ? 'class=active' : '' }}><a href="{{ route('help_incoming_mail_disposition') }}">Disposisi</a></li>
		</ul>
	</li>
	<li {{ Request::is('bantuan/surat-keluar') ? 'class=active' : '' }}><a href="{{ route('help_outgoing_mail') }}">Surat Keluar</a>
		<ul class="sub">
			<li {{ Request::is('bantuan/surat-keluar/tambah-sunting') ? 'class=active' : '' }}><a href="{{ route('help_outgoing_mail_create') }}">Tambah/sunting</a></li>
			<li {{ Request::is('bantuan/surat-keluar/hapus') ? 'class=active' : '' }}><a href="{{ route('help_outgoing_mail_delete') }}">Hapus</a></li>
		</ul>
	</li>
	<li {{ Request::is('bantuan/jenis-arsip') ? 'class=active' : '' }}><a href="{{ route('help_archives') }}">Jenis Arsip Lainnya</a></li>
	<li><a href="{{ route('help_search') }}">Pencarian</a></li>
	<li><a href="{{ route('help_share') }}">Bagikan</a></li>
	<li><a href="{{ route('help_folder') }}">Folder</a></li>
	<li><a href="{{ route('help_file') }}">Berkas</a></li>
</ul>