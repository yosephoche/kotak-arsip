@extends('app.layouts.main')

@section('title', 'Surat Keluar')

@section('contents')
	@if ( Session::has('success') ) 
		<div class="alert-custom alert-custom-success"><i class="fa fa-check-circle"></i>{{ session('success') }}</div>
	@endif

	<?php
		$ascFrom = 'true';
		if (@$_GET['sort'] == 'to' AND @$_GET['asc'] == 'true') {
			$ascFrom = 'false';
		}

		$ascSubject = 'true';
		if (@$_GET['sort'] == 'subject' AND @$_GET['asc'] == 'true') {
			$ascSubject = 'false';
		}
	?>

	<div class="ka-main">
		<div class="breadcrumbs">
			<ul class="list-inline">
				<li><a href="{{ route('outgoing_mail') }}">Surat Keluar</a></li>
			</ul>
		</div>

		@if (Auth::user()->id_company == '')
			<div class="alert alert-warning">Anda belum mendaftarkan/bergabung perusahaan Anda, <a href="{{ route('setting') }}">klik disini!</a></div>
		@endif

		@if (Auth::user()->phone == '')
			<div class="alert alert-warning">Silahkan lengkapi profil Anda, <a href="{{ route('setting') }}?tab=account">klik disini!</a></div>
		@endif
		
		<table class="table table-hover" v-if="json.outgoingMail != ''">
			<thead>	
				<tr>
					<th class="{{ @$_GET['sort'] == 'to' ? 'sort' : '' }} no-border">
						<a href="{{ route('outgoing_mail', ['sort' => 'to', 'asc' => $ascFrom]) }}">Tujuan Surat</a>
						@if (@$_GET['sort'] == 'to')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
					<th class="{{ @$_GET['sort'] == 'subject' ? 'sort' : '' }} no-border view-tablet-only">
						<a href="{{ route('outgoing_mail', ['sort' => 'subject', 'asc' => $ascSubject]) }}">Perihal</a>
						@if (@$_GET['sort'] == 'subject')
							@if (@$_GET['asc'] == 'true')
								<i class="fa fa-angle-down i-sort"></i>
							@elseif (@$_GET['asc'] == 'false')
								<i class="fa fa-angle-up i-sort"></i>
							@endif
						@endif
					</th>
					<th class="view-tablet-only no-border">Dibagikan</th>
					<th class="view-tablet-only no-border" colspan="2">Tanggal Keluar</th>
				</tr>
			</thead>
			<tbody class="item no-border" v-for="val in json.outgoingMail" v-on:click="detailSidebar(val, $event)">

				<!-- Original File -->
				<tr v-if="val.id_original === null">
					<td><a :href="'{{ route('outgoing_mail_detail') }}/' + val._id" v-html="val.to"></a></td>
					<td class="view-tablet-only"><div class="ellipsis" style="max-width: 200px"><span v-html="val.subject"></span></div></td>
					<td class="view-tablet-only" v-if="val.share != ''" width="150px" style="padding-top: 10px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index < 3 && disposisi != null">
								<b-tooltip :content="disposisi.name" placement="bottom">
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + disposisi.photo + ')' }" v-if="disposisi.photo != '' && disposisi.photo != null"></div>
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.share" class="img-disposisi" v-if="index == 0 && val.share.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.share.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a :href="'{{ route('outgoing_mail_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a href="#" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" v-on:click="idDispositionArray(val.share)">Bagikan</a></li>
							<li v-if="val.share.length > 0"><a v-bind:href="'{{ route('outgoing_mail_shared_history') }}/' + val._id">Riwayat Disposisi</a></li>
							<li v-if="val.id_original === null"><a :href="'{{ route('outgoing_mail_move') }}/' + val._id">Sunting</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" :data-id="val._id" class="text-danger">Hapus</a></li>
						</ul>
					</td>
				</tr>

				<!-- Shared File -->
				<tr v-else>
					<td>
						<span v-if="val.share_info_shared[val.share_info_shared.findIndex(x => x.share_to.$oid == '{{ Auth::user()->_id }}')].read == 0">
							<i class="fa fa-circle color-primary"></i> &nbsp;<b><a :href="'{{ route('outgoing_mail_detail') }}/' + val._id + '?read_direct=true'" v-html="val.to"></a></b>
						</span>
						<span v-else>
							<a :href="'{{ route('outgoing_mail_detail') }}/' + val._id" v-html="val.to"></a>
						</span>
					</td>
					<td class="view-tablet-only" v-html="val.subject"></td>
					<td class="view-tablet-only" v-if="val.shared != ''" width="150px" style="padding-top: 10px">
						<ul class="list-unstyled disposisi">
							<li v-for="(disposisi, index) in val.shared" class="img-disposisi" v-if="index < 3 && disposisi != null">
								<b-tooltip :content="disposisi.name" placement="bottom">
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + disposisi.photo + ')' }" v-if="disposisi.photo != '' && disposisi.photo != null"></div>
									<div class="img-disposisi" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
								</b-tooltip>
							</li>
							<li v-for="(disposisi, index) in val.shared" class="img-disposisi" v-if="index == 0 && val.shared.length > 3">
								<div class="img-disposisi" v-html="'+' + (val.shared.length - 3).toString()" style="background-color: #1079ff; color: #fff"></div>
							</li>
						</ul>
					</td>
					<td v-else>-</td>
					<td class="view-tablet-only" v-html="$options.filters.moment(val.date.$date.$numberLong)"></td>
					<td class="text-right dropdown">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
						<ul class="dropdown-menu pull-right">
							<li><a :href="'{{ route('outgoing_mail_detail') }}/' + val._id">Lihat Detail</a></li>
							<li><a href="#" data-toggle="modal" data-target="#disposisiModal" :data-id="val._id" :data-owner="val.id_owner" v-on:click="idDispositionArray(val.shared)">Bagikan</a></li>
							<li><a type="button" data-toggle="modal" data-target="#deleteModal" :data-id="val._id" class="text-danger">Hapus</a></li>
						</ul>
					</td>
				</tr>

			</tbody>

		</table>

		<div class="text-center" v-else>
			<hr>
			<br>
			<br>
			<br>
			<br>
			<img src="{{ url('assets/app/img/icons') }}/no_file.png" alt="" width="300px">
			<br>
			<br>
			<br>
			Belum ada surat keluar
		</div>

		<?php
			$link = GlobalClass::removeGetParam('page');
		?>

		@if ($archieve->lastPage() > 1)
		<div class="text-center">
			<hr class="m-t-0">
			<ul class="pagination-custom">
				<li class="{{ ($archieve->currentPage() == 1) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() - 1) }}">Sebelumnya</a>
				</li>
				@for ($i = 1; $i <= $archieve->lastPage(); $i++)
					<li class="{{ ($archieve->currentPage() == $i) ? ' active' : '' }}">
						<a href="{{ $link.'&page='.$i }}">{{ $i }}</a>
					</li>
				@endfor
				<li class="{{ ($archieve->currentPage() == $archieve->lastPage()) ? ' hide' : '' }}">
					<a href="{{ $link.'&page='.($archieve->currentPage() + 1) }}" >Selanjutnya</a>
				</li>
			</ul>
		</div>
		@endif
	</div>

	@if (Auth::user()->id_company != null)
	<label for="inputFileSubmit" class="btn-add-mobile">+</label>
	@endif
	<aside class="ka-sidebar-detail">
		<form action="{{ route('outgoing_mail_upload') }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="file" v-on:change="inputFileSubmit" id="inputFileSubmit" name="image" class="hide" accept=".jpg, .png, .jpeg, .pdf">
			@if (Auth::user()->id_company != null)	
			<label for="inputFileSubmit" class="btn btn-primary btn-block">Tambah</label>
			@endif
		</form>


		<br>

		<div class="detail-info">

			<div v-if="detail !== ''">
				<detail :detail="detail"></detail>
			</div>
			<div v-else>
				<no-select></no-select>
			</div>

		</div>
	</aside>		
