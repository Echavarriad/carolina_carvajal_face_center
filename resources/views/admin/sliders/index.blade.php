@extends('admin.layouts.admin')
@section('content')
    @include('admin._partials.messages')
    <section class="content-header">
        <h1>{{ $plural }}</h1>
    </section>

    <section class="content" id="app">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <div class="pull-right">
                            <a href=" {{ route($name . '.create') }} " class="btn btn-primary"><i class="fa fa-plus"></i>
                                Nuevo</a>
                        </div>
                        <span class="info">Para cambiar lás imágenes haga clic sobre la imagen y seleccione la
                            nueva.</span>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="order">Ordenar</th>
                                    <th>Imagen (1386 x 670 px)</th>
                                    <th class="w-15 actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="sortable" data-url="{{-- {{ route($name . '.order') }} --}}">
                                {{-- @foreach ($records as $item)
                                    <tr id="{{ $item->id }}">
                                        <td class="drag-handle"><a href="#"><i
                                                    class="fas fa-arrows-alt fa-2x"></i></a></td>
                                        <td>
                                            <upload-images :img="{{ json_encode($item->image) }}"
                                                id="{{ json_encode($item->id) }}" with="1386" height="670"
                                                folder="slider" model='Slider' field='image'></upload-images>
                                        </td>
                                        <td class="btn-actions-index">
                                            <a href="{{ route($name . '.edit', [$item->id]) }}"
                                                class="btn btn-primary btn-flat" title="Editar"><i
                                                    class="fa fa-edit"></i></a>
                                            <form action="{{ route($name . '.destroy', [$item->id]) }}" method="POST"
                                                style="display:inline;">@csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-flat btn-delete"
                                                    title="Eliminar" data-name ='' data-table = 'este registro'><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
