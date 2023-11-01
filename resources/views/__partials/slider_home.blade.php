<section>
    <div class="slider_content_two left fade">
        <img src="{{ asset('img/slider.png') }}" alt="" width="100%">
    </div>
    <div class="slider_content_two left fade">
        <img src="{{ asset('img/slider.png') }}" alt="" width="100%">
    </div>
    <div class="buttom-banner">
        <a class="text_buttom" onclick="plusSlides(-1)">BA<br>CK</a>
        <div>
            <p class="text_buttom">-</p>
        </div>
        <a class="text_buttom" onclick="plusSlides(1)">NE<br>XT</a>
    </div>
    {{-- <div class="img_over_banner">
        <img src="{{ asset('img/mujer 2.png') }}" alt="">

    </div> --}}
    {{-- <div class="text_banner_right">
        <h1>Find the perfect
            shade for
            you once...</h1>
    </div>
    <div class="text_banner_left">
        <p>ba
            ck</p>
        <hr>
        <p>
            ne
            xt
        </p>
    </div> --}}
</section>
<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("slider_content_two");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex - 1].style.display = "block";
    }
</script>