@endsection

@section('modal')
	<!-- Modals -->
	<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('outgoing_mail_shared') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="disposisiLabelModal">Bagikan</h4>
					</div>
					<div class="modal-body" style="border-top: 1px solid #ddd">
						<input type="hidden" name="id">
						<div class="col-md-6 view-tablet-only">
							<br>
							<textarea name="message" rows="13" placeholder="Tambahkan pesan (opsional)" class="form-control no-border no-padding no-resize" onchange="$('.message-fill').val($(this).val())"></textarea>
						</div>
						<div class="col-md-6 no-padding" style="border-left: 1px solid #ddd; height: 299px; overflow-y: auto">
							<table class="table">
								<tr>
									<td class="search" colspan="4"><input type="text" class="form-control" placeholder="Cari" v-model="search"></td>
								</tr>
								<tr v-for="(val, index) in filteredUsers" v-if="val._id != '{{ Auth::user()->_id }}' && dispositionArray.indexOf(val._id) == -1">
									<td class="text-center">
										<div v-if="checked.indexOf(val._id) !== -1">
											<input type="checkbox" :name="'share['+index+']'" :value="val._id" @change="check(val._id)" :id="val._id" class="checked" checked>
										</div>
										<div v-else>
											<input type="checkbox" :name="'share['+index+']'" :value="val._id" @change="check(val._id)" :id="val._id" class="unchecked">
										</div>
										<input type="text" :name="'date['+index+']'" value="{{ date('d/m/Y') }}" class="hide">
										<input type="text" :name="'message['+index+']'" class="message-fill hide" value="">
									</td>
									<td>
										<div class="img-profile" :style="{ backgroundImage: 'url({{ asset('assets/app/img/users') }}/thumb-' + val.photo + ')' }" v-if="val.photo != '' && val.photo != null"></div>
										<div class="img-profile" :style="{ backgroundImage: 'url({{ asset('assets/app/img/icons') }}/user.png)' }" v-else></div>
									</td>
									<td>
										<span class="name" v-html="val.name"></span><br>
										<span class="position" v-html="val.position"></span>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button class="btn btn-primary">Bagikan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabelModal">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<form action="{{ route('outgoing_mail_delete') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabelModal">Hapus</h4>
					</div>
					<div class="modal-body">
						<input type="text" class="hidden" id="delete-val" value="">
						<input type="hidden" name="id">
						Apakah Anda yakin ingin menghapus data ini?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Ya, hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('template')
	<!-- No select data in table -->
	<template id="no-select">
		<div class="no-select text-center">
			<img src="{{ asset('assets/app/img/icons/select_file.png') }}" alt="Pilih salah satu">
			<p>Pilih salah satu data untuk melihat detail</p>
		</div>
	</template>

	<!-- Detail after select data in table -->
	<template id="sidebar-detail">
		<div>
			<div class="select">
				<div v-for="message in detail.share_info_shared" v-if="message.share_to.$oid == '{{ Auth::user()->id }}'">
					<div class="item item-highlight" v-if="message.message != null">
						<label>Pesan untuk Anda</label>
						<div class="value" v-html="message.message"></div>
					</div>
					<div class="item" v-if="detail.id_owner != null">
						<label>Dibagikan oleh</label>
						<div class="value" v-html="detail.owner[detail.owner.length - 1].name"></div>
					</div>
				</div>
				<div class="item" v-if="detail.to">
					<label>Tujuan Surat</label>
					<div class="value" v-html="detail.to"></div>
				</div>
				<div class="item" v-if="detail.reference_number">
					<label>Nomor Surat</label>
					<div class="value ellipsis" v-html="detail.reference_number"></div>
				</div>
				<div class="item" v-if="detail.subject">
					<label>Perihal</label>
					<div class="value" v-html="detail.subject"></div>
				</div>
				<div class="item" v-if="detail.storage != ''">
					<label>Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storage"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.storagesub != ''">
					<label>Sub Penyimpanan Arsip</label>
					<div class="value">
						<ul class="list-unstyled">
							<li v-for="storage in detail.storagesub"><div class="value" v-html="storage.name"></div></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.folder">
					<label>Folder</label>
					<div class="value"><a :href="'{{ route('folder') }}/' + detail.folder" v-html="detail.folder"></a></div>
				</div>
				<div class="item" v-if="detail.share != ''">
					<label>Dibagikan</label>
					<div class="value value-disposition">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.share"><a :href="'{{ route('outgoing_mail_shared_history') }}/' + detail._id" v-html="disposisi.name"></a></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.shared != ''">
					<label>Dibagikan</label>
					<div class="value value-disposition">
						<ul class="list-unstyled">
							<li v-for="disposisi in detail.shared" v-html="disposisi.name"></li>
						</ul>
					</div>
				</div>
				<div class="item" v-if="detail.date">
					<label>Tanggal Keluar</label>
					<div class="value" v-html="$options.filters.moment(detail.date.$date.$numberLong)"></div>
				</div>
			</div>

			<div class="attachment">
				<span v-html="detail.files.length + ' file terlampir'"></span>
				<a :href="'{{ route('outgoing_mail_detail') }}/' + detail._id" class="btn btn-default btn-block">Lihat Detail</a>
			</div>
		</div>
	</template>
@endsection

@section('registerscript')
	<script src="{{ asset('assets/app/vue/surat-keluar.js') }}"></script>
	<script>
		<?php
			/* Sort By */
			$sortKey = '_id';
			if (@$_GET['sort'] == 'to') {
				$sortKey = 'to';
			} else if (@$_GET['sort'] == 'subject') {
				$sortKey = 'subject';
			}

			/* Ascending or Descending */
			$asc = 'false';
			if (@$_GET['asc'] == 'true') {
				$asc = 'true';
			}

			/* Pagination */
			$page = 1;
			if (@$_GET['page']) {
				$page = $_GET['page'];
			}

		?>
		getDataOutgoingMail('{{ route("api_outgoing_mail", ["sort" => $sortKey]) }}&asc={{ $asc }}{{ $page != 1 ? "&page=".$page : "" }}', 'outgoingMail');

		$('#disposisiModal').on('show.bs.modal', function (e) {
			/* Reset Form */
				$(this).find('textarea').val('');
				$(this).find('input[type="checkbox"]').prop('checked', false);
				
			/* Get ID */
				var id = $(e.relatedTarget).data('id');
				$(this).find('input[name="id"]').val(id);

			/* Remove owner mail from disposition */
				var id_owner = $(e.relatedTarget).data('owner');
				if (typeof id_owner !== "undefined") {
					$('#' + id_owner).closest('tr').addClass('hide');
				} else {
					$(this).find('tr').removeClass('hide');
				}
				/* if search */
					$('.search input').keyup(function() {
						if (typeof id_owner !== "undefined") {
							$('#' + id_owner).closest('tr').addClass('hide');
						} else {
							$(this).find('tr').removeClass('hide');
						}
						/* check unchecked */
							$('.checked').prop('checked', true);
							$('.unchecked').prop('checked', false);
					});

			$('#link_history').attr('href', '{{ route('outgoing_mail_shared_history') }}/' + id);
			
			/* Fill val date */
				var now = moment().format('DD/MM/YYYY');
				var status = true;
				$('.val-check').attr('data-date', now);
				$('.val-check').change(function() {
					if (status == true) {
						status = false;
					} else {
						status = true;
					}
					var id = $(this).data('id');
					var date = $(this).data('date');

					$(this).parent().find('input.val-check').val(status == false ? '-' : id);
					$(this).parent().find('input.val-date').val(status == false ? '-' : date);
					$(this).parent().find('input.val-message').val('');
					/* $(this).val(id); */
				});
		});

		$('#deleteModal').on('show.bs.modal', function (e) {
			var id = $(e.relatedTarget).data('id');
			$(this).find('input[name="id"]').val(id);
		});

		/* 3 mean 3second */
			alertTimeout(3);
	</script>
@endsection