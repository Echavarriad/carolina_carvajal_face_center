@extends('admin.layouts.admin')

@section('content')
<section class="content-header"></section>
<section class="content">
    <div class="row">
        <form action="{{ route($name.'.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        <div class="col-md-10 col-sm-12">
            <div class="box box-info">	       
                <div class="box-header with-border">
                    <h3 class="box-title">Nuevo {{ $singular }}</h3>
                </div>            
                <div class="box-body">
                    @include('admin._partials.errors')  
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">Contenido</a>
                            </li>                
                            <li>
                                <a href="#tab_2" data-toggle="tab">SEO</a>
                            </li>                     
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active fade in active" id="tab_1">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-9">
                                        <label>Título</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title')}}">
                                        <br>
                                    </div>
                                    <div class="col-sm-12 col-lg-3">
                                        <label>Fecha</label>
                                        <input type="text" name="date" class="datepicker form-control" value="{{ old('date')}}" autocomplete="off">
                                        <br>
                                    </div>
                                </div>
                                

                                <label>URL Amigable </label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug')}}">
                                <br>   

                                <label>Texto ampliación sobre la galería</label>
                                <textarea type="text" name="text_single_top" class="tinymce">{{ old('text_single_top')}}</textarea>
                                <br>

                                <label>Texto ampliación debajo de la galería</label>
                                <textarea type="text" name="text_single_bottom" class="tinymce">{{ old('text_single_bottom')}}</textarea>
                                <br>
                            </div>
                            <div class="tab-pane fade in" id="tab_2">
                                @include('admin._partials.meta_data', array('action' => 'add', 'metas_img' => true))
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