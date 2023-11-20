<template>
	<div>			 
		<div class="row">
			<div class="image-upload"  title="Clic para cambiar la imagen" :id="`div-${folder}-${id}`">
				<img v-if="image !== null" :src="`${base}/uploads/${image}`"   @click="launchFilePicker(image)" width="100">
					<img v-else :src="`${base}/mng/img/add-image.png`"  class="not-image"  @click="launchFilePicker(image)">
				<div v-if="active_load" class="gif-load">
					<img :src="`${base}/mng/img/load.gif`"  alt="">
				</div>
				<button type="button" v-if="delete_img" class="btn btn-danger" title="Eliminar" @click="deleteImage(item)"><img :src="`${base}/mng/img/trash-white.svg`"></button>
			</div>
		</div> 
		<input style="display:none" type="file" :id="`${field}-inputFile-${id}`" @change="saveImage"/>
	</div>
</template>
<script>
	export default{
		props:['img', 'id', 'with', 'height', 'folder', 'model', 'field', 'del', 'delrecord'],
		data(){
			return {
				base : window.base_url,
        		image: '',
				active_load : false,
        		image_before: '',
				delete_img: false
			}
		},
		created(){
      		this.image_before= this.img;
      		this.image= this.img;
			this.delete_img= (this.del && this.image !== null) ? true : false;
		},
		methods : {
			launchFilePicker(item){
				this.current_image = item;
       			 document.getElementById(this.field + "-inputFile-"+this.id).click()
				if (item != null) {
					this.image_id = item.id;
				}
			},
			saveImage(event){
				if (event.target.files[0] == null) {
					return false;
				}
				this.active_load = true;
				let formData = new FormData();
					formData.append('id',  this.id);
					formData.append('image',event.target.files[0]);
					formData.append('with', this.with);
					formData.append('height', this.height);
					formData.append('folder', this.folder);
					formData.append('model', this.model);
					formData.append('field', this.field);
					formData.append('image_before', this.image_before);
					axios.post(`/admin/ajax/uploads-images`, formData)
					.then(res=> {
						if (res.data.status == true) {
							this.$toast.success(res.data.message)
							this.image = res.data.name_image;
							this.image_before = res.data.name_image;
							this.active_load = false;
						} else{
							this.active_load = false;
							this.$swal({
									icon: 'error',
									title: res.data.message,
									showCancelButton: true,
									cancelButtonText: 'Cancelar',
									confirmButtonText: 'Aceptar'
								});
						}                             
					})
					.catch(err => {
						console.log('error');
				});
        	document.getElementById(this.field + "-inputFile-" + this.id).value = "";
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
							this.active_load = true;
							let data={
							id: this.id,
							image: this.image,
							model: this.model,
							field: this.field,
							delrecord: this.delrecord,
							};
							axios.post('/admin/ajax/delete-image', data)
							.then(response => {
							if(response.data.status){
								this.$toast.info(response.data.message)
								this.image= null
								this.image_before= null
								this.active_load = false;
								if(this.delrecord){
									$(`#div-${this.folder}-${this.id}`).remove()								;
								}
							}
							}).catch(err => {
								console.log('Error al eleiminar');
							});
						}
					});	
			}
		}
	}
</script>
	
