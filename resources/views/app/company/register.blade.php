@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="{{ route('company_store') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">
                        <input type="hidden" name="status" value="super_admin">

                        <div class="col-md-12">
                            <label for="">Buat Perusahaan</label>
                        </div>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Daftar</button>
                        </div>
                    </form>

                    <form action="{{ route('company_code') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <label for="">atau Masukkan Kode Perusahaan</label>
                        </div>
                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control" name="code" value="{{ old('code') }}" required autofocus>
                                @if ( Session::has('failed') ) 
                                    <span class="help-block">
                                        <strong>{{ session('failed') }}</strong>
                                    </span>
                                @endif
                                @if ($errors->has('code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection