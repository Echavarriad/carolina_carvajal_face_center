@extends('admin.layouts.admin')

@section('content')
<section class="content-header">

    
</section>

<section class="content">
   <div class="row">
    <div class="col-md-9 col-md-offset-2">
   <div class="box box-info">
         
            <div class="box-header with-border">
              <h3 class="box-title">Nueva traducción</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{ route('translation.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
             @csrf
              <div class="box-body">
             
                  <label>Clave</label>
                  <input type="text" name="key" class="form-control" value="" autocomplete="off" required>
                  <br>
                  <label>Español</label>
                  <input type="text" name="es" class="form-control" value="" autocomplete="off" required>
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

@section('js')
<script>
  (function(){
   $('.datepicker').datepicker({
      format : 'yyyy-mm-dd',
      autoclose: true,
      language:'es'
    });
  })();
</script>
@endsection