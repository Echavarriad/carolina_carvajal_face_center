@extends('admin.layouts.admin')
@section('content')
@include('admin._partials.messages')
<section class="content-header"></section>

<section class="content" id="app">
    <div class="row">
        <form action="{{ route($name.'.update',[$record->id]) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="col-md-10 col-sm-12"> 
            <div class="box box-info">	       
                <div class="box-header with-border">
                    <h3 class="box-title">Editar {{ $singular }}</h3>
                </div>
                <div class="box-body">
                    @include('admin._partials.errors')  
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">Contenido</a>
                            </li>     
                            <li>
                                <a href="#tab_2" data-toggle="tab">Categorías</a>
                            </li> 
                            <li>
                                <a href="#tab_3" data-toggle="tab">Precios y variaciones</a>
                            </li> 
                            <li>
                                <a href="#tab_5" data-toggle="tab">Relacionados</a>
                            </li>  
                            @if($record->type_product === 'simple')
                                <li>
                                    <a href="#tab_6" data-toggle="tab">Galería</a>
                                </li> 
                            @endif    
                            <li>
                                <a href="#tab_4" data-toggle="tab">SEO</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab_1">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $record->name }}">
                                <br>

                                <label>URL Amigable </label>
                                <input type="text" name="slug" class="form-control" value="{{ $record->slug }}">
                                <br> 
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Referencia</label>
                                        <input type="text" name="sku" class="form-control" value="{{ $record->sku }}">
                                        <br> 
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Peso en gramos</label>
                                        <input type="number" name="weight" class="form-control" value="{{ $record->weight }}">
                                        <br>
                                    </div>
                                </div>
                                 
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Posición (Cambie la posicion manualmente) </label>
                                        <input type="number" name="order" class="form-control" value="{{ $record->order }}">
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Información del producto</label>
                                        <textarea type="text" name="detail" class="tinymce">{{ $record->detail }}</textarea>
                                        <br>
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Información adicional</label>
                                        <textarea type="text" name="detail_additional" class="tinymce">{{ $record->detail_additional }}</textarea>
                                        <br>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="tab-pane fade in list-checks" id="tab_2">
                                <h3>Categorías</h3><br>
                                <div class="container-fluid">
                                    <div class="category-tree">
                                        <ul>
                                        @foreach($categories as $category)
                                            <li>
                                                <label>
                                                    <input type="checkbox" name="_categories[]" class="check_blue" value="{{$category['id']}}" {{ in_array($category['id'] , $product_cat) ? 'checked':''}}>
                                                    {{$category['name']}} 
                                                </label>
                                                @if(count($category['children']))
                                                    @include('admin._partials.categories' , ['childs' => $category['children'] , 'array_cats'=> $product_cat])
                                                @endif
                                            </li>
                                        @endforeach
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade in" id="tab_3">
                                <price-variations :data_product="{{ json_encode($record) }}">
                                </price-variations>
                            </div>  

                            <div class="tab-pane fade in list-checks" id="tab_5">
                                <product-relateds :product="{{ $record }}" :count_relateds ="{{ $count_related }}"></product-relateds> 
                            </div>
                            <div class="tab-pane fade in list-checks" id="tab_6">
                                <product-gallery :product="{{ $record }}"></product-gallery> 
                            </div>
                            <div class="tab-pane fade in" id="tab_4">
                                @include('admin._partials.meta_data', array('action' => 'edit', 'metas_img' => true))
                            </div>                        
                        </div>                
                    </div>                
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            <div class="box-footer btn-actions">
                <a href="{{ route($name . '.index') }}" class="btn btn-default"><i class="fa fa-times"></i> Cancelar</a>
                <button type="submit" class="btn btn-success pull-right" name="_save" value="continue" style="width: fit-content;    text-wrap: wrap;"><i class="fa fa-save"></i> Guardar y continuar</button>
                <button type="submit" class="btn btn-success pull-right" name="_save" value="save" style="width: fit-content;    text-wrap: wrap;"><i class="fa fa-save"></i> Guardar y salir</button>
            </div>
        </div>        
        <input type="hidden" value="{{ $record->id }}" name="id" />
        </form>
    </div>
</section>
@endsection
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('mng/jqtree/jqtree.css') }}">
@endpush
