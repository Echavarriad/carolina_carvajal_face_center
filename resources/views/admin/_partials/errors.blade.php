@if(count($errors))
@php
	// dd($errors);
@endphp
	<div class="alert alert-danger alert-dismissible">
	   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	   Se encontraron los siguientes errores:
	   <ul>
	   	@foreach($errors->all() as $error)
	   	<li>{{ $error }}</li>
	   	@endforeach
	   </ul>
	</div>
@endif