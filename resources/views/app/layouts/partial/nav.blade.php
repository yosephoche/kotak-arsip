<nav class="ka-nav">
	<ul class="left-side">
		<li class="nav-toggle">
			<a href="#"><i class="fa fa-bars"></i></a>
		</li>
		<li class="brand">
			<img src="{{ asset('assets/app/img/logo-white.svg') }}" class="logo" alt="Logo KotakArsip">
		</li>
		<li class="search">
			<form action="">
				<input type="text" placeholder="Pencarian">
			</form>
		</li>
	</ul>
	<ul class="right-side">
		<li class="notif new-notif dropdown">
			<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" @click="notification"><i class="fa fa-bell animated swing infinite"></i> <span class="badge">2</span></a>
			<ul class="dropdown-menu pull-right">
				<li class="notif-title">
					<h3>Notifikasi</h3>
					<a href="#">Tandai semua sudah dibaca</a>
				</li>
				<li role="separator" class="divider"></li>

				<li class="unread">
					<a href="detail.html">
						<div class="img-profile" style="background: url('{{ asset('assets/app/img/users/2.png') }}')"></div>
						<span>
							Alfian Saputra membagikan <b>Kontrak Kerja</b> kepada Anda<br>
							<div class="btn btn-primary btn-sm">Buka File</div>
						</span>
					</a>
				</li>
				<li role="separator" class="divider"></li>

				<li class="unread">
					<a href="detail.html">
						<div class="img-profile" style="background: url('{{ asset('assets/app/img/users/3.jpg') }}')"></div>
						<span>
							Alfian Labeda membagikan <b>Hasil Rapat 12 Agustus 2017</b> kepada Anda<br>
							<div class="btn btn-primary btn-sm">Buka File</div>
						</span>
					</a>
				</li>
				<li role="separator" class="divider"></li>

				<li>
					<a href="detail.html">
						<div class="img-profile" style="background: url('{{ asset('assets/app/img/users/4.jpg') }}')"></div>
						<span>
							Mashuri Mansur mendisposisi surat masuk dari <b>CV. Media SAKTI</b> kepada Anda<br>
							<div class="btn btn-primary btn-sm">Buka File</div>
						</span>
					</a>
				</li>
				<li role="separator" class="divider"></li>
				
				<li class="text-center"><a href="notifikasi.html">Lihat semua pemberitahuan</a></li>
			</ul>
		</li>
		<li class="profile dropdown">
			<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				<div class="img-profile" style="background: url({{ asset('assets/app/img/users').'/'.Auth::user()->photo }})"></div>
				<span class="view-desktop-only">{{ Auth::user()->name }}</span> &nbsp;<i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="pengaturan-akun.html">Pengaturan Akun</a></li>
				<li><a href="bantuan.html">Bantuan</a></li>
				<li role="separator" class="divider"></li>
				<li>
					<a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Kelaur
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
				</li>
			</ul>
		</li>
	</ul>
</nav>