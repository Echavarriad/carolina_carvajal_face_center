
@extends('layouts.master')


@section('content')

<section id="ingresar" style="margin-top:160px;">
		<div class="cont7">
			<div class="formCont">
				<div class="cont9">
					<form action="{{route('password.email')}}" method="post">
						@csrf
						<div class="row">

							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								 @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                 {{ session('status') }}
                                 </div>
                               @endif
								@foreach($errors->all() as $error)
								<div class="alert alert-danger">
									{{ $error }}
								</div>
						   	    @endforeach
								<div class="row">
									<label> Correo eléctronico</label>
					    			<input type="email" name="email" placeholder="{{__('generals.data_here')}}">
								</div>
								
						<div class="row">
							<div class="enviar">
								<input type="submit" value="{{__('generals.recover_pass')}}">
							</div>
						</div>
							</div>
							
						</div>
				  </form>
				</div>
			</div>
		</div>
	</section>


@endsection

@section('js')
<script>
	$(document).ready(function($) {
		$("html, body").animate({ scrollTop: 10 }, 100);
	});
</script>
@endsection