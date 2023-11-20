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
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active fade in active" id="tab_1">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Departamento</label>
                                        <select name="code_state" id="state" class="form-control">
                                            <option value="" disabled selected>---Seleccionar---</option>
                                            @foreach ($states as $item)
                                                <option value="{{ $item->id_state }}" data-cities="{{ json_encode($item->cities) }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Ciudad</label>
                                        <select name="code_city" id="cities" class="form-control">
                                            
                                        </select>
                                        <br>
                                    </div>
                                </div>
                                <label>Valor mínimo de la compra <span class="cursor info"><img src="{{ asset('mng/img/info-blue.svg')}}"  alt=""><p>Este es valor donde inicial la compra, por ejemplo si aplica para compras iguales o mayores a 2.000.000, 2.000.000  sería el valor que se coloca en este registro. Si aplica para compras menores de 2.000.000 el valor valor mínimo de compra es 0.</p></span></label>
                                <input type="text" name="value_min_buy" class="form-control" value="{{ old('value_min_buy')}}">
                                <br>

                                <label>Valor del envío</label>
                                <input type="text" name="shipping_fee" class="form-control" value="{{ old('shipping_fee')}}">

                                <input type="hidden" name="name_city" id="name_city">
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
@push('js')
<script>
    $('#state').change(function(event){
        let cities= $('#state option:selected').data('cities');
        let options= '';
        $('#cities').empty();
        cities.forEach((city, index) => {
            options += `<option value="${ city.ciudad }">${ city.name }</option>`;
            if(index == 0){
                $('#name_city').val(city.name);
            }
        });
        $('#cities').append(options);
    });

    $('#cities').change(function(event){        
        $('#name_city').val($('#cities option:selected').text());
    });
    
</script>
@endpush