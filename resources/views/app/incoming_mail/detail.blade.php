<!doctype html>

<html lang="en">

<head>

	<meta name="keyword" content="">

	<meta name="description" content="">

	<meta name="author" content="">

	<meta property="og:image" content="assets/img/logo.svg" />

	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<title>Kotakarsip</title>

	<link rel="icon" sizes="16x16" href="assets/img/favicon.png" />

	<!-- Font Icon -->
	<link rel="stylesheet" href="assets/libs/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="assets/libs/font-icons/font-awesome/css/font-awesome.min.css">

	<!-- Bootstrap Vue -->
	<link type="text/css" rel="stylesheet" href="assets/css/bootstrap-vue.css"/>

	<!-- Custom css -->
	<link href="assets/css/kotakarsip.css" rel="stylesheet">

</head>


<body>

	<div id="app">
		<nav class="ka-nav ka-nav-detail">
			<ul class="left-side">
				<li class="back"><a href="index.html"><i class="fa fa-angle-left"></i> &nbsp;&nbsp;Surat Masuk</a></li>
			</ul>
			<ul class="right-side">
				<li>
					<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#disposisiModal">Disposisi</a>
					&nbsp;&nbsp;
					<a href="#" class="btn btn-default" id="favorite" @click="favorite"><i class="fa fa-star-o"></i></a>
				</li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="sunting.html">Sunting</a></li>
						<li><a href="#" class="text-danger">Hapus</a></li>
					</ul>
				</li>
			</ul>
		</nav>

		<section class="ka-body ka-body-detail">
			<div class="ka-main">
				<div v-for="val in json.incomingMail">
					<img v-for="image in val.files" :src="'assets/img/data-img/surat-masuk/' + image" alt="">
					<!-- <object data="assets/img/data-img/surat-masuk/dok-2.pdf" type=""></object> -->
				</div>
			</div>

			<aside class="ka-sidebar-detail">
				<div class="detail-info">
					<div class="select" v-for="val in json.incomingMail">
						<div class="item" v-if="val.from">
							<label>Asal Surat</label>
							<div class="value">{{ val.from }}</div>
						</div>
						<div class="item" v-if="val.reference_number">
							<label>Nomor Surat</label>
							<div class="value">{{ val.reference_number }}</div>
						</div>
						<div class="item" v-if="val.subject">
							<label>Perihal</label>
							<div class="value">{{ val.subject }}</div>
						</div>
						<div class="item" v-if="val.storage">
							<label>Penyimpanan Arsip</label>
							<div class="value">{{ val.storage }}</div>
						</div>
						<div class="item" v-if="val.share">
							<label>Disposisi</label>
							<div class="value">
								<ul class="list-unstyled">
									<li v-for="disposisi in val.share"><a href="">{{ disposisi.name }}</a></li>
								</ul>
							</div>
						</div>
						<div class="item" v-if="val.date">
							<label>Tanggal Masuk</label>
							<div class="value">{{ val.date }}</div>
						</div>
					</div>
				</div>
			</aside>
		</section>


		<!-- Modals -->
		<div class="modal fade modal-disposisi" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiLabelModal">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<form action="">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="disposisiLabelModal">Disposisi</h4>
						</div>
						<div class="modal-body">
							<table class="table">
								<tr>
									<td class="search" colspan="4"><input type="text" class="form-control" placeholder="Cari" v-model="search"></td>
								</tr>
								<tr v-for="val in filteredUsers">
									<td class="text-center"><input type="checkbox"></td>
									<td><div class="img-profile" v-bind:style="{ backgroundImage: 'url(assets/img/users/' + val.img + ')' }"></div></td>
									<td>
										<span class="name">{{ val.name }}</span><br>
										<span class="position">{{ val.position }}</span>
									</td>
								</tr>
							</table>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							<button class="btn btn-primary">Disposisi</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="assets/js/kotakarsip.js"></script>
	<script src="assets/vue/components/sidebar-detail.js"></script>
	<script src="assets/vue/surat-masuk.js"></script>
	<script>
		getDataIncomingMailDetail([
			// Detail Surat Masuk
			'data/surat_masuk_detail.json',
			// Pengguna
			'data/pengguna.json'],
			'incomingMail');
	</script>

</body>

</html>