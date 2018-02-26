<!doctype html>

<html lang="en">

<head>

    @include('app.layouts.partial.meta')

    <title>Kotakarsip</title>

    @include('app.layouts.partial.style')

</head>


<body class="page-login">

    <div id="app">
        <nav class="ka-nav ka-nav-detail">
            <ul class="left-side">
                <li class="brand">
                    <img src="{{ asset('assets/app/img/logo.png') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
                </li>
            </ul>
            <ul class="right-side">
                <li><a href="">Laporkan masalah</a></li>
                <li>
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                </li>
            </ul>
        </nav>

        <div class="ka-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-4">
                        <img src="{{ asset('assets/app/img/login.png') }}" alt="" width="98%" style="margin-top: 60px">
                    </div>
                    <div class="col-md-4">
                        <h1>Daftar</h1>

                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="email_status" value="pending">
                            
                            <div class="form-group">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nama Lengkap" required>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div class="form-group">
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="Alamat Email" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulang Kata Sandi" required>
                            </div>
                            <div class="form-group">
                                <input type="checkbox"> &nbsp;Saya setuju dengan ketentuan <a href="">KotakArsip</a>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-block">Daftar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   @include('app.layouts.partial.script')

</body>

</html>