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

        <div class="ka-body" style="padding-top: 50px">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-center">Penyimpanan Arsip</h1>
                        <p class="text-center">Silahkan pilih jenis penyimpanan arsip yang Anda miliki!</p>

                        <br>
                        
                        <form action="{{ route('storage_sub_register') }}" method="get">

                            <input type="radio" name="storage" class="hide" id="filling-cabinet" value="filling_cabinet" checked>
                            <input type="radio" name="storage" class="hide" id="rotary-cabinet" value="rotary_cabinet">
                            <input type="radio" name="storage" class="hide" id="lemari-arsip" value="lemari_arsip">
                            <input type="radio" name="storage" class="hide" id="rak-arsip" value="rak_arsip">

                            <div class="row select-cabinet">
                                <div class="col-md-3 text-center">
                                    <label for="filling-cabinet" class="text-center active">
                                        <img src="{{ asset('assets/app/img/icons/filling_cabinet.png') }}" width="80%">
                                        <p>Filling Cabinet</p>
                                    </label>
                                </div>

                                <div class="col-md-3 text-center">
                                    <label for="rotary-cabinet" class="text-center">
                                        <img src="{{ asset('assets/app/img/icons/rotary_cabinet.png') }}" width="80%">
                                        <p>Rotary Cabinet</p>
                                    </label>
                                </div>

                                <div class="col-md-3 text-center">
                                    <label for="lemari-arsip" class="text-center">
                                        <img src="{{ asset('assets/app/img/icons/lemari_arsip.png') }}" width="80%">
                                        <p>Lemari Arsip</p>
                                    </label>
                                </div>

                                <div class="col-md-3 text-center">
                                    <label for="rak-arsip" class="text-center">
                                        <img src="{{ asset('assets/app/img/icons/rak_arsip.png') }}" width="80%">
                                        <p>Rak Arsip</p>
                                    </label>
                                </div>

                                <div class="col-md-12 text-center">
                                    <br>
                                    <br>
                                    <button class="btn btn-primary">Lanjutkan &nbsp;<i class="fa fa-angle-double-right"></i></button>
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
        $('.select-cabinet label').click(function() {
            var id = $(this).attr('for');

            $('input[type="radio"]').removeAttr('checked');
            $('input#' + id).attr('checked', 'checked');

            $('label').removeClass('active');
            $('label[for="' + id + '"]').addClass('active');
        });
    </script>

</body>

</html>