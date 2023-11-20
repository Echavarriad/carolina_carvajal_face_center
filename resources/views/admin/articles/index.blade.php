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
                <div class="pull-right">
                    <a href=" {{ route($name.'.create') }} " class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo</a>
                </div>
                <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la nueva.</span>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            {{-- <th class="order">Ordenar</th> --}}
                            <th class="w-10">Fecha</th>
                            <th>Título</th>                  
                            <th>Imagen (302 x 233 px)</th>
                            <th>Imagen vertical (513 x 584 px)</th>
                            <th class="w-7">Gallería</th>
                            <th class="w-10">Publicado</th>
                            <th class="w-10">Destacar</th>
                            <th class="w-15 actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $item)
                            <tr id="{{ $item->id }}">
                                {{-- <td class="drag-handle"><a href="#"><i class="fas fa-arrows-alt fa-2x"></i></a></td> --}}
                                <td>{{ $item->formatDate() }}</td>  
                                <td>{{ $item->title }}</td>  
                                <td>
                                    <upload-images :img="{{ json_encode($item->image_intro) }}" id="{{ json_encode($item->id) }}" with="302" height="233" folder="article" model='Article' field='image_intro' :del="false" :delrecord="false"></upload-images>
                                </td> 
                                <td>
                                    <upload-images :img="{{ json_encode($item->image_home) }}" id="{{ json_encode($item->id) }}" with="513" height="584" folder="article" model='Article' field='image_home' :del="true"></upload-images>
                                </td> 
                                <td>
                                    <a href="{{ route($name. '.images', $item->id)}}">
                                        <i class="fa fa-images fa-2x"></i> ({{ $item->gallery->count() }})
                                    </a>
                                </td> 
                                <td class="btn-actions-index">
                                    <c-switch status="{{ $item->published }}" url="{{ route($name . '.published') }}" id="{{ $item->id }}"></c-switch>
                                </td>  
                                <td class="btn-actions-index">
                                    <c-switch status="{{ $item->featured }}" url="{{ route($name . '.featured') }}" id="{{ $item->id }}"></c-switch>
                                </td> 
                                <td class="btn-actions-index">
                                    <a href="{{ route($name.'.edit' , [$item->id]) }}" class="btn btn-primary btn-flat" title="Editar"><i class="fa fa-edit"></i></a>
                                    <form action="{{route($name.'.destroy' , [$item->id])}}" method="POST" style="display:inline;">@csrf 
                                    @method('DELETE')
                                        <button  type="submit" class="btn btn-danger btn-flat btn-delete" title="Eliminar" data-name ='{{ $item->title }}' data-table = 'este registro'><i class="fa fa-trash"></i></button> 
                                    </form>
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