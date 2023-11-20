<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Ítem</th>
      <th>Código</th>
      <th>Estado</th>
      <th>Fecha</th>
    </tr>
  </thead>

  <tbody>
    @foreach($records as $item)
      <tr>
        <td>{{ $count }}</td>
        <td>{{ $item->code }}</td>
        <td><div style="text-align:center;background: {{ $item->status->color }}; width: 125px;height: 25px; color: #fff;font-weight: 600;font-size: 18px;">{{ $item->status->name }}</div></td>
        <td>{{ $item->date }}</td>
      </tr>  
      @php
        $count--;
      @endphp
    @endforeach
  </tbody>  
</table>