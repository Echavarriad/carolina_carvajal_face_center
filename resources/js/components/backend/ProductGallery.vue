<template>
    <div class="row gallery">
        <h2>Galería del producto (517 x 709 px)</h2>
        <label>Arrastre lás imágenes para ordenar</label><br>   
        <div class="container">
            <draggable v-model="gallery" group="options" @change="updateOrder" :key="uploadsImageKey">
                <div class="item" v-for="(item, index) in gallery" :key="index">
                    <upload-images :img="item.image" :id="item.id" with="517" height="709" folder="productgallery" model='ProductGallery' field='image' :del="true" :delrecord="true" :key="uploadsImageKey"></upload-images>
                </div>
            </draggable>
        </div> 
        <button class="btn btn-primary" title="Agregar" @click.prevent="addImage()">
            <img :src="`${base}/mng/img/plus.svg`" alt=""> Agregar Imagen
        </button>        
    </div>
</template>


<script>
import draggable from 'vuedraggable'
export default{
    components: {
        draggable,
    },
    props:{
        product:{
            type:Object,
            required:true
        }
    },
    data(){
        return {
            base : window.base_url,
            uploadsImageKey: 0,
            gallery: []
        }
    },
    async mounted() {
        this.gallery= this.product.gallery;

    },
    methods:{
        async addImage(){
            const resp= await axios.post('/admin/ajax/add-gallery-to-product', {product: this.product.id});
            this.gallery = resp.data.gallery;
            this.uploadsImageKey++;
        },
        async updateOrder(){
            const resp= await axios.post(`admin/ajax/order-products-gallery`,{product: this.product.id, gallery: this.gallery});
            this.gallery= resp.data.gallery;
            this.uploadsImageKey++;
        }
    }
}
</script>

<style>
    .gallery {
        position: relative;
    }
    .gallery .btn.btn-primary {
        position: absolute;
        right: 5%;
        top: 50px;
    }

    .gallery .container {
        display: flex;
    }

    .gallery .container .item {
        float:left;
    }

    .gallery .container .image-upload{
        margin-right: 50px;    
        margin-top: 20px;
    }
</style>