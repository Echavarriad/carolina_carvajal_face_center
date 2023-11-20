<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>O.C E-commerce</th>
      <th>Cliente</th>
      <th>Documento</th>
      <th>Total</th>
      <th>Estado</th>
      <th>Fecha</th>
      <th class="actions">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @foreach($orders as $order)
      <tr>
        <td><a href="{{ route('order.show' , $order->id) }}">{{$order->reference}}</a></td>
        <td>{{ $order->customer_name . ' ' . $order->customer_lastname }}</td>
        <td>{{ $order->customer_document }}</td>
        <td>{{ $order->total_format }}</td>
        <td><label class="label" style="font-size: 15px;background: {{ $order->status->color }};">{{ $order->status->name }}</label></td>
        <td>{{ $order->created_at }}</td>
        <td class="btn-actions-index"><a href="{{ route('order.show' , [$order->id]) }}" class="btn btn-primary btn-flat" title="Detalle"><i class="fas fa-eye"></i></a>
        </td>
      </tr>  
    @endforeach
  </tbody>  
</table>