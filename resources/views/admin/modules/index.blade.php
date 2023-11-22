@extends('admin.layouts.admin')
@section('content')
@include('admin._partials.messages')
<section class="content-header">
    <h1>{{ $plural }}</h1>      
</section>

<section class="content" id="app">      
    <div class="row">
        <div class="col-xs-12">          
            <div class="box">
            <div class="box-header"></div>
            <div class="box-body">
                <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la nueva.</span>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="w-15">Logo</th>                  
                            <th>Nombre</th>
                            <th class="w-10">Tipo</th>
                            <th class="w-15 actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $item)
                            <tr> 
                                <td>
                                    <upload-images :img="{{ json_encode($item->logo) }}" id="{{ json_encode($item->id) }}" with="0" height="0" folder="module" model='Module' field='logo' del="true"></upload-images>
                                </td> 
                                <td>{{ $item->name }}</td>    
                                <td>{{ $item->type }}</td>    
                                <td class="btn-actions-index">
                                    <a href="{{ route($name.'.edit' , [$item->id]) }}" class="btn btn-primary btn-flat" title="Editar"><i class="fa fa-edit"></i></a>
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