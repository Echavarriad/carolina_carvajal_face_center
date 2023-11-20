<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Pedido {{ $order->reference }}</title>
        <style>
        .projection-table{
             width: 100%;
             display: table;
             border-collapse: separate !important;
             border-spacing: 0; 
         }

         .projection-table thead{
             border: none;
             width: 100%;
         }

         .projection-table thead tr th{
            text-align: center;
            line-height: 25px;
            border: 1px solid #989898;
            background-color: #d7d7d7;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            font-size: 1.1rem;
         }
        .projection-table tbody tr:LAST-CHILD td:FIRST-CHILD {
            border-bottom-left-radius: 10px;

        }
        .projection-table tbody tr:LAST-CHILD td:LAST-CHILD {
            border-bottom-right-radius: 10px;
        }

         .projection-table thead tr td{
           	text-align: center;
           	line-height: 25px;
            border: 1px solid #989898;
            background-color: #d7d7d7;
            font-size: 16px;
            font-weight: 800;
         }
        .projection-table tbody tr td{
            text-align: center;
            line-height: 25px;
            border: 1px solid #989898;
            font-size: 15px;
            padding: 4px 0px;
         }
         .cont-left{
					 display: inline-block;
					}
					.cont-right{
					 display: inline-block;
					 float: right;
					}
					.row{
					 width: 100%;
					 display: table;
					}

					.left {
					float: left;
					text-align: left;
					margin: 2px 10px;
					display: table-cell;
					min-width: 250px;
					}
					.right {
					  float: left;
					  text-align: right;
					  margin: 2px 10px;
					  display: table-cell;
					  min-width: 250px;
					}
    </style>
    </head>
    <body>
    	<div class="row">
    		<div class="cont-left">
          <strong style="text-align: left">Reference : </strong>
          <span>{{ $order->reference }}</span>         
  			</div>
  			<div class="cont-right">
          <strong style="text-align: right">Fecha : </strong>
          <span>{{ $order->format_order_date }}</span>          
  			</div>
    	</div><br><br>	
  		 	<div style="background-color: #f4831f;text-align: left;">
		 			 	<a href="{{ url('/') }}" target="_blank">
                <img style="margin-top: 11px;" src="{{ asset('uploads/' . config('settings.shop_logo_email')) }}" alt="Moteliando" width="300">
          	</a>        
        </div><br>
        <div class="row">
	    		<div class="left">
           	<label for=""><strong style="text-align: left">Nombre: </strong><span>{{ $order->customer_name }}</span></label><br>
           	<label for=""><strong style="text-align: left">Email: </strong><span>{{ $order->customer_email }}</span></label><br>
            <label for=""><strong style="text-align: left">{{ $order->customer_type_document }} : </strong><span>{{ $order->customer_document }}</span></label><br>
           	@if (!empty($order->customer_phone))
           		<label for=""><strong style="text-align: left">Teléfono: </strong><span>{{ $order->customer_phone }}</span></label><br>
           	@endif           	
          	               
	  			</div>

          @if ($order->type_cart == 'product')
  	  			<div class="right">
             	<label for=""><strong style="text-align: left">Dirección: </strong><span>{{ $order->address_shipping->address }}</span></label><br>
            	@if (!empty($order->address_shipping->complement))
             		<label for=""><strong style="text-align: left">Complemento: </strong><span>{{$order->address_shipping->complement }}</span></label><br>
             	@endif
             	<label for=""><strong style="text-align: left">Departamento: </strong><span>{{ $order->address_shipping->state }}</span></label><br>
           		<label for=""><strong style="text-align: left">Ciudad: </strong><span>{{ $order->address_shipping->city }}</span></label><br>      
  	  			</div>
          @endif          
	    	</div> 
       <br>
        <div class="contenido">
            <table class="projection-table">
               <thead>
               	<tr>
                  <td></td>
               		<td style="width: 40%;">{{ $order->type_cart == 'product' ? 'Producto' : 'Habitación' }}</td>
               		<td style="width: 10%;">Cant.</td>
               		<td style="width: 15%;">Precio</td>
               		<td style="width: 15%;">Total</td>
               	</tr>
               </thead>
               <tbody>
               	@foreach ($order->items as $item)
             			<tr>
                    <td><img src="{{ asset('uploads/' . $item->image) }}" width="70"></td>
               		 	<td>{{ $item->name }}</td>
	                  <td>{{ $item->quantity }}</td>
	                  <td>{{ $item->price_format }}</td>
	                  <td style="text-align: right;padding-right:6px">{{ $item->total_format }}</td>
	               	</tr>
               	@endforeach
               		
                  @if ($order->type_cart == 'product')
                    <tr>
                 		 	<td colspan="4" style="text-align: right;font-weight: 600;padding-right: 6px;">Subtotal </td>
                 		 	<td style="text-align: right;padding-right:6px">{{ $order->subtotal_format }}</td>
  	               	</tr>
  	               	<tr>
                 		 	<td colspan="4" style="text-align: right;font-weight: 600;padding-right: 6px;">Envío </td>
                 		 	<td style="text-align: right;padding-right:6px">{{ $order->shipping_format }}</td>
  	               	</tr>
                  @endif
	               	<tr>
               		 	<td colspan="4" style="text-align: right;font-weight: 600;padding-right: 6px;">TOTAL </td>
               		 	<td style="font-weight: 800;font-size: 20px;text-align: right;padding-right:6px">{{ $order->total_format }}</td>
	               	</tr>
               
               </tbody>
            </table>
        </div>
    </body>
</html>