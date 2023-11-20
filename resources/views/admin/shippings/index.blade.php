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
                        <div style="position: relative; display: flex; align-items: center; justify-content: space-between;">
                            <div style="margin-bottom: 26px;">
                                <form action="{{ url()->current() }}" style="display: flex;width: 500px;">
                                    <input type="text" name="city" class="form-control"
                                        placeholder="Ingrese el nombre de la ciudad" value="{{ $city ?? '' }}">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                    <a href=" {{ route($name . '.index') }} " class="btn btn-dark">Limpiar</a>
                                </form>
                            </div>
                            <div class="pull-right">
                                <a href=" {{ route($name . '.create') }} " class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Nuevo</a>
                            </div>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ciudad</th>
                                    <th>Valor Mínimo de la compra</th>
                                    <th>Valor del envío</th>
                                    <th class="w-10">Activo</th>
                                    <th class="w-15 actions">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $item)
                                    <tr>
                                        <td>{{ $item->name_city }}</td>
                                        <td>{{-- {{ core()->currency($item->value_min_buy) }} --}}</td>
                                        <td>{{-- {{ core()->currency($item->shipping_fee) }} --}}</td>
                                        <td class="btn-actions-index">
                                            {{-- <c-switch status="{{ $item->is_active }}" url="{{ route($name . '.active') }}"
                                                id="{{ $item->id }}"></c-switch> --}}
                                        </td>
                                        <td class="btn-actions-index">
                                            <a href="{{ route($name . '.edit', [$item->id]) }}"
                                                class="btn btn-primary btn-flat" title="Editar"><i
                                                    class="fa fa-edit"></i></a>
                                            <form action="{{ route($name . '.destroy', [$item->id]) }}" method="POST"
                                                style="display:inline;">@csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-flat btn-delete"
                                                    title="Eliminar" data-name ='{{ $item->name }}'
                                                    data-table = 'este registro'><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $records->appends(request()->input())->links('admin._partials.pagination') }}
            </div>
        </div>
    </section>
@endsection
