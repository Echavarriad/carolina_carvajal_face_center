@include('__partials.header')
<main class="main">
    <section>
        <div>
            <div>
                <div class="first_contetn_product flex">
                    <div class="content_product">
                        <img src="{{ asset('img/producto.png') }}" alt="">
                    </div>
                    <div class="content_product">
                        <div>
                            <img src="{{ asset('icon/icon (1).png') }}" alt="">
                        </div>
                        <div>
                            <img src="{{ asset('img/titulo (1).png') }}" alt="">
                        </div>
                        <div>
                            <img src="{{ asset('img/precio.png') }}" alt="">
                        </div>
                        <div>
                            {{-- <p>descripción</p>
                            <p>who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded
                                by desire, that they cannot foresee the pain and trouble that are bound to ensue; and
                                equal blame belongs to those who fail in their duty through weakness of will,

                                cases are perfectly simple and easy to distinguish. In a free hour, when our power of
                                choice is untrammelled and when nothing prevents our being able to do what we</p> --}}
                            <img src="{{ asset('img/info 1.png') }}" alt="">
                        </div>
                        <div class="content_venta flex">
                            <div class="cantidad_venta flex">
                                <p class="padd cantidad">cantidad</p>
                                <p class="padd num">01</p>
                                <div class="padd">
                                    <img src="{{ asset('img/menos.png') }}" alt="">
                                    <img src="{{ asset('img/mas.png') }}" alt="">
                                </div>
                            </div>
                            <div style="width: 100%">
                                <button class="buton-shop">Agregar al carrito</button>
                            </div>
                        </div>
                        <div class="content_info">
                            <div class="flex between" style="align-items: center">
                                <div>
                                    <p class="info_top">INFORMAcIóN adicional</p>
                                </div>
                                <div>
                                    <img src="{{ asset('img/desplegar.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="" alt="">
                </div>
            </div>
            <div>

            </div>
        </div>
    </section>
    <section>
        <div>
            <div style="display: flex;justify-content:center;margin-bottom: 5%">
                <img src="{{ asset('img/más productos relacionados.png') }}" alt="">
            </div>
            <div class="section_two_container flex">
                <div class="one_producst"><img src="{{ asset('img/bloque 2.png') }}" alt=""></div>
            </div>
        </div>
    </section>
</main>

@include('__partials.footer')
