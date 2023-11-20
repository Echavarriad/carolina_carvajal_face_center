<template>
	<div class="col-lg-12 col-sm-12 vue-variations">
		 <label for="name">Leer <span class="cursor info"><img :src="`${base}/mng/img/info-blue.svg`"  alt=""><p>Recuerde guardar los cambios en los botones de la parte inferior de esta sección, si no guarda y refresca la página, deberá hacer todo desde la última vez que haya guardado.</p></span> </label>
		<div class="n-chk" v-if="attributes.length > 0">
      <label class="new-control new-checkbox checkbox-primary">
        <input type="checkbox" class="new-control-input" @click="changeTypeProduct" :checked="type_product == 'configurable'">
        <span class="new-control-indicator"></span>Producto con variaciones
      </label>
  	</div>
  	<hr>
  	<div v-if="type_product == 'simple'">
  		<div class="row">
        <div class="form-group col-sm-12 col-lg-3">
          <label for="name">Precio <span>*</span> <span class="cursor info"><img :src="`${base}/mng/img/info-blue.svg`"  alt=""><p>Si no desea agregar el precio, el producto se mostrará en la tienda sin Botón de agregar al carrito.</p></span></label>
          <input type="number" class="form-control"  v-model="product.price">
        </div>
        <div class="form-group col-sm-12 col-lg-3">
          <label for="name">Precio especial</label>
          <input type="number" class="form-control" v-model="product.price_special">
        </div>
        <div class="form-group col-sm-12 col-lg-2">
          <label for="name">Total stock <span>*</span></label>
          <input type="number" class="form-control" v-model="product.stock">
        </div>
        <div class="form-group col-sm-12 col-lg-2">
          <label for="name">En reserva</label>
          <input type="number" class="form-control" :value="product.reserved_stock" readonly>
        </div>
         <div class="form-group col-sm-12 col-lg-2">
          <label for="name">Stock disponible</label>
          <input type="number" class="form-control" :value="Number(product.stock) - Number(product.reserved_stock)" readonly>
        </div>
      </div> 
     	<button class="btn btn-primary"  title="Guardar" @click.prevent="savePriceProductSimple"><img :src="`${base}/mng/img/save.svg`" alt=""></button>
  	</div>
  	<section v-else>
        <label for="">Atributos disponibles <span class="cursor info"><img :src="`${base}/mng/img/info-blue.svg`"  alt=""><p>Seleccione los atributos con los cuales desea hacer las variaciones, después haga clic en el botón "Actualizar variaciones", Recuerde que al realizar la acción deberá ingresar nuevamente todos los datos de cada variación.</p></span></label>
        <div class="n-chk" v-for="attr in attributes" :key="attr.id">
          <label class="new-control new-checkbox checkbox-primary" style="width: 100%;">
            <input type="checkbox" class="new-control-input" @click="checkAttr(attr, $event)" :checked="attr.has_variation == true">
            <span class="new-control-indicator"></span>{{ attr.name }}
            <button class="btn btn-dark btn-short"  title="Seleccionar opciones" @click.prevent="showOptions(attr)"><img :src="`${base}/mng/img/list.svg`" alt=""></button>
          </label>
        </div>
        <button class="btn btn-primary"  title="Agregar atributos" @click.prevent="updateVariations"><img :src="`${base}/mng/img/refresh.svg`" alt=""> Actualizar variaciones</button> 
      <div v-if="product.variations.length > 0" class="inputs-all-variations">
        <hr>
        <label for="">Aplicar a todas las variaciones <span class="cursor info"><img :src="`${base}/mng/img/info-blue.svg`"  alt=""><p>Para actualizar todas las variaciones Ingrese todos los campos y de clic en Aplicar a todo.</p></span></label> 
        <div class="row">
            <div class="form-group col-sm-12 col-lg-4">
              <label for="name">Precio </label>
              <input type="number" class="form-control"  v-model="all_variations.price">
            </div>
            <div class="form-group col-sm-12 col-lg-3">
              <label for="name">Precio especial</label>
              <input type="number" class="form-control" v-model="all_variations.price_special">
            </div>
            <div class="form-group col-sm-12 col-lg-4">
              <label for="name">Stock <span>*</span></label>
              <input type="number" class="form-control" v-model="all_variations.stock">
            </div>
            <div class="form-group col-sm-12 col-lg-4">
              <button class="btn btn-primary"  title="Guardar" @click.prevent="updateAllVariations"><img :src="`${base}/mng/img/save.svg`"> Aplicar a todo</button>
            </div>
        </div>       
      </div>
      <hr>
      <div class="row">	      	
        <div class="col-lg-12 col-sm-12 add-variation">
          <label for="">Variaciones <span class="cursor info"><img :src="`${base}/mng/img/info-blue.svg`"  alt=""><p>Estas son las variaciones que tiene actualmente, puede editarlas, eliminarlas y agregar nuevas variaciones según los atributos y las opciones que estén seleccionados.</p></span></label>
          <button class="btn btn-primary mb-3 mr-3 add" @click.prevent="show_variations = true"><img :src="`${base}/mng/img/plus.svg`" alt=""> Agregar variación</button>
        </div>
      </div>
			<div class="table-responsive variations">
        <table  class="table dt-table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
            <thead>
              <tr role="row">
                <th class="w-10">Variación</th>
                <th>Imagen (517 x 709 px)</th>
                <th class="w-10">Precio</th>
                <th class="w-10">Precio especial</th>
                <th class="w-10">Stock</th>
                <th class="w-10">En reserva</th>
                <th class="w-10">Stock disponible</th>
                <th class="w-15">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr role="row" class="odd" v-for="(variation, index) in product.variations" v-if="variation.is_hidden == '0'" :key="index">
                <td> 
                    <label class="new-control new-checkbox checkbox-primary">
                      <input type="checkbox" class="new-control-input" @click="checkDeleteVariation(variation, $event)">
                      <span class="new-control-indicator"></span>{{ variation.variation }}
                    </label>
                </td>
                <td>
                  <upload-images :img="variation.image" :id="variation.id" with="517" height="709" folder="variation" model='Variation' field='image' :del="true" :delrecord="false" :key="uploadsImageKey"></upload-images>
                </td>
                <td>
                    <input v-if="variation.update == '1'" type="number" class="form-control" v-model="variation.price">
                    <label v-else>{{ variation.price }}</label>
                </td>
                <td>
                    <input v-if="variation.update == '1'" type="number" class="form-control" v-model="variation.price_special">
                    <label v-else>{{ variation.price_special }}</label>
                </td>
                <td> 
                    <input v-if="variation.update == '1'" type="number" class="form-control" v-model="variation.stock">
                    <label v-else>{{ variation.stock }}</label>
                </td>
                <td> 
                    <label>{{ variation.reserved_stock }}</label>
                </td>
                <td> 
                    <label>{{ Number(variation.stock) - Number(variation.reserved_stock) }}</label>
                </td>
                <td>
                    <!-- <button v-if="variation.update == '0'" class="btn btn-primary" title="Editar" @click.prevent="variation.update = true"><img :src="`${base}/mng/img/edit-white.svg`" alt=""></button>
                    <button v-if="variation.update == '1'" class="btn btn-primary" title="Guardar" @click.prevent="updateVariation(variation)"><img :src="`${base}/mng/img/save.svg`" alt=""></button> -->
                  <button class="btn btn-danger" title="Eliminar" @click.prevent="deleteVariation(variation)"><img :src="`${base}/mng/img/trash-white.svg`" alt=""></button>
                </td>
              </tr>        
            </tbody>
        </table>
        <button class="btn btn-danger" title="Eliminar selección" @click.prevent="deleteAllSelectedVariation" :disabled="array_del_variations.length == 0" style="padding: 0.4375rem 1.25rem;"><img :src="`${base}/mng/img/trash-white.svg`" alt="">Eliminar las variaciones seleccionadas</button>
  	  </div>
    </section>

  <!--Modal para seleccionar las variaciones borradas -->
  <div class="modal-category" v-if="show_variations">      
    <div class="content-modal">     
      <div class="col-md-12 square">
        <h3>Seleccionar Variaciones</h3>
        <div class="row content-gral">
          <div class="col-lg-12">
          <div class="attributes">
            <div class="n-chk"  v-for="item in product.variations" :key="item.id">
                <label v-if="item.is_hidden == '1'" class="new-control new-checkbox checkbox-primary">
                  <input type="checkbox" class="new-control-input" @click="checkAddVariation(item, $event)">
                  <span class="new-control-indicator"></span>{{ item.variation }}
                </label>
            </div>
          </div>
          </div>
        </div>        
      <div class="col-md-2 col-sm-12"> 
        <div class="buttons-inline">
          <button class="btn btn-dark" v-on:click="show_variations = false"><img :src="`${base}/mng/img/cancel.svg`" alt=""> Cancelar</button>
          <button type="submit" class="btn btn-primary" v-on:click.prevent="addVariations" :disabled="array_add_variations.length == 0"><img :src="`${base}/mng/img/plus.svg`" alt=""> Agregar</button>
        </div>
      </div>
      </div>
    </div> 
  </div>
  <!-- fin modal -->

  <!--Modal para seleccionar las opciones de cada atributo -->
  <div class="modal-category" v-if="show_options">      
    <div class="content-modal">     
      <div class="col-md-12 square">
        <h3>Seleccione las opciones que va a utilizar en la variación</h3>
        <div class="row content-gral">
          <div class="attributes">
            <div class="n-chk" v-for="option in current_attr.options" :key="option.id">
                <label class="new-control new-checkbox checkbox-primary">
                  <input type="checkbox" class="new-control-input" @click="checkAddOptions(option, $event)" :checked="option.add_option == '1'">
                  <span class="new-control-indicator"></span>{{ option.name }}
                </label>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-12"> 
          <div class="buttons-inline">
            <button class="btn btn-dark" v-on:click="show_options = false"><img :src="`${base}/mng/img/cancel.svg`" alt=""> Cerrar</button>
          </div>
        </div>
      </div>
      
    </div> 
  </div>
  <!-- fin modal -->
	</div>
