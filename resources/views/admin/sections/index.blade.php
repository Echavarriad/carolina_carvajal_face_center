@extends('admin.layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    @include('admin._partials.messages')
    <section class="content-header">
        <h1>
            Secciones
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (config('app.debug'))
                            <div class="pull-right">
                                <a href="{{ route('sections.create') }} " class="btn btn-primary"><i class="fa fa-plus"></i>
                                    Nuevo</a>
                            </div>
                        @endif
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="order">ID</th>
                                    <th>Nombre</th>
                                    <th class="w-15">SEO</th>
                                    <th class="w-15">Contenidos</th>
                                    <th class="w-15">Mostrar</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($records as $item)
                                    <tr>
                                        <td> {{ $item->id }}</td>
                                        <td> {{ $item->name }}</td>
                                        <td>
                                            @if ($item->seo)
                                                <a href="{{-- {{ route('sections.edit' , [$item->id]) }} --}}" class="btn btn-primary btn-flat"
                                                    title="Contenidos"><i class="fa fa-area-chart"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{-- {{ route('contents.show' , [$item->id]) }} --}}" class="btn btn-primary btn-flat"
                                                title="Contenidos"><i class="fa fa-bars"></i></a>
                                        </td>
                                        <td class="btn-actions-index">
                                            <input type="checkbox" name="{{-- {{ $item->id }} --}}" class="input-switch"
                                                {{-- {{ $item->show ? 'checked':''}} --}} data-size="mini">
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('mng/plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('mng/plugins/toastr/toastr.min.css') }}">
@endpush
@push('js')
    <script src="{{ asset('mng/plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('mng/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function(event) {
                event.preventDefault();
                $(this).parent().submit();
            });

            $(".input-switch").bootstrapSwitch({
                onSwitchChange: function(e, status) {
                    updateSettings($(this).attr('name'), status);
                }
            });

            /*function updateSettings(id , status){
                 $.ajax({

                      type : 'POST',
                      dataType : 'json',
                      data : {_token:'{{ csrf_token() }}' , section:id,status:status},
                           success : function(res){
                           if(res.status == true){
                                     toastr.options.positionClass = 'toast-bottom-right';
                                     toastr.success(res.message);
                           }
                           }
                 });
            }*/
        });
    </script>
@endpush
