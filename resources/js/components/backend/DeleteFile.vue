<template>
	<div>			 
		<div class="row delete-file" v-if="file != null">
			<a :href="`${base}/uploads/${file}`" target="_blank">Archivo Actual</a>
            <button type="button" class="btn btn-danger delete-file" title="Eliminar archivo" @click="deleteFile()"><img :src="`${base}/mng/img/trash-white.svg`" alt=""></button>
            <div v-if="active_load" class="gif-load">
				<img :src="`${base}/mng/img/load.gif`"  alt="">
            </div>
		</div> 
	</div>
</template>
<script>
	export default{
		props:['file', 'id', 'folder', 'model', 'field'],
		data(){
			return {
				base : window.base_url,
				active_load : false,
			}
		},
		created(){
		},
		methods : {
			deleteFile(){
				this.$swal({
					icon: 'question',
					title: '¿Desea eliminar el archivo?',
					showCancelButton: true,
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Aceptar'
					}).then((result) => {
						if(result.value){ 
							this.active_load = true;
							let data={
                                id: this.id,
                                file: this.file,
                                model: this.model,
                                field: this.field,
                                folder: this.folder,
							};
							axios.post('/admin/ajax/delete-file', data)
							.then(response => {
							if(response.data.status){
								this.$toast.info(response.data.message)
								this.file= null
								this.active_load = false;
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
	
