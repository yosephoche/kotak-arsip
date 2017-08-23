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
                    <img src="{{ asset('assets/app/img/logo.svg') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
                </li>
            </ul>
            <ul class="right-side">
                <li><a href="">Laporkan masalah</a></li>
                <li>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </li>
            </ul>
        </nav>

        <div class="ka-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-4">
                        <img src="{{ asset('assets/app/img/login.svg') }}" alt="" width="98%" style="margin-top: 60px">
                    </div>
                    <div class="col-md-4">
                        <h1>Masuk</h1>

                        <form action="{{ route('login') }}" class="form-custom row" method="POST">
                            {{ csrf_field() }}
                            @if ( $errors->has('email') or $errors->has('password') )
                                <div class="alert-top alert alert-danger text-center">Email atau kata sandi salah</div>
                            @endif
                            <div class="form-group">
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="Alamat Email">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Kata Sandi">
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> &nbsp;Tetap masuk
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-block">Masuk</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

     @include('app.layouts.partial.script')

</body>

</html>