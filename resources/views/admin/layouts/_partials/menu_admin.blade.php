<ul class="sidebar-menu">

    <li class="header"><a href="{{ route('home.index') }}"><i class="fa fa-home"></i> <span>INICIO</span></a></li>

    <li><a href="{{ route('sections.index') }}"><i class="fa fa-bars"></i> <span>Secciones</span></a></li>
    <li><a href="{{ route('sliders.index') }}"><i class="fa fa-images"></i> <span>Slider</span></a></li>
    <li><a href="{{ route('articles.index') }}"><i class="fa fa-newspaper"></i> <span>Blogs</span></a></li>
    <li class="header">Tienda</li>
    <li><a href="{{ route('products.index') }}"><i class="fa fa-p"></i> <span>Productos</span></a></li>
    <li><a href="{{ route('order.index') }}"><i class="fa fa-p"></i> <span>Pedidos</span></a></li>
    {{-- <li><a href="{{ route('coupons.index') }}"><i class="fa fa-tag"></i> <span>Cupones</span></a></li> --}}
    {{-- <li><a href="{{ route('status.index') }}"><i class="fa fa-s"></i> <span>Estados</span></a></li> --}}
    <li><a href="{{ route('shippings.index') }}"><i class="fa fa-truck-fast"></i> <span>Tabla de envíos</span></a></li>

    <li class="header">Configuración</li>
    <li><a href="{{-- {{ route('settings') }} --}}"><i class="fa fa-cogs"></i> <span>Configuración</span></a></li>
    <li><a href="{{-- {{ route('translation.index') }} --}}"><i class="fa fa-language"></i> <span>Traducciones</span></a></li>
    <li><a href="{{-- {{ route('admins.index') }} --}}"><i class="fa fa-user-plus"></i> <span>Administradores</span></a></li>
    <li><a href="{{-- {{ route('users.index') }} --}}"><i class="fa fa-user-plus"></i> <span>Users</span></a></li>
    <li><a href="{{-- {{ route('modules.index') }} --}}"><i class="fa fa-m"></i> <span>Módulos</span></a></li>
</ul>
