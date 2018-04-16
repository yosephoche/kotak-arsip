<nav class="ka-nav">
	<ul class="left-side">
		<li class="nav-toggle" @click="navToggle">
			<a href="#"><i class="fa fa-bars"></i></a>
		</li>
		<li class="brand">
			<a href="{{ route('incoming_mail') }}"><img src="{{ asset('assets/app/img/logo-white.png') }}" class="logo" alt="Logo KotakArsip"></a>
		</li>
		<li class="search">
			<form action="{{ route('search') }}" id="typeahead">
				<input class="typeahead" name="q" type="text" placeholder="Pencarian">
			</form>
		</li>
	</ul>
	<ul class="right-side">
		<?php
			$count = App\Notifications::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->orderBy('_id', 'desc')->where('read', 0)->count();
			$notifications = App\Notifications::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->orderBy('_id', 'desc')->take(9)->get();
			if ($count > 9) {
				$notifications = App\Notifications::where('id_user', GlobalClass::generateMongoObjectId(Auth::user()->_id))->orderBy('_id', 'desc')->take(9)->get();
			}
		?>
		<li class="notif dropdown {{ $count > 0 ? 'new-notif' : '' }}">
			@if ($count > 9)
				<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell {{ $count > 0 ? 'animated swing infinite' : '' }}"></i> {!! $count > 0 ? '<span class="badge">9+</span>' : '' !!}</a>
			@else
				<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell {{ $count > 0 ? 'animated swing infinite' : '' }}"></i> {!! $count > 0 ? '<span class="badge">'.$count.'</span>' : '' !!}</a>
			@endif

			<ul class="dropdown-menu pull-right">
				<li class="notif-title">
					<h3>Notifikasi</h3>
					<a href="{{ route('notifications_readall') }}">Tandai semua sudah dibaca</a>
				</li>
				
				@forelse ($notifications as $notif)
					<li class="{{ $notif->read == 0 ? 'unread' : '' }}">
						<a href="{{ $notif->link }}?read={{ $notif->_id }}">
							<?php $user = App\User::where('_id', GlobalClass::generateMongoObjectId($notif->from))->select('photo')->first(); ?>
							<div class="img-profile" style="background: url({{ asset('assets/app/img/users').'/thumb-'.$user->photo }})"></div>
							<span>
								{!! $notif->message !!}<br>
								<div class="btn btn-primary btn-sm">Buka File</div>
							</span>
						</a>
					</li>
				@empty
					<li class="text-center empty">
						<img src="{{ asset('assets/app/img/icons') }}/notification.png" alt="Pratinjau belum mendukung format berkas" width="100px"><br><br>
					</li>
				@endforelse
				
				<li class="text-center"><a href="{{ route('notifications') }}">Lihat semua pemberitahuan</a></li>
			</ul>
		</li>
		<li class="profile dropdown">
			<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				@if (Auth::user()->photo != '')
					<div class="img-profile" style="background: url({{ asset('assets/app/img/users').'/thumb-'.Auth::user()->photo }})"></div>
				@else
					<div class="img-profile" style="background: url({{ asset('assets/app/img/icons/user-white.png') }})"></div>
				@endif
				<span class="view-desktop-only">{{ Auth::user()->name }}</span> &nbsp;<i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu pull-right">
				<?php $status = Auth::user()->status ?>
				@if ($status == 'admin' && Auth::user()->email != 'demo@kotakarsip.com')
					<li><a href="{{ route('status_capacity') }}">Kapasitas</a></li>
				@endif
				<li><a href="{{ route('setting', ['tab' => 'account']) }}">Pengaturan Akun</a></li>
				<li><a href="{{ route('help') }}">Bantuan</a></li>
				<li role="separator" class="divider"></li>
				<li>
					<a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Keluar
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
				</li>
			</ul>
		</li>
	</ul>
</nav>