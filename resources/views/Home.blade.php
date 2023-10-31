@include('__partials.header')
<main class="main">
    <section class="section_one_main">
        <section class="banner">
            <div>
                <h1>DISCOVER NOW / scrolling down</h1>
            </div>
            <div class="backgroun-img">
                <div class="over_img">
                    <p class="type_img">video / intro</p>
                    <div class="flex content_text">
                        <p>Born from the knowledge
                            carolina carvajal</p>
                        <img src="{{ asset('icon/play.svg') }}" alt="">
                    </div>
                </div>
                <img src="{{ asset('img/Rectangle 13.png') }}" alt="" width="100%">

            </div>
        </section>
        <section class="section_two_main">
            <div class="section_two_main--top">
                <h2>Nuestros tratamientos makeup<br> that’s skin loving</h2>
            </div>
            <div class="flex">
                <div>
                    <img src="{{ asset('img/images.png') }}" alt="">
                </div>
                <div class="section_two_text">
                    <p class="section_two_text--one-p">Perfilamiento y aumento<br> labial.</p>
                    <p class="section_two_text--two-p">On the other<br> hand, we magna<br> with righteous<br> labial.
                    </p>
                    <p class="section_two_text--three-p">
                        who are so beguiled and demoralized by the<br> charms of pleasure of the moment, so blinded<br>
                        by
                        desire, that they cannot foresee the pain<br> and trouble that are bound to ensue; and<br> equal
                        blame
                        belongs to those who fail in their<br> duty through weakness of will,<br><br> which is the same
                        as
                        saying
                        through<br> shrinking from toil and pain. These cases are<br> perfectly simple and easy to
                        distinguish.
                    </p>
                    <a href="" type="buttom" class="">... Conoce más</a>
                </div>
            </div>
        </section>
    </section>

    <section class="section_three_main">
        <div class="flex">
            <div class="content section_content_one">
                <img src="{{ asset('img/01.png') }}" alt="">
            </div>
            <div class="content section_content_two">
                <img src="{{ asset('img/02.png') }}" alt="">
            </div>
            <div class="content section_content_three section_content_three--text">
                <div class="flex">
                    <div>
                        <img src="{{ asset('icon/icono.png') }}" alt="">
                    </div>
                    <div class="section_logo">
                        <h4>environmentally<br> friendly<br> products</h4>
                    </div>
                </div>
                <div class="section_title">
                    <h3>We have carefully selected each ingredient, so that not only the products</h3>
                </div>
                <div class="section_texte">
                    <p>
                        who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by
                        desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame
                        belongs to those who fail in their duty through weakness of will, <br><br>which is the same as
                        saying
                        through shrinking from toil and pain. These cases are perfectly simple and easy to distinguish.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="section_four_main">
        <div class="flex center">
            <div class="section_four_content">
                <div><img src="{{ asset('img/Rectangle 17.png') }}" alt=""></div>
                <div>
                    <p>New survey sheds light on providers'
                        embrace of telemedicine</p>
                    <a href="">... Leer este blog</a>
                </div>
            </div>
            <div class="section_four_content">
                <div><img src="{{ asset('img/Rectangle 17 (1).png') }}" alt=""></div>
                <div>
                    <p>New survey sheds light on providers'
                        embrace of telemedicine</p>
                    <a href="">... Leer este blog</a>
                </div>
            </div>
            <div class="section_four_content">
                <div><img src="{{ asset('img/Rectangle 18.png') }}" alt=""></div>
                <div>
                    <h4>blogs y novedades</h4>
                    <h4>How Apple Vision Pro can be deployed in healthcare</h4>
                    <h4>leer este blog</h4>
                </div>
            </div>
        </div>
    </section>
    <section class="section_five_content">
        <div class="section_five_content--top">
            <h1>What people are saying</h1>
            <p>Experience worry / free beauty</p>
        </div>
        <div class="section_five_content--buttom flex">
            <div class="section_five_content--text">
                <h3 class="name">Sara Martínez</h3>
                <p class="type">testimonio</p>
                {{-- <i class="fa-brands fa-gratipay" style="color: #d3bf90;"></i> --}}
                <hr>
                <p class="comment">products feel amazing on the skin and so nourishing. I am able to easily create a
                    natural look or an
                    evening look.</p>
            </div>
            <div class="section_five_content--text">
                <h3>Alejandra Buitrago</h3>
                <p class="type">testimonio</p>
                <hr>
                <p>products feel amazing on the skin and so nourishing. I am able to easily create a natural look or an
                    evening look.</p>
            </div>
            <div class="section_five_content--text">
                <h3>Ronald Ayazo</h3>
                <p class="type">testimonio</p>
                <hr>
                <p>products feel amazing on the skin and so nourishing. I am able to easily create a natural look or an
                    evening look.</p>
            </div>
        </div>
    </section>
</main>

@include('__partials.footer')
