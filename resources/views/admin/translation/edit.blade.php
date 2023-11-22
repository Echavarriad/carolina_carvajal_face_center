@extends('admin.layouts.admin')

@section('content')
<section class="content-header">
</section>

<section class="content">
   <div class="row">
    <div class="col-md-9 col-md-offset-2">
	 <div class="box box-info">
	
              <div class="box-header with-border">
              <h3 class="box-title">Editar traducción</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ route('translation.update',[$id]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
             {{ csrf_field() }}
             {{ method_field('PUT') }}
              <div class="box-body">
                  <label>Valor</label>
                  <input type="text" name="es[{{$id}}]" class="form-control" autocomplete="off" value="{{ $langs['es'][$id]}} " required>                 
                  <br>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{{ route('translation.index') }}" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-success pull-right">Guardar</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
      </div>
   </div>
</section>

@endsection
