@extends('admin.layouts.admin')



@section('content')

<section class="content-header">



    

</section>



<section class="content">

   <div class="row">

    <div class="col-md-9 col-sm-12">

	 <div class="box box-info">

	       

            <div class="box-header with-border">

              <h3 class="box-title">Nueva seccion</h3>

            </div>



            <!-- /.box-header -->

            <!-- form start -->

            <form action="{{ route($name .'.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">

             @csrf

              <div class="box-body">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name')}}">
                <br>

                <label>SEO</label>
                <select name="seo" class="form-control">
                  <option value="1">Si</option>
                  <option value="0">No</option>
                </select>
                <br>

                <br>

              </div>

          </div>

      </div>

      <div class="col-md-3 col-sm-12">        
          <!-- /.box-body -->
          @include('admin._partials.save_cancel', array('url' => route($name . '.index')))
          <!-- /.box-footer -->

          </form>
      </div>

   </div>

</section>



@endsection