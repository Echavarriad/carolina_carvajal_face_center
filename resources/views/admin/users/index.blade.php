@extends('admin.layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
@include('admin._partials.messages')
    <section class="content-header">
    
      <h1>
        Usuarios
      </h1>

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">

            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div class="pull-right">
                <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo</a>
              </div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Celular</th>
                    <th>Última sesión</th>
                    <!--<th>Activo</th>-->
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->document }}</td>
                  <td>{{ $user->mobile }}</td>
                  <td>{{ $user->last_login }}</td>
                 
                  <td><a href="{{ route('users.edit' , [$user->id]) }}" class="btn btn-primary btn-flat" title="Editar"><i class="fa fa-edit"></i></a>
                   @if($user->id != auth('admin')->user()->id)
                    <form action="{{route('users.destroy' , [$user->id])}}" method="POST" style="display:inline;">@csrf 
                    @method('DELETE')
                    <buttton  type="submit" class="btn btn-danger btn-flat btn-delete" title="Eliminar" data-name ='{{ $user->name }}' data-table = 'este usuario'><i class="fa fa-trash"></i></buttton> </form>
                   
                   @endif
                  </td>
                </tr>
                @endforeach
                
              </tbody>
               
              
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@endsection
@include('admin._partials.push_switch') 