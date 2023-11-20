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
      
      <link rel="icon" type="image/png" href="{{asset('uploads/'.config('settings.shop_favicon')) }}" />
      <meta name="apple-mobile-web-app-title" content="{{ config('settings.shop_name') }}">
      <meta name="application-name" content="{{ config('settings.shop_name') }}">
      <meta name="msapplication-TileColor" content="{{ config('settings.color_primary') }}">
      <meta name="theme-color" content="{{ config('settings.color_primary') }}">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <meta name="base-url" content="{{ url('/') }}" />
    </head>

  <body class="hold-transition login-page">

    <div class="login-box">
      <div class="login-logo">
        <a href="{{ url('/') }}" title="{{ config('app.name') }}">
           <img src="{{ asset('uploads/'.config('settings.shop_logo')) }}" style="max-width: 250px;">
        </a>        
      </div>
      <!-- /.login-logo -->
      <div class="login-box-body">        
          @if(session()->has('flash.success'))
            <p class="login-box-msg success" style="color:#18ed29;">{{ session('flash.success') }}</p>
          @endif
         	@if(session()->has('flash.error'))
         	<p class="login-box-msg" style="color:#901414;">{{ session('flash.error') }}</p>
	        @endif         
            <form class="form-horizontal" role="form" method="POST" action="{{ route('forgot.admin.post') }}">
              {{ csrf_field() }}
              <input type="hidden" name="token" value="{{$token}}">
              <p class="title-admin">Ingrese los nuevos accesos.</p>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Nueva contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <p class="login-box-msg">{{ $errors->first('password') }}</p>
                @endif
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" name="_password_confirm" placeholder="Repetir contraseña">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('_password_confirm'))
                    <p class="login-box-msg">{{ $errors->first('password') }}</p>
                @endif
              </div>
              <div class="row">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Restablecer contraseña</button>
              </div>
            </form>
            <p class="login-box-msg link-back">Volver a <a href="{{ url('/') }}">{{config('app.name')}} </a></p>
          <br>
         
      </div>
    </div>
<!-- /.login-box -->
  </body>
</html>

