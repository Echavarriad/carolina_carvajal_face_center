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
                            <a href="#tab_1" data-toggle="tab">{{ $record->name }}</a>
                            </li>  
                        </ul>
                        <div class="tab-content">
                            <label for="">Descripción</label>
                            <input type="text" class="form-control" name="description" value="{{ $record->description }}">
                            <br>
                            <div class="tab-pane fade in active" id="tab_1">
                                
                                @include('admin.modules._partials.' . $record->code) 
                            
                                <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la nueva.</span><br>
                                <label>Logo</label>
                                <upload-images :img="{{ json_encode($record->logo) }}" id="{{ json_encode($record->id) }}" with="0" height="0" folder="module" model='Module' field='logo' del="true"></upload-images>
                                <br>
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
