@extends('admin.layouts.admin')

@section('content')
    <section class="content-header">
        <h1>
            Nuevo usuario
        </h1>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="box box-info">
                    @include('admin._partials.errors')
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{ route('users.store') }}" method="POST" class="form-horizontal"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <label>Documento</label>
                            <input type="text" name="document" class="form-control" value="{{ old('document') }}"
                                required>
                            <br>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{ route('users.index') }}" class="btn btn-default">Cancelar</a>
                            <button type="submit" class="btn btn-success pull-right">Guardar</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
