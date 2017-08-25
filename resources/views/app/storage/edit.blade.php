@extends('app.layouts.main')

@section('title', 'Storage')

@section('contents')
	<div class="body">
		<form action="{{ route('storage_update', ['id' => $storage->_id]) }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="id_company" value="{{ Auth::user()->id_company }}">
			<input type="hidden" name="_method" value="put">
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Type</label>
						<select name="type" class="form-control">
							<?php
								$type = ["filling_cabinet"=>"Filling Cabinet", "rolling_cabinet"=>"Rolling Cabinet", "rak_arsip"=>"Rak Arsip", "card_cabinet"=>"Card Cabinet"];
								foreach ($type as $t => $t_value) {
							?>
								<option value="{{ $t }}" {{ $t == $storage->type ? 'selected' : '' }}>{{ $t_value }}</option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="">Nomor Rak:</label>
						<input type="text" class="form-control" name="name" value="{{ $storage->name }}" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
					</div>

					<button class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</form>
	</ul>
</div>
@endsection