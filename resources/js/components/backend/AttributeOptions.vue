<template>
	<div class="row">
		<label for="">Puede ordenar las opciones arrastrándolas, si agreagó una opción refresque la página antes de ordenar.</label><br>		
		<div class="container">
		<a class="btn btn-primary" @click.prevent="addOption"><i class="fa fa-plus"></i> Agregar</a>
		<draggable v-model="options" group="options" @change="updateOrder">
			<div class="row items-options" v-for="(item, index) in options" :key="index">
				<div class="item">				
				<div class="form-group">
					<label for="name">Opción</label>
					<div class="inputs" v-if="attribute.type == 'color'">
						<input type="text" class="form-control" v-model="item.name">
						<input type="color" class="form-control" v-model="item.color">
					</div>
					<input type="text" class="form-control" v-else v-model="item.name">
				</div> 
				<div class="form-group btns">
					<br>
					<button type="submit" class="btn btn-success" @click="saveOption(item)" title="Guardar" :disabled="item._save && item.id == null"><i class="fa fa-save"></i></button>

					<button class="btn btn-danger" @click="deleteOption(item)" title="Eliminar"  :disabled="item._save && item.id == null"><i class="fa fa-trash"></i></button>
					<button class="btn btn-dark" title="Arrastre para ordenar"><i class="fas fa-arrows-alt fa-2x"></i></button>
				</div>
			</div>
			</div>
			</draggable>
		</div>
	</div>
</template>
<script>
import draggable from 'vuedraggable'
	export default{
		components: {
            draggable,
    },
		props:{ attribute:{type:Object, required:true}},
		data(){
			return {
				base : window.base_url,
				options : [],
				btn_add_all : false
			}
		},
		mounted(){
			this.options = this.attribute.options;
		},
		methods : {
			addOption(){
				this.btn_add_all = true;
				this.options.push({id : null, name : '', color : null, attribute_id : this.attribute.id, order : 0, _save : false});
			},
			saveOption(item){
				if(item.name == '' || item.name == null){
					this.$toast.error('Debe ingresar un valor');
					return;
				}
				axios.post( `admin/ajax/master-option`, item )
					.then(response => {
						if(response.data.status){
							item._save = true;
							this.$toast.success(response.data.message);
						}
				});
			},
			saveAllOptions(){
				let data = {
					data : this.options,
					action : 'save-all'
				};
				axios.post( `admin/ajax/master-option`,data)
				.then(response => {
					if(response.data.status){
						this.$toast.success(response.data.message);
					}
				});
			},
			deleteOption(item){
				if(!item.id){
					this.options.splice(this.options.indexOf(item) , 1);
				}else{
					this.$swal({
					icon: 'question',
					title: '¿Desea eliminar la opción?',
					showCancelButton: true,
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Aceptar'
					}).then((result) => {
						if(result.value){
									axios.delete(`/admin/ajax/delete-option/${item.id}`)
									.then(response => {
										if(response.data.status){
											this.$toast.success(response.data.message)
										this.options.splice(this.options.indexOf(item) , 1);
										}
									})
									.catch(err => {
										console.log('Error al eleiminar');
								});
								}
					}); 
				}       
			},
			updateOrder(){
				axios.post(`admin/ajax/order-options`,this.options).then(response => {
					
				});
			}
		}
	}
</script>
<style>
	.inputs{
		display: flex;
		width: 100%;
	}

	.inputs input:first-child {
    	width: 160px;
	}
	.inputs input:last-child {
    	width: 50px;
	}
</style>
