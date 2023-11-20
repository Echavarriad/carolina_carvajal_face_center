<template>
    <div class="product_images">
        <div class="product_images--thumbs swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide" :class="item.class" v-for="item in galleries" :key="item.index" v-on:click="goToImage(item)">
                    <picture>
                        <img :src="`${base}/uploads/${item.image}`" :alt="product.alt" :title="product.tit">
                    </picture>
                </div>
            </div>
        </div>

        <div class="product_images--view swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide" v-for="item in galleries" :key="item.index">
                    <picture>
                        <img :src="`${base}/uploads/${item.image}`" :alt="product.alt" :title="product.tit">
                    </picture>
                </div>
            </div>
        </div>
    </div>
  </template>
  
  <script>
  // core version + navigation, pagination modules:
import Swiper from 'swiper';

  import 'swiper/css'
  
  
  export default {
    props: ['product', 'gallery', 'variations'],
    data(){
			return {
                base : window.base_url,
				swiperThumb: null,
				swiper: null,
                galleries: []
			}
    },
    created(){
        let index= 0;
        this.galleries.push({index: index, image: this.product.image, class: 'active'});
        index++;
        if(this.product.type_product === 'simple'){
            this.gallery.forEach(item => {
                this.galleries.push({index: index, image: item.image, class: ''});
                index++;
            });
        }else{
            this.variations.forEach(item => {
                if(item.image != null){
                    this.galleries.push({index: index, image: item.image, class: ''});
                    index++;
                }
            });
        }
        
    },
    mounted() {
        this.swiperThumb= new Swiper('.product_images--thumbs', {
            centeredSlidesBounds: true,
            slidesPerView: 3,
            watchOverflow: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            spaceBetween: 14,
            observer: true,  
            observeParents: true,
            breakpoints: {
                0: {
                direction: 'horizontal',
                },
                1280: {
                direction: 'vertical',
                }
            }
        });
        let self= this;
        this.swiper= new Swiper('.product_images--view', {
            slidesPerView: 1,
            spaceBetween: 14,
            loop: true,
            speed: 800,
            thumbs: {
                swiper: self.swiperThumb
            }
        });
    },
    methods: {
        goToImage(item){
            this.galleries.forEach(itm => {
                itm.class= '';
            });
            item.class= 'active';
            this.swiper.slideTo(item.index, 1000, true);
        },
        goToImageFromVariation(index){
            this.swiper.slideTo(index, 1000, true);
        }
    }
  }
  </script>
  <style>
.product_cnt .product_content .product_images .product_images--thumbs .swiper-slide.active picture {
    filter: grayscale(0);
}
</style>