</template>
<script>
import draggable from 'vuedraggable'
	export default{
		components: {
            draggable,
    },
		props:['data_product'],
		data(){
			return {
				base : window.base_url,
        show_variations : false,
				show_options : false,
        attributes : [],
				current_attr : [],
				product : {},
				type_product : null,
				array_attributes : [],
				array_add_variations : [],
				array_del_variations : [],
        count_attributes_checked : 0,
        all_variations : {
          price : null,
          price_special : null,
          stock : 1
        },
        uploadsImageKey: 0
			}
		},
		created(){	
			this.product = this.data_product;
      this.getSettingsVariations();			
			this.type_product = this.product.type_product;
			
		},
		methods : {
      async getSettingsVariations(){
        const resp= await axios.post('admin/ajax/get-settings-variations', {product_id: this.product.id});
        this.attributes = resp.data.attributes;
			  this.count_attributes_checked = resp.data.countAttributesChecked;
        if (this.attributes.length == 0) {
          this.product.type_product = 'simple';
          this.type_product = 'simple';
        }
      },
			changeTypeProduct(event){
        this.type_product = event.target.checked ? 'configurable' : 'simple';        
			},
			checkAttr(item, event){
				if (event.target.checked) {   
          this.count_attributes_checked++;    		
          this.current_attr = item;
          this.show_options = true;
	      }else{
          this.count_attributes_checked--;
	      }
        item.has_variation = event.target.checked ? 1 : 0;
			},
      showOptions(item){
        if (item.has_variation == 1) {
          this.current_attr = item;
          this.show_options = true;
        }else{
          this.$toast.error('Para ver las opciones, el atributo debe estar seleccionado');
        }
      },
      checkAddOptions(item, event){
        let add_option = event.target.checked ? 1 : 0;
        item.add_option = add_option;
        let self = this;
        this.attributes.forEach(function(attr){
          if(attr.id == self.current_attr.id){
            attr.options = self.current_attr.options;
            return false;
          }
        });
      },
			checkAddVariation(item, event){
				if (event.target.checked) {       		
      		this.array_add_variations.push(item);
	      }else{
 					this.array_add_variations.splice(this.array_add_variations.indexOf(item) , 1);
	      }
			},
			checkDeleteVariation(item, event){
				if (event.target.checked) {       		
      		this.array_del_variations.push(item);
	      }else{
 					this.array_del_variations.splice(this.array_del_variations.indexOf(item) , 1);
	      }
			},
			deleteAllSelectedVariation(){
				this.$swal({
          icon: 'question',
          title: '¿Desea eliminar las variaciones?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
              if(result.value){ 
              	let data = {
    							data : this.array_del_variations,
              	 	action : 'delete-all',
                  product_id : this.product.id
              	}
               	axios.post( `admin/ajax/update-variation`, data)
				          .then(response => {
		                  if(response.data.status){
            						this.array_del_variations.forEach(function(item){
				      						item.is_hidden = 1;
				      					});
		                    this.$toast.success(response.data.message);
		                    this.array_del_variations = [];
		                  }
				            })
			           .catch(function(){
			           });
              }
         });        
			},
			updateVariations(){
        if (this.product.variations.length == 0) {
          let data = {
            data : this.attributes,
            product_id : this.product.id
          }
          this.deleteCreateVariations(data);
        }else{
  				  this.$swal({
            icon: 'question',
            title: '¿Está seguro de actualizar?, al realizar la acción se borrarán todas las variaciones actuales.',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Aceptar'
            }).then((result) => {
                if(result.value){ 
                  let data = {
                      data : this.attributes,
                      product_id : this.product.id
                  }
                  this.deleteCreateVariations(data);
                }
           });            
        }
			},
      async deleteCreateVariations(data){
        //Se borran las variaciones actuales y se crean unas nuevas
        const resp= await axios.post( `admin/ajax/delete-create-variations`, data);
        if(resp.data.status){
          this.attributes = resp.data.attributes;
          this.product = resp.data.product;
          this.$toast.success(resp.data.message);
          this.uploadsImageKey++;
        }
      },
			async addVariations(){
				let data = {
    			data : this.array_add_variations,
          action : 'add',
          product_id : this.product.id
      	}
				const resp= await axios.post( `admin/ajax/update-variation`, data);
        if(resp.data.status){
          this.array_add_variations.forEach(function(item){
            item.is_hidden = 0;
          });
          this.$toast.success(resp.data.message);
          this.array_add_variations = [];
          this.show_variations = false;
        }
			},
      async updateVariation(variation){
        if (variation.price == null || variation.price_special == null || variation.stock == null) {
          this.$toast.error('Los campos precio, precio especial y stock no pueden estar vacíos');
          return false;
        }else if(variation.price < 0 || variation.price_special < 0 || variation.stock < 0){
          this.$toast.error('Solo se aceptan números iguales o  mayores a 0');
          return false;
        }
        let data = {
          data : variation,
          action : 'update',
          product_id : this.product.id
        }
        const resp= await axios.post( `admin/ajax/update-variation`, data)
        if(resp.data.status){
          variation.update = false;
          this.$toast.success(resp.data.message);
        }
      },
			async savePriceProductSimple(){
				if(this.product.price == null || this.product.price == '' ||
          this.product.stock == null || this.product.stock == ''){
					this.$toast.error('El precio y el stock son requeridos.');
          return;
        }
        let data = {
          id : this.product.id,
          price : this.product.price,
          price_special : this.product.price_special,
          stock : this.product.stock,
          type_product : this.type_product,
        }
        const resp= await axios.post( `admin/ajax/save-product-simple`,data);
        if(resp.data.status){
            this.$toast.success(resp.data.message);
            this.product= resp.data.product;
        }
			},
			saveAllOptions(){
          axios.post( `admin/ajax/create-options`,this.options)
          .then(response => {
                  if(response.data.status){
                    this.$toast.success(response.data.message);
                  }
            })
           .catch(function(){
           });
			},
			deleteVariation(item){
				this.$swal({
          icon: 'question',
          title: '¿Desea eliminar la variación?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
              if(result.value){ 
              	let data = {
            			data : {
	              		values : item.values, is_hidden : 1
	              	},
            	    action : 'delete',
                  product_id : this.product.id
              	}
               	axios.post( `admin/ajax/update-variation`, data)
				          .then(response => {
		                  if(response.data.status){
              					item.is_hidden = true;
		                    this.$toast.success(response.data.message);
		                  }
				            });
              }
         });        
			},
      async updateAllVariations(){
        let data = {
            data : this.all_variations,
            action : 'update-all',
            product_id : this.product.id
        }
        const resp= await axios.post( `admin/ajax/update-variation`, data);
        if(resp.data.status){
          this.product = resp.data.product;
          this.$toast.success(resp.data.message);
          this.all_variations = {
            price : null,
            price_special : null,
            stock : 1
          };
        }
      },
			updateOrder(){
				axios.post(`admin/ajax/update-variations-position`,this.options);
			}
		}
	}
</script>

	<style>
    .inputs-all-variations button.btn.btn-primary {
      margin-left: -1px !important;
    }

    .add-variation button.btn.btn-primary {
      margin-left: 40px !important;
    }
</style>
