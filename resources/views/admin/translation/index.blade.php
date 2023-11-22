@extends('admin.layouts.admin')
@section('content')
@include('admin._partials.messages')
<section class="content-header">
    <h1>Traducciones</h1>      
</section>

<section class="content" id="app">      
    <div class="row">
        <div class="col-xs-12">          
            <div class="box">
            <div class="box-header"></div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                          <th class="w-5">Clave</th>
                          <th>Nombre</th>
                          <th class="w-15">Elementos</th>
                          <th class="actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($langs['es'] as $key => $value)
                      <tr>
                        <td>{{$key}}</td>
                        <td>{{$value}}</td>
                        <td>
                          <a href="{{ route('translationelement.index' , ['s' => $key]) }}" class="btn btn-primary btn-flat" title="Editar elementos"><i class="fa fa-list"></i></a>
                        </td>
                        <td>
                          <a href="{{ route('translation.edit' , [$key]) }}" class="btn btn-primary btn-flat" title="Editar"><i class="fa fa-edit"></i></a>
                        </td>
                      </tr>
                      @endforeach             
                    </tbody> 
                </table>
            </div>
            </div>        
        </div>
    </div>
</section>
@endsection  