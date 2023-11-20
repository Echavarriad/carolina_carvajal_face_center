@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('mng/jqtree/jqtree.css') }}">
@endpush
<ul>
  @foreach($childs as $child)
    <li>
        <label>
          @if($array_cats)
            <input type="checkbox" name="_categories[]" class="check_blue" value="{{$child['id']}}" {{ in_array($child['id'] , $array_cats) ? 'checked':''}}>
              {{ $child['name'] }} 
          @else
          <input type="checkbox" name="_categories[]" class="check_blue" value="{{$child['id']}}">
          {{ $child['name'] }} 
          @endif
        </label>
        @if(count($child['children']))
            @include('admin._partials.categories',['childs' => $child['children'] , 'array_cats' => $array_cats])
        @endif
    </li>
  @endforeach
</ul>