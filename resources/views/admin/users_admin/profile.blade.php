@extends('admin.layouts.admin')

@section('content')
@include('admin._partials.messages')
<section class="content-header">
      <h1>
        Perfil de usuario
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
            <form action="{{ route('admins.profile' , [$admin->id]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
             {{ csrf_field() }}
             <input type="hidden" name="id" value="{{ $admin->id }}">
              <div class="box-body">
                 <label>Nombre</label>
                 <input type="text" name="name" class="form-control" value="{{ $admin->name }}">
                <br>

                <label>Email</label>
                 <input type="text" name="email" class="form-control" value="{{ $admin->email }}">
                <br>
                  <label>Teléfono</label>
                   <input type="text" name="phone" class="form-control" value="{{ $admin->phone }}">
                  <br>
                  <label>Teléfono</label>
                   <input type="text" name="phone" class="form-control" value="{{ $admin->phone }}">
                  <br>

                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                 <a href="{{ route('admins.change_password') }}" class="btn btn-default">Cambiar contraseña</a>
                <button type="submit" class="btn btn-success pull-right">Guardar</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
      </div>
   </div>
</section>

@endsection

