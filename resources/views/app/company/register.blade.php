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
            <div class="brand brand-center">
                <img src="{{ asset('assets/app/img/logo.png') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
            </div>
        </nav>

        <div class="ka-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-md-4">
                        <img src="{{ asset('assets/app/img/company.png') }}" alt="" width="98%" style="margin-top: 60px">
                    </div>
                    <div class="col-md-4">
                        <h1>Perusahaan</h1>

                        <form action="{{ route('company_store') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_user" value="{{ Auth::user()->_id }}">

                            <p>Masukkan <b data-toggle="tooltip" data-placement="bottom" title="Silahkan minta kode perusahaan Anda pada Admin Perusahaan">kode perusahaan</b> Anda!</p>
                            <div class="form-group">
                                <input type="text" id="company_code" name="company_code" class="form-control" placeholder="Kode Perusahaan">
                            </div>
                            <p>Perusahaan Anda belum terdaftar pada KotakArsip? Silahkan masukkan nama perusahaan Anda!</p>
                            <div class="form-group">
                                <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Nama Perusahaan">
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-block">Lanjutkan &nbsp;<i class="fa fa-angle-double-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/app/js/kotakarsip.js') }}"></script>

    <script>
        $('input').keyup(function() {

            /* Company Code filled */
            var company_code = $('#company_code').val();
            if (company_code !== '') {
                $('#company_name').attr('disabled', 'disabled');
            } else {
                $('#company_name').removeAttr('disabled');
            }

            /* Company Name filled */
            var company_name = $('#company_name').val();
            if (company_name !== '') {
                $('#company_code').attr('disabled', 'disabled');
            } else {
                $('#company_code').removeAttr('disabled');
            }

        });
    </script>

</body>

</html>