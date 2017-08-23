@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Silahkan Pilih Jenis Arsip</div>

                <div class="panel-body">
                    <form action="{{ route('archieve_type_store') }}" class="form-horizontal" method="POST">
                        {{ csrf_field() }}
                        <div class="" align="center">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="surat_masuk"> Surat Masuk
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="surat_keluar"> Surat Keluar
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="arsip_dokumen"> Arsip Dokumen
                            </label>
                            <br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="surat_pribadi"> Surat Pribadi
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="surat_niaga"> Surat Niaga
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="archieve[]" value="surat_resmi"> Surat Resmi
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-10 col-sm-10">
                                <button type="submit" class="btn btn-default">Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
