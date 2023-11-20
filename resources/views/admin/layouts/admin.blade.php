<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrador {{-- {{ config('settings.shop_name') }} --}} </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ url('mng/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/14dab0af84.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ url('mng/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ url('mng/dist/css/skins/_all-skins.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('mng/css/sortable.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="{{ url('mng/plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url('mng/css/my_styles.css?v=' . config('settings.version_cache')) }}">

    @stack('css')
    <style type="text/css">
        [v-cloak] {
            display: none;
        }
    </style>

    <link rel="icon" type="image/png" href="{{ asset('uploads/' . config('settings.shop_favicon')) }}" />
    {{-- <meta name="apple-mobile-web-app-title" content="{{ config('settings.shop_name') }}">
  <meta name="application-name" content="{{ config('settings.shop_name') }}">
  <meta name="msapplication-TileColor" content="{{ config('settings.color_primary') }}">
  <meta name="theme-color" content="{{ config('settings.color_primary') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="base-url" content="{{ url('/') }}" /> --}}
</head>

<body class="hold-transition skin-blue sidebar-mini admin">
    <div class="loader">
        <div class="spinner"></div>
    </div>
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="logo" target="_blank">
                <span>{{ config('settings.shop_name') }}</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ url('/mng/img/icon_user.png') }}" class="user-image">
                                {{-- <span class="hidden-xs">{{ auth('admin')->user()->name }}</span> --}}
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ url('/mng/img/icon_user.png') }}" class="img-circle">

                                    <p>
                                        {{-- {{ auth('admin')->user()->name }} --}}
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{-- {{ route('admins.profile') }} --}}" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{-- {{ route('admin.logout') }} --}}" class="btn btn-default btn-flat">Salir</a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image" style="min-height: 25px;">
                    </div>
                    <div class="pull-left info">
                        <a href="{{ url('/') }}" style="font-size:16px;margin-left:-12%;" target="_blank">Ir al
                            sitio</a>
                    </div>
                </div>
                @include('admin.layouts._partials.menu_admin')
            </section>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version Laravel</b> {{ config('app.version_laravel') }} | <b>Version PHP</b>
                {{ config('app.version_php') }}
            </div>
            <strong>{{ config('settings.shop_name') }}</strong>
        </footer>
        <div class="control-sidebar-bg"></div>
    </div>
    <script src="{{ url('mng/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    @if (strpos(request()->route()->getName(),
            'gallery') === false)
        <script src="{{ url('mng/js/backend.js?v=' . config('settings.version_cache')) }}"></script>
    @endif
    <script src="{{ url('mng/js/jquery.ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="{{ url('mng/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('mng/dist/js/app.min.js') }}"></script>
    <script src="{{ url('mng/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('mng/js/my_scripts.js') }}"></script>
    <script src="{{ url('mng/tinymce/tinymce.min.js') }}"></script>
    <script>
        var baseRoot = "{{ url('/') }}";
        var token = "{{ csrf_token() }}";
        $(document).on('click', '.btn-delete', function(event) {
            event.preventDefault();
            var table = $(this).data('table');
            var name = $(this).data('name');
            var note = $(this).data('note');
            if (note == undefined) {
                note = '';
            }
            var obj = $(this);
            Swal.fire({
                title: "¿Está seguro de eliminar " + table + "?",
                html: '<span style="font-size:21px;font-weight:bold; color : #3c8dbc">' + name +
                    ' </span><br><p style="color:red">' + note + '</p>',
                showCancelButton: true,
                confirmButtonColor: '{{ config('settings.color_primary') }}',
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
            }).then(isConfirm => {
                if (isConfirm.value) {
                    obj.parent().submit();
                }
            });
        });
        $.datepicker.regional['es'] = {
            prevText: '< Ant',
            nextText: 'Sig >',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            showTimepicker: false
        });
        var editor_config = {
            path_absolute: "{{ url('admin/') }}",
            selector: ".tinymce",
            language: "es",
            branding: false,
            menubar: 'file edit insert view format table',
            theme: 'modern',
            force_p_newlines: true,
            forced_root_block: "",
            valid_elements: '*[*]',
            toolbar1: 'undo redo | insert | styleselect | bold italic underline | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'hr print preview media | forecolor backcolor emoticons | codesample code',
            image_advtab: true,
            height: 200,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools toc help'
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback: function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;
                var cmsURL = editor_config.path_absolute + '/lfmanager?field_name=' + field_name + "&type=manager";
                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Administrador de archivos',
                    width: x * 0.6,
                    height: y * 0.7,
                    resizable: "yes",
                    close_previous: "no"
                });
            }
        };
        tinymce.init(editor_config);
    </script>
    @stack('js')
</body>

</html>
