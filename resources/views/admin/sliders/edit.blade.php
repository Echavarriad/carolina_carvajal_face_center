@extends('admin.layouts.admin')
@section('content')

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
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab_1">
                                <label>Texto</label>
                                <textarea type="text" name="text" class="tinymce">{{ $record->text }}</textarea>
                                <br>

                                <label>Imagen (1386 x 670 px)</label>
                                <upload-images :img="{{ json_encode($record->image) }}" id="{{ json_encode($record->id) }}" with="1386" height="670" folder="slider" model='Slider' field='image'></upload-images>
                                <br>

                                <div class="row">
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Title de la imagen</label>
                                        <input type="text" name="tit" class="form-control" value="{{ $record->tit }}">
                                    </div>
                                    <div class="col-sm-12 col-lg-6">
                                        <label>Alt de la imagen</label>
                                        <input type="text" name="alt" class="form-control" value="{{ $record->alt }}">
                                    </div>
                                </div>
                            </div>                      
                        </div>                
                    </div>                
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-12">
            @include('admin._partials.save_cancel', array('url' => route($name . '.index')))
        </div>
        </form>
    </div>
</section>
@endsection
