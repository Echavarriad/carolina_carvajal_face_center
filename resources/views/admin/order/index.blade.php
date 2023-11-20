@extends('admin.layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
@include('admin._partials.messages')
    <section class="content-header">
      <h1>{{ $plural }}</h1>  
      <br>   
    </section>

    <!-- Main content -->
    <section class="content">      
      <div class="row">
        <div class="col-xs-12">          
          <div class="box">
            <div class="box-header">
              <form action="{{ url()->current() }}" method="get">
                <div class="row">
                  <div class="col-sm-12 col-md-6 col-lg-3">
                    <input type="text" name="date_initial" id
                    ="date_initial" class="form-control" placeholder="Fecha inicial" value="{{ isset($field_form['date_initial']) ? $field_form['date_initial'] : '' }}" autocomplete="off"> 
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-3">
                    <input type="text" name="date_final" id
                    ="date_final" class="form-control"  placeholder="Fecha final" value="{{ isset($field_form['date_final']) ? $field_form['date_final'] : '' }}" autocomplete="off"> 
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-3">
                    <input type="text" name="reference" class="form-control" placeholder="Referencia" value="{{ isset($field_form['reference']) ? $field_form['reference'] : '' }}"> 
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-3">
                    @php
                    $st = '';
                      if(isset($field_form['status'])){
                        $st = $field_form['status'];
                      }
                    @endphp
                        <select name="status" class="form-control">
                          <option value="">---Estado---</option>
                          @foreach ($statuses as $item)
                            <option value="{{ $item->id }}" {{ $st == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                          @endforeach
                        </select>
                      <br>
                  </div>
                </div> <br>
                <div class="row">  
                  
                  <div class="col-lg-3">
                    <button  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                    <a href="{{ route($name . '.index') }}" class="btn btn-default"><i class="fa fa-times"></i> Limpiar</a> 
                  </div>
                </div> 
            </form>           
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @include('admin.order.list_orders')

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
         {{ $orders->appends(request()->input())->links('admin._partials.pagination') }}
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@endsection

@include('.admin._partials.datepicker')
