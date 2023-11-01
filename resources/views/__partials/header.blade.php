<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Abuot_us.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slider') }}">
    <link rel="stylesheet" href="{{ asset('css/Treatments.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <script src="https://kit.fontawesome.com/2f38503f3e.js" crossorigin="anonymous"></script>
    <title>Home</title>
</head>

<body>
    <header class="header ">
        <div class="main_content_header flex between">
            <nav class="flex between center-item">
                <div class="logo">
                    <img src="{{ asset('img/logo 2.png') }}" alt="Logo Carolina Carvajal">
                </div>
                <ul class="nav-links">
                    <li><a href="{{ route('Home') }}">home</a></li>
                    <li><a href="{{ route('about_us') }}">nosotros</a></li>
                    <li><a href="{{ route('treatments') }}">Tratamientos</a></li>
                    <li><a href="{{ route('blogs') }}">Blogs</a></li>
                </ul>
            </nav>
            <nav class=" flex center-item">
                <ul class="nav-links">
                    <li><a href="{{ route('products') }}">PRODUCTOS</a></li>
                    <li><a href="{{ route('contact') }}">CONTACTO</a></li>
                    <li><a href=""></a>ESP/ENG</li>
                    <li><a href=""></a><i class="fa-solid fa-rotate" style="color: #000000;"></i></li>
                    <li><a href=""><i class="fa-regular fa-circle-user" style="color: #000000;"></i></a></li>
                    <li><a href=""><i class="fa-solid fa-cart-shopping" style="color: #000000;"></i></a></li>
                </ul>
            </nav>
        </div>
    </header>
    <nav role="navigation" class="navigation">
        <div id="menuToggle">
            <input type="checkbox" />
            <span></span>
            <span></span>
            <span></span>

            <ul id="menu">
                <div class="logo">
                    <img src="{{ asset('img/logo 2.png') }}" alt="Logo Carolina Carvajal">
                </div>
                <li><a href="{{ route('Home') }}">Home</a></li>
                <li><a href="{{ route('about_us') }}">Nosotros</a></li>
                <li><a href="{{ route('treatments') }}">Tratamientos</a></li>
                <li><a href="{{ route('blogs') }}">Blogs</a></li>
                <li><a href="{{ route('products') }}">PRODUCTOS</a></li>
                <li><a href="{{ route('contact') }}">CONTACTO</a></li>
                <li><a href=""></a>ESP/ENG</li>
                <li><a href=""></a><i class="fa-solid fa-rotate" style="color: #000000;"></i></li>
                <li><a href=""><i class="fa-regular fa-circle-user" style="color: #000000;"></i></a></li>
                <li><a href=""><i class="fa-solid fa-cart-shopping" style="color: #000000;"></i></a></li>
            </ul>
        </div>
    </nav>
    @include('__partials.slider')
