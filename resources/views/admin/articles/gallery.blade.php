@extends('admin.layouts.admin')
@section('content')
@include('admin._partials.messages')
<section class="content-header">
    <h1>Galería de {{ $plural }}</h1> 
    <br>
    <a href=" {{ route($name. '.index') }} " class="btn btn-primary"><i class="fa fa-arrow-left"></i> Volver a Blogs</a>    
</section>

<section class="content" id="app">      
    <div class="row">
        <div class="col-xs-12">          
            <div class="box">
            <div class="box-header">
            </div>
            <div class="box-body">
                <div class="pull-right">
                    <a href="{{ route($name.'.add_image', $record->id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar imagen</a>
                </div>
                <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la nueva.</span>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="order">Ordenar</th>                
                            <th>Imagen (765 x 859 px)</th>
                            <th class="w-15 actions">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" data-url="{{ route($name . '.order_gallery') }}">
                        @foreach($record->gallery as $item)
                            <tr id="{{ $item->id }}">
                                <td class="drag-handle"><a href="#"><i class="fas fa-arrows-alt fa-2x"></i></a></td> 
                                <td>
                                    <upload-images :img="{{ json_encode($item->image) }}" id="{{ json_encode($item->id) }}" with="756" height="859" folder="articlegallery" model='ArticleGallery' field='image' del="false"></upload-images>
                                </td>   
                                <td class="btn-actions-index">
                                    <form action="{{route($name.'.delete_image' , [$record->id, $item->id])}}" method="POST" style="display:inline;">@csrf 
                                    @method('DELETE')
                                    <button  type="submit" class="btn btn-danger btn-flat btn-delete" title="Eliminar" data-name ='' data-table = 'esta imagen'><i class="fa fa-trash"></i></button> </form>
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