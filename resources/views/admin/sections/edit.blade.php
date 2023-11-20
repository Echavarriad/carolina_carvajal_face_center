@extends('admin.layouts.admin')

@section('content')
<section class="content-header">
</section>



<section class="content">

   <div class="row">

    <div class="col-md-9 col-sm-12">

	 <div class="box box-info">

	       @include('admin._partials.errors')

            <div class="box-header with-border">

              <h3 class="box-title">SEO</h3>
            </div>

            <!-- /.box-header -->
            <!-- form start -->

            <form action="{{ route($name .'.update',[$section->id]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">

             @csrf

             @method('PUT')

              <div class="box-body">

             

             <div class="nav-tabs-custom">

             <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#tab_1" data-toggle="tab">SEO</a>
                  </li>
            </ul>

            

            <div class="tab-content">

              <div class="tab-pane fade in active" id="tab_1">               

                  <label>Meta título</label>
                  <input type="text" name="meta_title" class="form-control" value="{{ $section->meta_title }}">
                  <br>

                  <label>Meta descripción</label>
                  <textarea name="meta_description" class="form-control" rows="5">{{ $section->meta_description}}</textarea>
                  <br>

                  <label>Meta keywords</label>
                  <textarea name="meta_keywords" class="form-control" rows="5">{{ $section->meta_keywords}}</textarea>
                  <br>              
                </div>

            </div>

            <!-- /.tab-content -->

          </div> 
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

