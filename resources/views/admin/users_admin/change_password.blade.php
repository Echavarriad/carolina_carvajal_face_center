@extends('admin.layouts.admin')

@section('content')

@include('admin._partials.messages')
<section class="content-header">
      <h1>
        Cambiar contraseña
      </h1>
  
</section>

<section class="content">
   <div class="row">
    <div class="col-md-7 col-md-offset-2">
	 <div class="box box-info">
	       @include('admin._partials.errors')
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
           <form action="{{ route('admins.change_password') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
             {{ csrf_field() }}
              <div class="box-body">
                <label>Contraseña actual</label>
                 <input type="password" name="_current_password" class="form-control">
                <br>

                <label>Nueva contraseña</label>
                 <input type="password" name="password" class="form-control">
                <br>

                <label>Confirmar contraseña</label>
                 <input type="password" name="password_confirmation" class="form-control">
                <br>

                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              	 <a href="{{ route('admins.profile') }}" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-success pull-right">Guardar</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
      </div>
   </div>
</section>

@endsection

