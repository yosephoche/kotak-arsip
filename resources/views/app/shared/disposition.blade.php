@extends('app.layouts.main')

@section('title', 'Riwayat Disposisi')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">...</a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('incoming_mail') }}">Surat Masuk</a></li>
						<li><a href="{{ route('incoming_mail_detail', ['id' => $archieve->_id]) }}">{{ $archieve->from }}</a></li>
					</ul>
				</li>
				<li>Riwayat Disposisi</li>
			</ul>
		</div>

		<hr>
		<div id="timeline" v-for="val in json.incomingMail">
			<ul class="timeline">
				<li class="tl-item" v-for="disposisi in val.share">
					<div class="tl-wrap">
						<span class="tl-date" v-html="$options.filters.moment(disposisi.date.$date.$numberLong)"></span>
						<div class="tl-content panel padder b-a">
							<span class="arrow left pull-up"></span>
							<div v-html="disposisi.user[0].name"></div>
							<span class="time" v-html="disposisi.message"></span>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/surat-masuk.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/app/libs/timeline/jquery.timeline.css') }}">
	<script src="{{ asset('assets/app/libs/timeline/jquery.timeline.js') }}"></script>
	<script>
		getDataIncomingMailDetail('{{ route('api_incoming_mail_detail', ['id' => $archieve->_id]) }}', 'incomingMail');

		$('.ka-body').addClass('ka-body-single');
	</script>
@endsection