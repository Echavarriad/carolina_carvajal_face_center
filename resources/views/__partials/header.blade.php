<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                    <li><a href="#">INICION</a></li>
                    <li><a href="#">NOSOTROS</a></li>
                    <li><a href="#">TRATAMIENTOS</a></li>
                    <li><a href="#">BLOGS</a></li>
                </ul>
            </nav>
            <nav class=" flex center-item">
                <ul class="nav-links">
                    <li><a href="">PRODUCTOS</a></li>
                    <li><a href="">CONTACTO</a></li>
                    <li><a href=""></a>ESP/ENG</li>
                    <li><a href=""></a><i class="fa-solid fa-rotate" style="color: #000000;"></i></li>
                    <li><a href=""><i class="fa-regular fa-circle-user" style="color: #000000;"></i></a></li>
                    <li><a href=""><i class="fa-solid fa-cart-shopping" style="color: #000000;"></i></a></li>
                </ul>
            </nav>
        </div>
    </header>
    @include('__partials.slider')
