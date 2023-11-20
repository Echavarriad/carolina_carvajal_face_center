<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>{{ config('app.name') }} | Administrador</title>
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.6 -->
      <link rel="stylesheet" href="{{url('/mng/css/bootstrap.min.css')}}">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="{{url('/mng/dist/css/AdminLTE.min.css')}}">
      <link rel="stylesheet" href="{{url('/mng/css/my_styles.css')}}">
      <link rel="icon" type="image/png" href="{{ asset('img/favicons/favicon.ico') }}" />
    </head>

  <body class="hold-transition login-page">

    <div class="login-box">
      <div class="login-logo">
        <a href="{{ url('/') }}" title="">
          <img src="{{url('/img/logo.png')}}" >
        </a>        
      </div>
      <!-- /.login-logo -->
      <div class="access-denied">        
          <h2>La IP no está autorizada.</h2>
      </div>
    </div>
<!-- /.login-box -->
  </body>
</html>

