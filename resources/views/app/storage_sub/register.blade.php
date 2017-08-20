@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Silahkan isi penyimpanan anda</div>

                <div class="panel-body">
                    <div class="col-md-12">
                        <label for="">{{ $storage->name }}</label>
                    </div>
                    <br>
                    <br>
                    <form action="{{ route('storage_sub_store') }}" class="form-inline" method="POST">
                        {{ csrf_field() }}

                        <input type="hidden" name="id_storage" value="{{ $storage->_id }}">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nama Rak" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default">Tambah</button>
                    </form>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <td width="10%">No</td>
                                <td>Nama</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($storage_sub as $sub)
                                <tr>
                                    <td>1</td>
                                    <td>{{ $sub->name }}</td>
                                    <td>
                                        <form action="{{ route('storage_sub_delete', ['id' => $sub->_id ]) }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="submit" name="submit" value="Hapus">
                                            <input type="hidden" type="_method" value="DELETE">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">
                        <div class="col-sm-offset-10 col-sm-10">
                            <a href="/" class="btn btn-primary">Finish</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
