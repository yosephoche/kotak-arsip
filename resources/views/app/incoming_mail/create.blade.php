@extends('app.layouts.main')

@section('title', 'Surat Masuk')

@section('contents')
	<div class="body">
		<div class="body-action">
			<div class="sort">
				Urutkan berdasarkan&nbsp;
				<label class="select-text">
					<select>
						<option value="">Terbaru</option>
						<option value="">Asal Surat</option>
					</select>
				</label>
			</div>
		</div>

		<ul class="list-content">
			<form action="{{ route('surat_masuk_store') }}" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-md-6">
						<div class="form-group hidden">
							<input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">
							<input type="hidden" name="id_company" value="{{ Auth::user()->id_company }}">
							<input type="hidden" name="type" value="incoming_mail">
						</div>

						<div class="form-group">
							<label for="">Asal Surat</label>
							<input id="form-company" name="from" type="text" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
						</div>

						<div class="form-group">
							<label for="">No. Surat</label>
							<input id="form-nosurat" name="reference_number" type="text" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
						</div>

						<div class="form-group">
							<label for="">Perihal</label>
							<input id="form-perihal" name="subject" type="text" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
						</div>

						<div class="form-group">
							<label for="">Tanggal Masuk</label>
							<input id="form-tgl" type="text" name="date" placeholder="dd/mm/yyyy" class="form-control datepicker" data-date-end-date="0d" data-validation="required birthdate" data-validation-format="dd/mm/yyyy" data-validation-error-msg-required="* Wajib diisi" data-validation-error-msg-birthdate="* Mohon masukkan tanggal dengan format dd/mm/yyyy">
						</div>

						<div class="form-group">
							<label for="">Penyimpanan Fisik</label>
							<label class="select-box">
								<select id="form-rak" name="storage" class="form-control">
									<option value="Rak 201">Rak 201</option>
									<option value="Rak 202">Rak 202</option>
								</select>
							</label>
						</div>

						<div class="form-group">
							<label for="">Share</label><br>
							<select id="form-disposisi" name="share[]" class="form-control testsel" multiple="multiple" placeholder="Kepada:" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
								@foreach ($user as $u)
									<option value="{{ $u->_id }}">{{ $u->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="">Keterangan</label>
							<textarea id="form-ket" name="information" rows="4" class="form-control"></textarea>
						</div>

						<div class="form-group">
							<label for="">Hasil Scan</label>
							<input type="file" name="files[]" class="form-control" accept=".jpg, .png, .pdf" multiple>
						</div>

						<div class="form-footer">
							<a href="{{ route('surat_masuk') }}" class="btn btn-default close-form">Kembali</a>
							<button class="btn btn-primary">Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</ul>
	</div>
@endsection