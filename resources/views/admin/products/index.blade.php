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
                <div style="position: relative; display: flex; align-items: center; justify-content: space-between;">
                    <div style="margin-bottom: 26px;">
                        <form action="{{ url()->current() }}" style="display: flex;width: 500px;">
                            <input type="text" name="name" class="form-control" placeholder="Buscar por el nombre del producto o referencia" value="{{ $field_form['name'] ?? '' }}">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href=" {{ route($name.'.index') }} " class="btn btn-dark">Limpiar</a>
                        </form>
                    </div>
                <div class="pull-right">
                    <a href=" {{ route($name.'.create') }} " class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo</a>
                </div>
                </div>
                <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la nueva.</span>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="order">Ordenar</th>   
                            <th class="w-10">Imagen (517 x 709 px)</th>
                            <th class="w-10">REF.</th>               
                            <th>Nombre</th>               
                            <th class="w-7">Precio</th>
                            <th class="w-10">Destacado</th>
                            <th class="w-10">Publicado</th>
                            <th class="w-10 actions">Acciones</th>
                            <th class="w-12 actions">SIIGO</th>
                        </tr>
                    </thead>
                    <tbody id="sortable" data-url="{{ route($name . '.order') }}">
                        @foreach($records as $item)
                            <tr id="{{ $item->id }}">
                                <td class="drag-handle"><a href="#"><i class="fas fa-arrows-alt fa-2x"></i></a></td>  
                                <td>
                                    <upload-images :img="{{ json_encode($item->image) }}" id="{{ json_encode($item->id) }}" with="517" height="709" folder="product" model='Product' field='image' :del="true" :delrecord="false"></upload-images>
                                </td> 
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    {{ $item->type_product == 'simple' 
                                                        ?  $item->price()
                                                        : 'Desde ' . core()->currency($item->price); }}</td>  
                                <td class="btn-actions-index">
                                    <c-switch status="{{ $item->featured }}" url="{{ route($name . '.featured') }}" id="{{ $item->id }}"></c-switch>
                                </td>
                                <td class="btn-actions-index">
                                    <c-switch status="{{ $item->published }}" url="{{ route($name . '.published') }}" id="{{ $item->id }}"></c-switch>
                                </td>    
                                <td class="btn-actions-index">
                                    <a href="{{ route($name.'.edit' , [$item->id]) }}" class="btn btn-primary btn-flat" title="Editar"><i class="fa fa-edit"></i></a>
                                    <form action="{{route($name.'.destroy' , [$item->id])}}" method="POST" style="display:inline;">@csrf 
                                    @method('DELETE')
                                        <button  type="submit" class="btn btn-danger btn-flat btn-delete" title="Eliminar" data-name ='{{ $item->name }}' data-table = 'este registro, recuerde eliminar el producto en SIIGO si ya existe'><i class="fa fa-trash"></i></button> 
                                    </form>                                   
                                </td>
                                <td class="btn-actions-index">
                                    <manager-product-siigo inline-template :data_product="{{ json_encode($item) }}" v-cloak>
                                        <div>
                                            <a v-if="product.id_siigo == null || product.id_siigo == ''" class="btn btn-info btn-flat" title="Crear producto en Siigo" v-on:click.prevent="createProductInSiigo" ><i class="fa fa-send"></i></a>
                                            <a v-else class="btn btn-info btn-flat" title="Atualizar producto en Siigo" v-on:click.prevent="updateProductInSiigo" ><i class="fa fa-sync"></i></a>
                                            <a v-if="product.id_siigo != null && product.can_delete_in_siigo == '1'" class="btn btn-danger btn-flat" title="Eliminar producto en Siigo" v-on:click.prevent="deleteProductInSiigo" ><i class="fa fa-trash"></i></a>
                                            <a v-if="product.id_siigo != null" class="btn btn-dark btn-flat" title="Actualiza el stock del producto en SIIGO" v-on:click.prevent="updateStockInSiigo" ><i class="fa fa-cubes"></i></a>
                                        </div>
                                    </manager-product-siigo>                                    
                                </td>
                            </tr>
                        @endforeach                
                    </tbody> 
                </table>
            </div>
            </div> 
            {{ $records->appends(request()->input())->links('admin._partials.pagination') }}       
        </div>
    </div>
</section>
@endsection 