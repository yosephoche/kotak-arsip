<!doctype html>

<html lang="en">

<head>

    <meta name="keyword" content="">

    <meta name="description" content="">

    <meta name="author" content="">

    <meta property="og:image" content="{{ url('/resources/assets/app') }}/img/logo.svg" />

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kotakarsip</title>

    <link rel="icon" sizes="16x16" href="{{ url('/resources/assets/app') }}/img/favicon.png" />

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{ url('/resources/assets/app') }}/libs/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="{{ url('/resources/assets/app') }}/libs/font-icons/font-awesome/css/font-awesome.min.css">
    
    <!-- Libs css -->
    <link rel="stylesheet" href="{{ url('/resources/assets/app') }}/libs/jquery-ui/jquery-ui.css">
    <link href="{{ url('/resources/assets/app') }}/libs/sumoselect/sumoselect.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ url('/resources/assets/app') }}/libs/datepick/jquery.datepick.css">

    <!-- Custom css -->
    <link href="{{ url('/resources/assets/app') }}/css/kotakarsip.css" rel="stylesheet">

</head>


<body>

    <section class="login">
        <form action="{{ route('login') }}" class="form-custom row" method="POST">
            {{ csrf_field() }}
            <div class="text-center brand">
                <br>
                <img src="{{ url('/resources/assets/app') }}/img/logo.svg" alt="Logo Kotakarsip">
                <br><br><br>
            </div>
            <div class="col-md-12">
                @if ( $errors->has('email') or $errors->has('password') )
                    <div class="alert-top alert alert-danger text-center">Email atau kata sandi salah</div>
                @endif
                <div class="form-group">
                    <label class="form-label" for="">Nama Pengguna / Email</label>
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
                </div>
                <div class="form-group">
                    <label class="form-label" for="">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" data-validation="required" data-validation-error-msg-required="* Wajib diisi">
                </div>
                
                <small class="color-gray" style="margin-bottom: 10px; display: block;"><i>Jangan dicentang jika ini komputer publik</i></small>

                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="checkbox-single">
                            <input type="checkbox" id="check-1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="check-1"><i class="fa fa-check"></i></label>
                            <label class="label-custom" for="check-1">Tetap masuk</label>
                        </div>
                    </div>
                    <div class="form-group col-md-6 text-right">
                        <a href="{{ route('password.request') }}">Forgot Password</a>
                    </div>
                </div>
                
                <button class="btn btn-info btn-block" lang-id="Masuk">Login</button>

                <br>
            </div>
        </form>
    </section>

    <ul class='right-click right-click-doc'>
        <li><a href="index.html">Kembali</a></li>
    </ul>

    <script src="{{ url('/resources/assets/app') }}/js/kotakarsip.js"></script>

</body>

</html>
                    