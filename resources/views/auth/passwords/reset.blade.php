
@extends('layouts.master')


@section('content')

<section id="ingresar" style="margin-top:160px;">
		<div class="cont7">
            <div class="nosotrosCont">
            	<h2 >{{__('messages.restore_password')}}</h2>
            </div>
			<div class="formCont">
				<div class="cont9">
					<form action="{{route('password.update')}}" method="post">
						@csrf
                        
						<div class="row">
                             
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								 @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                 {{ session('status') }}
                                 </div>
                               @endif
							     
								<div class="row">
									<label> Correo eléctronico</label>
									@if ($errors->has('email'))
                                    <span>
                                        <strong class="text-danger">{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
					    			<input type="email" name="email" placeholder="{{__('generals.data_here')}}">
								</div>
								<div class="row">
									<label> {{__('messages.new_password')}}</label>
									@if ($errors->has('password'))
                                    <span>
                                        <strong class="text-danger">{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
					    			<input type="password" name="password" placeholder="{{__('generals.data_here')}}">
								</div>
								<div class="row">
									<label> {{__('messages.repeat_new_password')}}</label>
					    			<input type="password" name="password_confirmation" placeholder="{{__('generals.data_here')}}">
								</div>
								
						<div class="row">
							<div class="enviar">
								<input type="hidden" name="token" value="{{$token}}">
								<input type="submit" value="{{__('messages.restore_password')}}">
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