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
                <img src="{{ asset('assets/app/img/logo.svg') }}" class="logo" alt="Logo KotakArsip"> &nbsp;&nbsp;<b>KOTAK<span>ARSIP</span></b>
            </div>
        </nav>

        <div class="ka-body" style="padding-top: 50px">
            <div class="container setup-storage-sub">
                <div class="row">
                    <form action="{{ route('storage_sub_register_store') }}" method="post">
                        <div class="col-md-offset-2 col-md-4">
                            <br>
                            <img src="{{ asset('assets/app/img/icons/map_arsip.svg') }}" alt="" width="98%" style="margin-top: 60px">
                        </div>
                        {{ csrf_field() }}
                        <div class="col-md-4">
                            @if ($storage == "filling_cabinet")
                                <h1><input type="text" name="storage_name" placeholder="Nama Filling Cabinet"></h1>
                            @elseif ($storage == "rotary_cabinet")
                                <h1><input type="text" name="storage_name" placeholder="Nama Rotary Cabinet"></h1>
                            @elseif ($storage == "lemari_arsip")
                                <h1><input type="text" name="storage_name" placeholder="Nama Lemari Arsip"></h1>
                            @else
                                <h1><input type="text" name="storage_name" placeholder="Nama Rak Arsip"></h1>
                            @endif
                            <input type="hidden" name="type" value="{{ $storage }}">
                            <p>Silahkan masukkan nama sub penyimpanan Anda seperti map, folder, guide dan ordner pada filling cabinet ini!</p>

                            <br>

                            <div id="list">
                                <div class="form-group">
                                    <input type="text" name="name[]" class="form-control" placeholder="Nama/Kode map, folder, guide dan ordner">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-md-6">
                                    <a href="" id="add-list">+ Tambah</a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-primary">Lanjutkan &nbsp;<i class="fa fa-angle-double-right"></i></button>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </form>
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

        $('#add-list').click(function(e) {
            e.preventDefault();
            $('#list').append('<div class="form-group"><input type="text" name="name[]" class="form-control" placeholder="Nama/Kode map, folder, guide dan ordner"></div>')
        });
    </script>

</body>

</html>