@extends('app.layouts.main')

@section('title', 'Riwayat Berbagi Berkas')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">...</a>
					<ul class="dropdown-menu">
						<li><a href="{{ route('file') }}">Berkas</a></li>
						<li><a href="{{ route('file_detail', ['id' => $archieve->_id]) }}">{{ $archieve->name }}</a></li>
					</ul>
				</li>
				<li>Riwayat Berbagi</li>
			</ul>
		</div>

		<hr>
		<div id="timeline">
			<ul class="timeline">
				<li class="tl-item" v-for="val in json.files">
					<div class="tl-wrap">
						<span class="tl-date" v-html="$options.filters.fromnow(val.date.date)"></span>
						<div class="tl-content panel padder b-a">
							<span class="arrow left pull-up"></span>
							<div v-html="val.user[0].name"></div>
							<span class="time" v-html="val.message"></span>
							<a :href="'{{ route('file_shared_delete') }}/' + val._id + '/' + val.user[0]._id.$oid + '/' + val.id_archieve"><span class="close" title="Hapus Akses">&times;</span></a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/berkas.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/app/libs/timeline/jquery.timeline.css') }}">
	<script src="{{ asset('assets/app/libs/timeline/jquery.timeline.js') }}"></script>
	<script>
		getDataFilesDetail('{{ route('api_file_detail_shared', ['id' => $archieve->_id]) }}', 'files');

		$('.ka-body').addClass('ka-body-single');
	</script>
@endsection