@extends('admin.layouts.admin')
@push('css')
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@section('content')
<div class="loader" style="display: none"><div class="spinner"></div></div>
@include('admin._partials.messages')
<section class="content-header">
      <h1>
        Configuración
      </h1>
</section>

<section class="content">
  
  @if(session()->has('flash.success_config'))
    <div class="row">
      <div class="col-lg-7 col-md-offset-2" style="margin-bottom: 20px;">
          <span>Guardando configuración</span> <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div>   
  @endif
  
  <div class="row">
    <div class="col-lg-10 col-md-12"> 
      @include('admin._partials.errors')          
            <!-- /.box-header -->
            <!-- form start -->
        <form action="{{ route('settings_update') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Correos (Si va a ingresar varios correos, separa cada uno con coma)</h3>
              </div>
              <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                        <label>Correo de contacto</label>
                        <input type="text" name="email_contact" class="form-control" value="{{ config('settings.email_contact') }}">
                        <br>
                    </div>
                    <div class="col-xs-12">
                      <label>Correo de newsletter</label>
                      <input type="text" name="email_newsletter" class="form-control" value="{{ config('settings.email_newsletter') }}">
                      <br>
                    </div>
                    <div class="col-xs-12">
                      <label>Correo para los pedidos</label>
                      <input type="text" name="email_order" class="form-control" value="{{ config('settings.email_order') }}">
                      <br>
                    </div>
                  </div>
              </div>
          </div>           
          <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Redes sociales</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-12 col-sm-6">
                    <label>Facebook</label>
                    <input type="text" name="facebook" class="form-control" value="{{ config('settings.facebook') }}">
                    <br>
                  </div>
                  <div class="col-sm-12 col-sm-6">
                    <label>Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{ config('settings.instagram') }}">
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Twitter</label>
                    <input type="text" name="twitter" class="form-control" value="{{ config('settings.twitter') }}">
                    <br>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <label>Youtube</label>
                    <input type="text" name="youtube" class="form-control" value="{{ config('settings.youtube') }}">
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ config('settings.whatsapp') }}">
                    <br>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <label>Texto WhatsApp</label>
                    <input type="text" name="text_whatsapp" class="form-control" value="{{ config('settings.text_whatsapp') }}">
                    <br>
                  </div>
                </div>  
              </div>
          </div>
          <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Necesitas ayuda del footer</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-xs-12 col-lg-4">
                    <label>Celular</label>
                    <input type="text" name="mobile_footer" class="form-control" value="{{ config('settings.mobile_footer') }}">
                    <br>
                  </div>
                  <div class="col-xs-12 col-lg-4">
                    <label>Correo</label>
                    <input type="text" name="email_footer" class="form-control" value="{{ config('settings.email_footer') }}">
                    <br>
                  </div>
                  <div class="col-xs-12 col-lg-4">
                    <label>Dirección</label>
                    <input type="text" name="address_footer" class="form-control" value="{{ config('settings.address_footer') }}">
                    <br>
                  </div>
                </div>
              </div>
          </div>  
          <div class="box box-info">
              <div class="box-header with-border">
                  <h3 class="box-title">Información del sitio</h3>
              </div>
            <div class="box-body">
                <label>Nombre de la tienda </label>
                <input type="text" name="shop_name" class="form-control" value="{{ config('settings.shop_name') }}">
                <br>

                <label>URL Google maps</label>
                <input type="text" name="url_google_maps" class="form-control" value="{{ config('settings.url_google_maps') }}">
                <br>

                <label>Dirección seccion contacto </label>
                <textarea name="address_section_contact" class="tinymce" rows="5">{{ config('settings.address_section_contact') }}</textarea>
                <br>
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Correos en sección contacto </label>
                    <textarea name="email_section_contact" class="tinymce" rows="5">{{ config('settings.email_section_contact') }}</textarea>
                    <br>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <label>Teléfonos en sección contacto </label>
                    <textarea name="phone_section_contact" class="tinymce" rows="5">{{ config('settings.phone_section_contact') }}</textarea>
                    <br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control"> 
                    <img src="{{ asset('uploads/'.$settings['shop_logo']) }}" width="250">   
                    <br><br>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <label>Logo utilizado en correos y pdfs (png, img)</label>
                    <input type="file" name="logo_email" class="form-control">
                    <img src="{{ asset('uploads/'.$settings['shop_logo_email']) }}" width="250">
                    <br><br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-lg-12">
                    <label>Favicon</label>
                    <input type="file" name="favicon" class="form-control">
                    <img src="{{ asset('uploads/'.$settings['shop_favicon']) }}" width="20">
                    <br><br>
                  </div>
                </div> 
                <br>

                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Políticas de tratamiento de datos</label>
                    <input type="file" name="doc_policy" class="form-control">
                    @if (!empty($settings['doc_policy']))
                      <a href="{{ asset('uploads/'.$settings['doc_policy']) }}" target="_blank">Documento actual</a>
                    @endif 
                  </div>
                </div>
                <br>         
              </div>              
          </div>  
          <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Settings</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Cantidad de productos por página</label>
                    <input type="text" name="paginate_products" class="form-control" value="{{ config('settings.paginate_products') }}"> <br>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <label>Prefijo referencias de pago</label>
                    <input type="text" name="prefix_reference" class="form-control" value="{{ config('settings.prefix_reference') }}"><br>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-lg-6">
                    <label>Sitio en modo mantenimiento</label>
                    <br>
                    <input type="checkbox" name="maintenance" class="input-switch" data-size="mini" {{ config('settings.maintenance') == true ? "checked" : ""}} value="{{ config('settings.maintenance') == true ? 1 : 0 }}" data-url="{{ route('settings.mode_maintenance') }}">
                  </div>
                  <div class="col-sm-12 col-lg-6">
                      <label>Modo desarrollo</label>
                      <br>
                      <input type="checkbox" name="debug" class="input-switch" data-size="mini" {{ config('settings.debug') == true ? "checked" : ""}} value="{{ config('settings.debug') == true ? 1 : 0 }}" data-url="{{ route('settings.debug') }}">
                    </div>
                </div>
              </div>
          </div>
          <div class="row">   
            <div class="col-sm-12 col-lg-12">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">API SIIGO</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12 col-lg-3">
                      <label>URL API</label>
                      <input type="text" name="url_api_siigo" class="form-control" value="{{ config('settings.url_api_siigo') }}"> <br>
                    </div>
                    <div class="col-sm-12 col-lg-3">
                      <label>Usuario API</label>
                      <input type="text" name="username_siigo" class="form-control" value="{{ config('settings.username_siigo') }}"><br>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                      <label>Access Key API</label>
                      <input type="text" name="access_key_siigo" class="form-control" value="{{ config('settings.access_key_siigo') }}"><br>
                    </div>
                  </div>
                </div>
              </div>    
            </div>    
          </div>    
          <div class="row">
            <div class="col-sm-12 col-lg-6">
              <div class="box box-info">
                <div class="box-header with-border">
                <h3 class="box-title">Google</h3>
                </div>
                <div class="box-body">
                  <label>Código Analytics</label>
                  <input type="text" name="code_analytics" class="form-control" value="{{ config('settings.code_analytics') }}" placeholder="UA-XXXXXXXX-X">
                  <br>
                  <label>Clave del sitio (ReCAPTCHA v3)</label>
                      <input type="text" name="recaptcha_key_site"class="form-control" value="{{ config('settings.recaptcha_key_site') }}">
                  <br>
                  <div class="row">
                      <div class="col-xs-10">
                        <label>Clave secreta (ReCAPTCHA v3)</label>
                        <input type="password" name="recaptcha_key_secret" id="password" class="form-control" value="{{ config('settings.recaptcha_key_secret') }}">
                      </div>
                      <div class="col-xs-2">
                      <button class="btn btn-primary" type="button" id="button-password">Mostrar</button>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-lg-6">
              <div class="box box-info">
                <div class="box-header with-border">
                <h3 class="box-title">SMTP</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <label>Protocolo de envío</label>
                      <select name="mail_mailer" id="" class="form-control">
                      <option value="smtp" {{ config('settings.mail_mailer') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                      <option value="sendmail" {{ config('settings.mail_mailer') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                      </select>
                      <br>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <label>Servidor</label>
                      <input type="text" name="host_smtp" class="form-control" value="{{ config('settings.host_smtp') }}">
                      <br>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <label>Puerto</label>
                      <input type="text" name="port_smtp" class="form-control" value="{{ config('settings.port_smtp') }}">
                      <br>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <label>Usuario</label>
                      <input type="text" name="user_smtp" class="form-control" value="{{ config('settings.user_smtp') }}">
                      <br>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <label>Contraseña</label>
                      <input type="password" name="password_smtp" class="form-control" value="{{ config('settings.password_smtp') }}">
                      <br>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 col-sm-12">
                      <label>Remitente</label>
                      <input type="text" name="from_address_smtp" class="form-control" value="{{ config('settings.from_address_smtp') }}">
                      <br>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                      <label>Seguridad</label>
                      <input type="text" name="encryption_smtp" class="form-control" value="{{ config('settings.encryption_smtp') }}">
                      <br>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="col-lg-2 col-md-12">
       <div class="box-footer btn-actions">
          <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Guardar</button>
      </div>
        </form>
      </div>
   </div>
</section>

@endsection
@include('admin._partials.color')

@push('js')
<script src="{{ asset('mng/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
<script>
  @if(session()->has('flash.success_config'))
    $('.loader').fadeIn();
    setTimeout(function(){
        location.reload(true);
    } , 2000);
  @endif
  $('#button-password').click(function(event){
      var tipo = document.getElementById("password");
      if(tipo.type == "password"){
          tipo.type = "text";
          $(this).text('Ocultar');
      }else{
          tipo.type = "password";
           $(this).text('Mostrar');
      }
  });
  $('#button-password-api').click(function(event){
      var tipo = document.getElementById("password-api");
      if(tipo.type == "password"){
          tipo.type = "text";
          $(this).text('Ocultar');
      }else{
          tipo.type = "password";
            $(this).text('Mostrar');
      }
  });

    $(".input-switch").bootstrapSwitch({
          onSwitchChange: function(e, status) {
          updateSettings($(this).attr('name') ,status, $(this).data('url'));
          }
    });

    function updateSettings(id , status, url){
          $.ajax({
              url : url,
              type : 'POST',
              dataType : 'json',
              data : {_token:'{{csrf_token()}}', id:id, status:status},
              success : function(res){
                  if(res.status == true){
                      toastr.options.positionClass = 'toast-bottom-right';
                      toastr.success(res.message);
                  }
              } 
          });
    }
</script>
@endpush