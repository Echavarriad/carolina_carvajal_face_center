<template>
	<div> 
    <div id="cont-images" class="row" style="margin-bottom: 20px;">
        <ul id="sortable" class="images-product">
          <h4>Arrastrar para ordenar</h4>
          <draggable v-model="images" group="options" @change="updateOrder">
            <li class="drag-handle ui-state-default" v-for="(item, index) in images" :key="index">
              <div class="image-rs">
                <img :src="`${base}/uploads/${item.image}`" class="img-thumbnail-list">
                <span>{{ index+1 }}</span>
                <button type="button" class="btn btn-danger btn-flat btn-gallery" title="Eliminar"  @click.prevent="deleteImage(item)"><i class="fa fa-trash"></i></button>
                <button v-if="url_update != ''" type="button" class="btn btn-success btn-gallery" title="Editar" @click.prevent="edit(item)"><i class="fa fa-edit"></i></button>
              </div>
            </li> 
          </draggable>
        </ul>
      </div>
		<vue-dropzone ref="objDZ" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-success="successEvent"></vue-dropzone>
    <!--Modal para seleccionar las opciones de cada atributo -->
  <div class="modal-category" v-if="show_modal">      
    <div class="content-modal">     
      <div class="col-md-12">
        <div class="row content-gral">
          <h2>Datos de la imagen</h2>
          <div v-if="has_title =='true'" class="col-lg-12">
             <label>Título</label>
             <input type="text" class="form-control" v-model="title">
          </div>
          <div class="col-lg-12">
             <label>Title de la imagen</label>
             <input type="text" class="form-control" v-model="tit">
          </div>
          <div class="col-lg-12">
             <label>Alt de la imagen</label>
             <input type="text" class="form-control" v-model="alt">
             
          </div>
        </div>
        <div class="col-md-2 col-sm-12"> 
          <div class="buttons-inline box-footer">
            <button class="btn btn-success" v-on:click.prevent="update"><i class="fa fa-save"></i> Guardar</button>
            <button class="btn btn-default" v-on:click="show_modal = false"><i class="fa fa-times"></i> Cancelar</button>
          </div>
        </div>
      </div>      
    </div> 
  </div>
  <!-- fin modal -->
	</div>
</template>

<style>
  dropzone-custom-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.dropzone-custom-title {
  margin-top: 0;
  color: #00b782;
}

.subtitle {
  color: #314b5f;
}
</style>
<script>
	import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	import draggable from 'vuedraggable'

  export default{
  components: {
      draggable
  },
  props:['data_images', 'model_id', 'url_uploads', 'url_delete', 'url_sort', 'url_update', 'size_images', 'has_title'],
    components: {
      vueDropzone: vue2Dropzone
   },
   mounted(){
   },
   data () {
    return {      
      base : window.base_url,
      images : [],
      title : null,
      content: "<h1>Some initial content</h1>",
      alt : null,
      tit : null,
      show_modal : false,
      dropzoneOptions: {
          url : this.url_uploads,
          thumbnailWidth: 150,
          maxFilesize: 10,
          acceptedFiles: ".jpg,.jpeg,.gif,.png,.bmp,.svg",
          dictFileTooBig: "El archivo pesa (@{{filesize}}MB). el máximo permitido es de: @{{maxFilesize}}MB.",
          dictInvalidFileType: "Este tipo de archivo no es permitido",
          dictDefaultMessage : "Arrastre los archivos aquí "+this.size_images +"",
          headers: {
             "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').content
          }
      }
    }
  },
  created(){
    this.images = this.data_images;
  },
  methods : {
  	sendingEvent (file , xhr , formData){
        let to = 123;
        formData.append("model_id", this.model_id);
  	},
    successEvent(response){
       if (this.$refs.objDZ.getUploadingFiles().length === 0 && this.$refs.objDZ.getQueuedFiles().length === 0) {
          location.reload();
        }
    },
     deleteImage(item){
      this.$swal({
          icon: 'question',
          title: '¿Desea eliminar la imagen?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
            if(result.value){
              axios.delete(`/admin/ajax/${this.url_delete}/${item.id}`)
                 .then(response => {
                    if(response.data.status){
                      this.$toast.success(response.data.message)
                      this.images.splice(this.images.indexOf(item) , 1);
                    }
                 })
                 .catch(err => {
                    console.log('Error al eleiminar');
              });
            }
         });
     },
     edit(item){
      this.show_modal = true;
      this.item_current = item;
      this.title = item.title
      this.alt = item.alt
      this.tit = item.tit
     },
     update(){
      this.item_current.title = this.title;
      this.item_current.alt = this.alt;
      this.item_current.tit = this.tit;
      axios.post(`admin/ajax/${this.url_update}`,this.item_current).then(response => {
          if(response.data.status){
            this.show_modal = false;
            this.$toast.success(response.data.message);
          }
          
        });
     },
     updateOrder(){
        axios.post(`admin/ajax/${this.url_sort}`,this.images).then(response => {
          
        });
      }
  }
}
</script>