@include('__partials.header')
<main class="main">
    <section>
        <div class="main_content flex">
            {{-- <div class="title_contact">
                <h1>Nos encantaría hablar con nuestro gran<br> cliente, llámanos o escríbenos.</h1>
            </div>
            <div class="body_content flex between">
                <div class="padding address">
                    <p class="title">Donde puedes encontrarnos</p>
                    <p class="boddy">
                        technology park Quai Gustave-Ador 62, 1207<br> Genève medellín / colombia
                    </p>
                </div>
                <div class="padding email">
                    <p class="title">donde puedes escribirnos</p>
                    <p class="boddy">
                        hello@carolina-carvajal.com<br>
                        servicio-cliente@carolina-carvajal.com
                    </p>
                </div>
                <div class="padding phone">
                    <p class="title">donde puedes llamarnos</p>
                    <p class="boddy">
                        + 32 99 260 55 08<br>
                        604 + 6050145 Ext: 203300 - 203301
                    </p>
                </div>
            </div> --}}
            <img src="{{ asset('img/info (1).png') }}" alt="" width="100%">
        </div>
    </section>
    <section>
        <div class="flex">
            {{-- <div class="flex" style="width: 100%">
                <img src="{{ asset('img/01(3).png') }}" alt="" width="100%">
                <img src="{{ asset('img/Rectangle 42.png') }}" alt="" width="100%">
            </div>
            <div style="width: 100%">
                <img src="{{ asset('img/Rectangle 46.png') }}" alt="" width="100%">
            </div> --}}
            <img src="{{ asset('img/galeria (1).png') }}" alt="" width="100%">
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
