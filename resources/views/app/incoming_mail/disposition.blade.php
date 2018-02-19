@extends('app.layouts.main')

@section('title', 'Riwayat Disposisi Surat Masuk')

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
		<div id="timeline">
			<ul class="timeline">
				<li class="tl-item" v-for="val in json.incomingMail">
					<div class="tl-wrap">
						<span class="tl-date" v-html="$options.filters.fromnow(val.date.date)"></span>
						<div class="tl-content panel padder b-a">
							<span class="arrow left pull-up"></span>
							<div v-html="val.user[0].name"></div>
							<span class="time" v-html="val.message"></span>
							<a :href="'{{ route('incoming_mail_disposition_delete') }}/' + val._id + '/' + val.user[0]._id.$oid + '/' + val.id_archieve"><span class="close" title="Hapus Akses">&times;</span></a>
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
		getDataIncomingMailDetail('{{ route('api_incoming_mail_detail_disposition', ['id' => $archieve->_id]) }}', 'incomingMail');

		$('.ka-body').addClass('ka-body-single');
	</script>
@endsection