@extends('app.layouts.main')

@section('title', 'Anggota')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif
	
	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('member') }}">Arsip Kepegawaian</a></li>
			</ul>
		</div>

		<table class="table table-hover">
			<tr>
				<th class="sort" @click="sortBy('name', $event)">Nama Lengkap <i class="fa fa-angle-down i-sort"></i></th>
				<th class="view-tablet-only" @click="sortBy('position', $event)">Jabatan</th>
			</tr>
			<tr class="item va-mid" v-for="val in orderedUsers" @click="detailSidebar(val, $event)">
				<td>
					<a :href="'{{ route('employee_files') }}/' + val._id" class="disposisi ellipsis">
						<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/users')}}/thumb-'+ val.photo +')' }" v-if="val.photo != '' && val.photo != null"></div>
						<div class="img-disposisi" v-bind:style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
						&nbsp;&nbsp;&nbsp;<span v-html="val.name"></span>
					</a>
				</td>
				<td class="view-tablet-only" v-html="val.position"></td>
			</tr>
		</table>
	</div>

	<aside class="ka-sidebar-detail">
		<div class="detail-info">

		</div>
	</aside>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/pengguna.js') }}"></script>
	<script>
		getDataUsers('{{ route("api_member") }}', 'users');

		// 3 mean 3second
		alertTimeout(3);
	</script>
@endsection
