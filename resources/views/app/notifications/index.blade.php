@extends('app.layouts.main')

@section('title', 'Pemberitahuan')

@section('contents')
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li>Pemberitahuan</li>
			</ul>
		</div>

		<hr>
		<div id="timeline">
			<ul class="timeline">
				@foreach ($notifications as $notif)
					<li class="tl-item">
						<div class="tl-wrap" style="margin-left: 5em">
							<?php
								$date_timestamp = strtotime($notif->created_at);
								$date = date('d-M', $date_timestamp); 
								$time = date('H:i', $date_timestamp); 
							?>
							<span class="tl-date">{{ $date }}</span>
							<div class="tl-content panel padder b-a">
								<span class="arrow left pull-up"></span>
								@if ($notif->read === 0)
									<a href="{{ $notif->link }}?read={{ $notif->_id }}" style="color: #333"><i class="fa fa-circle color-primary"></i> &nbsp;{!! $notif->message !!}</a>
									<span class="time"> &nbsp; &nbsp; &nbsp;&nbsp;{{ $time }}</span>
								@else
									<a href="{{ $notif->link }}?read={{ $notif->_id }}" style="color: #333">{!! $notif->message !!}</a>
									<span class="time">{{ $time }}</span>
								@endif
							</div>
						</div>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endsection

@section('registerscript')
	<link rel="stylesheet" href="{{ asset('assets/app/libs/timeline/jquery.timeline.css') }}">
	<script src="{{ asset('assets/app/libs/timeline/jquery.timeline.js') }}"></script>
@endsection