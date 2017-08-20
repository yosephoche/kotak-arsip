@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Silahkan Pilih Jenis Penyimpanan</div>

                <div class="panel-body">
                    <form action="{{ route('storage_store') }}" class="form-horizontal" method="POST">
                        {{ csrf_field() }}
                        <div class="" align="center">
                            <label class="radio-inline">
                                <input type="radio" name="type" value="filling_cabinet">Filling Cabinet
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" value="rolling_cabinet">Rollling Cabinet
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" value="rak_arsip">Rak Arsip 
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="type" value="card_cabinet">Card Cabinet
                            </label>
                            <br>
                            <br>
                            <div class="col-md-12">
                                <label for="">Storage Name</label>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="col-sm-offset-3 col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
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
