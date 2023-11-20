@extends('admin.layouts.admin')

@section('content')

<section class="content">
  <section class="content-header" style="margin-bottom: 10px;">
      <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Volver</a>     
      {{-- <a href=" {{ route($name.'.download', $order->id) }} " class="btn btn-primary pull-right"><i class="fas fa-file-pdf"></i> Descargar en PDF</a> --}}
      
    </section>
   <div class="row">
   <div class="col-lg-7 col-md-12"> 
      <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title">Detalle de la compra</h3>
          </div>
          <div class="box-body">                               
              <div class="row">
                <div class="col-sm-12">
                  <h4 class="details-order"><strong>Referencia:</strong> <span style="color: {{ config('settings.color_primary') }};font-weight: 800;">{{ $order->reference }}</span></h4> 
                  <h4 class="details-order"><strong>Nombre:</strong> <span>{{ $order->customer_name . ' ' . $order->customer_lastname }}</span></h4>                  
                  <h4 class="details-order"><strong>{{ $order->customer_type_document }}:</strong> <span>{{ $order->customer_document }}</span></h4>
                  <h4 class="details-order"><strong>Email:</strong> <span>{{ $order->customer_email }}</span></h4>
                  <h4 class="details-order"><strong>Teléfono:</strong> <span>{{ $order->customer_mobile }}</span></h4>
                  <h4 class="details-order"><strong>Estado:</strong> 
                      <select name="" id="select-status">
                        @foreach ($statuses as $item)
                          <option style="color:{{ $item->color }}" value="{{ $item->id }}"  {{ $order->status_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </h4>
                    @if ($order->status_id == 2 || $order->status_id == 3 || $order->status_id == 6)
                      <h4 class="details-order"><strong>Número de la guía: 
                        @if ($order->status_id == 2)
                          <i class="cursor fas fa-edit guide"></i>
                        @endif 
                        </strong> 
                          <span>{{ $order->guide_number }}</span>
                          <input type="text" id="input-guide" style="display: none;">
                          <i class="cursor fas fa-save guide" style="display: none;"></i>
                        </h4>
                        <h4 class="details-order"><strong>URL de rastreo:
                        @if ($order->status_id == 2)
                          <i class="cursor fas fa-edit url-guide"></i>
                        @endif                           
                        </strong> <span>{{ $order->url_guide }}</span>
                          <input type="text" id="input-url-guide" style="display: none;">
                          <i class="cursor fas fa-save url-guide" style="display: none;"></i>
                      </h4>                     
                    @endif
                </div>
              </div>
          </div>
      </div>
      <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title">Dirección de envío</h3>
          </div>
          <div class="box-body address">                               
              <div class="row">
                <div class="col-sm-12">
                  <h4 class="details-order"><strong>Dirección:</strong> <span>{{ $order->address->address }}</span></h4>
                  @if(!empty($order->address->complement))
                    <h4 class="details-order"><strong>Complemento:</strong> <span>{{ $order->address->complement }}</span></h4>
                  @endif
                  <h4 class="details-order"><strong>Ciudad</strong> <span>{{ $order->address->city . ' - ' . $order->address->state }}</span></h4>
                  <h4 class="details-order"><strong>{{ $order->shipping_method }}</strong> <span><span></h4>
                </div>
              </div>
          </div>

      </div>
      
      
    </div>
    <div class="col-lg-5 col-md-12"> 
      <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title">Valores</h3>
          </div>
          <div class="box-body">                               
              <div class="row">
                <div class="col-sm-12"> 
                  <h4 class="values-order"><strong>Subtotal:</strong> <span>{{ $order->subtotal_format }}</span></h4> 
                  @if($order->discount_amount > 0)
                    <h4 class="values-order"><strong>Descuento:</strong> <span>-{{ $order->discount_format }}</span></h4>
                  @endif 
                  @if($order->apply_iva)
                    <h4 class="values-order"><strong>IVA:</strong> <span>{{ $order->iva_format }}</span></h4>
                  @endif 
                    <h4 class="values-order"><strong>Envío:</strong><span>{{ $order->shipping_free ? 'Gratis' : $order->shipping_format }}</span></h4>               
                  
                  <h4 class="values-order last"><strong>TOTAL:</strong><span>{{ $order->total_format }}</span></h4>
                </div>
              </div>
          </div>
      </div>
      @if(!empty($payment))
        <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title">Pago</h3>
          </div>
          <div class="box-body payment">                               
              <div class="row">
                <div class="col-sm-12">
                  @if (empty($payment))
                    <h4 class="details-order"><strong>No se ha realizado el pago</h4>
                  @else
                    @foreach ($payment->params as $key => $value)
                      <h4 class="details-order"><strong>{{ $key }}:</strong> <span>{{ $key == 'amount' ? core()->currency($value, 2) : $value }}</span></h4>
                    @endforeach
                  @endif
                </div>
              </div>
        </div>
      @endif
      </div>
    </div>
    <div class="col-sm-12"> 
      <div class="box box-info">
          <div class="box-header with-border">
          <h3 class="box-title">Productos</h3>
          </div>
          <div class="box-body">                               
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="w-10"></th>
                  <th class="w-10">Referencia</th>
                  <th>Producto</th>
                  <th class="w-7">Cantidad</th>
                  <th class="w-10">Precio</th>
                  <th class="w-10">Total</th>
                </tr>
                </thead>

                <tbody>
               @foreach($order->items as $item)
                <tr>
                  <td><img src="{{ asset('uploads/' . $item->image) }}" width="70"></td>
                  <td>{{ $item->sku }}</td>
                  <td>{{ $item->name  }}</td>
                  <td>{{ $item->quantity }}</td>
                  <td>{{ $item->price_format }}</td>
                  <td>{{ $item->total_format }}</td>
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

@push('js')

  <script>
    let current_status = '{{ $order->status->name }}';
    let order_id = '{{ $order->id }}';
    let observation = '';
    $('#select-status').change(function(event) {
      let status_id = $(this).val();
      let status_text= $("#select-status option:selected").text();
      Swal.fire({
        title:"¿Está seguro de cambiar el estado?",
        html:'<span style="font-size:25px;font-weight:bold; color : #3c8dbc">De ' + current_status + ' a ' + status_text + '</span>' + observation,
        showCancelButton:true,        
        confirmButtonColor: '{{ config('settings.color_primary') }}',
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        }).then(isConfirm => {
            if (isConfirm.value) {
                $.ajax({
                  url : '{{ route($name . '.change_status') }}',
                  type : 'POST',
                  dataType : 'json',
                  data : {status_id : status_id, order_id : order_id, '_token' : token}, 
                  beforeSend : function(){
                    $('.loader').fadeIn();
                  },
                  success : function(response){
                    if(response.status == true){
                      location.reload();
                    }
                  }
                });
              }
        });
    });

    $('.fas.fa-edit.url-guide').click(function(event) {
      $(this).fadeOut();
      $('.fas.fa-save.url-guide').fadeIn();
      $('#input-url-guide').fadeIn();
      $('#input-url-guide').focus();
    });

    $('.fas.fa-save.url-guide').click(function(event) {
      let url_guide = $('#input-url-guide').val();
      if (url_guide.length > 0){
        $(this).fadeOut();      
        $('.fas.fa-edit.url-guide').fadeIn();
        $('#input-url-guide').fadeOut();
        $.ajax({
            url : '{{ route($name . '.update_url_guide') }}',
            type : 'POST',
            dataType : 'json',
            data : {url_guide : url_guide, order_id : order_id, '_token' : token}, 
            beforeSend : function(){
              $('.loader').fadeIn();
            },
            success : function(response){
              if(response.status == true){
                location.reload();
              }
            }
        });
      }      
    });

    $('.fas.fa-edit.guide').click(function(event) {
      $(this).fadeOut();
      $('.fas.fa-save.guide').fadeIn();
      $('#input-guide').fadeIn();
      $('#input-guide').focus();
    });

    $('.fas.fa-save.guide').click(function(event) {
      let guide = $('#input-guide').val();
      if (guide.length > 0){
        $(this).fadeOut();      
        $('.fas.fa-edit.guide').fadeIn();
        $('#input-guide').fadeOut();
        $.ajax({
            url : '{{ route($name . '.update_guide') }}',
            type : 'POST',
            dataType : 'json',
            data : {guide : guide, order_id : order_id, '_token' : token}, 
            beforeSend : function(){
              $('.loader').fadeIn();
            },
            success : function(response){
              if(response.status == true){
                location.reload();
              }
            }
        });
      }      
    });
  </script>
@endpush