@include('__partials.header')
<main class="main">
    <section class="banner">
        <div class="content_main">
            {{-- <div class="subcontent">
                <div class="content_first flex between">
                    <p class="title">On the other hand, we magna<br> with right magna labial.</p>
                    <div class="main_logo flex">
                        <img src="{{ asset('icon/icon.png') }}" alt="">
                        <p>On the other hand, we denounce with righteous</p>
                    </div>
                </div>
                <div class="content_two flex">
                    <div class="section_about">
                        <h3>Beneficios del
                            perfilado de labios</h3>
                        <p>este tratamiento depende exclusivamente del tipo de ácido hialurónico que se utilice y del
                            especialista que lo aplique. Entre los beneficios que encontrarás con esta técnica
                            están:<br>

                            Mejor proporción labial.
                            Labios más atractivos gracias a la definición de sus bordes.
                            Labios más hidratados y con un mejor tono.
                        </p>
                    </div>
                    <div class="section_about">
                        <h3>Diferencia entre perfilado y relleno de labios</h3>
                        <p>Los dos tratamientos se realizan con infiltraciones de ácido hialurónico pero persiguen
                            objetivos diferentes.<br>

                            Con el relleno de labios con ácido hialurónico buscamos aumentar el volumen y mantener la
                            hidratación de la piel, mientras que con el perfilado conseguimos redefinir la forma de los
                            labios.
                    </div>
                    <div class="section_about">
                        <h3>Perfilado de labios,
                            la técnica</h3>
                        <p>El perfilado de labios se realiza con ácido hialurónico que se infiltra a través de micro
                            cánulas en las capas más superficiales de la piel del labio.<br>

                            De forma inmediata, mejora el contorno labial e hidrata la piel. La duración del efecto
                            dependerá de cada persona, es decir, del tiempo que tarda cada organismo en absorber el
                            tratamiento.</p>
                    </div>
                </div>
            </div> --}}
            <img src="{{ asset('img/info.png') }}" alt="" width="100%">
        </div>
    </section>
    <section>
        <div class="flex">
            {{-- <div>
                <img src="{{ asset('img/01 (2).png') }}" alt="">
                <img src="{{ asset('img/02 (1).png') }}" alt="">
            </div>
            <div>
                <div><img src="{{ asset('img/Rectangle 42 (1).png') }}" alt=""></div>
            </div>
            <div>
                <div><img src="{{ asset('img/04.png') }}" alt=""></div>
                <div><img src="{{ asset('img/05.png') }}" alt=""></div>
            </div>
            <div>
                <div><img src="{{ asset('img/06.png') }}" alt=""></div>
                <div><img src="{{ asset('img/Rectangle 45.png') }}" alt=""></div>
            </div> --}}
            <img src="{{ asset('img/galeria.png') }}" alt="" width="100%">
        </div>
    </section>
    <section>
        <div class="contact form_main_content flex">
            <div class="contact_left" style="width: 100%">
                <p>LET'S START<br> YOUR<br>
                    HEALTHY ADVENTURE</p>
            </div>
            <div style="width:100%">
                <form action="">
                    <div class="flex">
                        <div class="content_input"><input type="text" class="form-control" name=""
                                id="" placeholder="Nombre completo">
                        </div>
                        <div class="content_input"><input type="text" class="form-control" name=""
                                id="" placeholder="Teléfono / movil"></div>
                    </div>
                    <div class="flex">
                        <div class="content_input"><input type="text" class="form-control" name=""
                                id="" placeholder="Correo electrónico"></div>
                        <div class="content_input"><input type="text" class="form-control" name=""
                                id="" placeholder="Asunto"></div>
                    </div>
                    <div class="content_input">
                        <textarea class="form-control" name="" id="" cols="40" rows="5" placeholder="Mensaje"></textarea>
                    </div>
                    <div class="flex between content_input" style="margin-top: 5px">
                        <div class="flex" style="justify-content: center;align-items: center;">
                            <div style="margin-right: 5px">
                                <input type="checkbox" name="" id="">
                            </div>
                            <div class="terms">
                                <p>He leído y acepto las condiciones de tratamiento de datos // clic aquí</p>
                            </div>
                        </div>
                        <div>
                            <button class="button">Enviar mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

@include('__partials.footer')
