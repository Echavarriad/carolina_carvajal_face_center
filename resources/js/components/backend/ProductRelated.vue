<template>
 	<div class="row">
 		<h2>Solo puede relacionar 4 productos</h2>
		<label v-if="count > 1" for="">Puede ordenar los productos arrastrándolos y ubicándolos en el lugar que desee</label><br>   
		<div class="container">
			<table id="example1" class="table table-bordered table-striped">
				 <tbody>
          <draggable v-model="relateds" group="options" @change="updateOrder">
            <tr v-for="(product, index) in relateds" :key="index">
              <td><a href="#" title="Arrastre para ordenar"><i class="fas fa-arrows-alt fa-2x"></i></a></td>
              <td><img :src="product.image_main" width="60"></td>
              <td>{{product.name}}</td>
              <td><button class="btn btn-danger not-padding" title="Eliminar" @click.prevent="deleteRelated(product)" style="margin:0 7px;"><i class="fa fa-trash"></i></button></td>
            </tr>
          </draggable>
        </tbody>
			</table>
		</div>   
		<div class="container">

		<div class="autocomplete" v-if="count < 4">
      <label>Producto</label>
    	<input type="text" v-model="product_search" @keyup="searchProduct" class="form-control" autocomplete="off" placeholder="Buscar...">
      <ul class="autocomplete-results" v-if="results.length">
        <li class="autocomplete-result" v-for="(result, index) in results" @click="addProduct(result)" :key="index">{{result.name}}</li>
      </ul>
    </div> 
		</div>        
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
          },
          count_relateds: Number
        },
        data(){
          return {
            product_search : '',
            relateds: [],
            results: [],
            base : window.base_url,
            count : 0
          }
        },
       async mounted() {
       		this.count = this.count_relateds;
          const resp= await axios.get(`/admin/ajax/get-relateds/${this.product.id}`);
          this.relateds = resp.data.data;

        },
        methods:{
            addProduct(item){
              axios.post( `/admin/ajax/add-related`,{product : this.product.id , related : item.id})
              .then(res => {
                  this.product_search = '';
                  if(res.data.status != undefined && !res.data.status){
                    this.$toast.error(res.data.message);
                    return;
                  }
                  
                  this.relateds.push(res.data.data);
                  this.count++;
                });
            },
            deleteRelated(item){
             this.$swal({
              icon: 'question',
              title: '¿Desea eliminar este registro?',
              showCancelButton: true,
              cancelButtonText: 'Cancelar',
              confirmButtonText: 'Aceptar'
              }).then((result) => {
                if(result.value){
                  axios.delete(`/admin/ajax/delete-related/${item.id}`)
                     .then(res => {
                        if(res.data.status == 1){
                            this.$toast.success("Registro eliminado correctamente")
                            this.relateds.splice(this.relateds.indexOf(item) , 1);
                            this.count--;
                        }
                     })
                     .catch(err => {
                 }); 
               }
              });   
            },
            searchProduct(){
              let data = {
                term : this.product_search,
                product : this.product.id
              };
              axios.post( `/admin/ajax/search-product`,data)
              .then(res => {
                      this.results = res.data;
                })
               .catch(function(){
               });
            },
            updateOrder(){
              axios.post(`admin/ajax/order-products-relateds`,this.relateds);
            }
        }
    }
</script>

<style>
    .autocomplete {
      position: relative;
      width: 250px;
    }
    .autocomplete-results {
      padding: 0;
      margin: 0;
      border: 1px solid #eeeeee;
      height: auto;
      overflow: auto;
    }
    .autocomplete-result {
    list-style: none;
      text-align: left;
      padding: 4px 4px;
      cursor: pointer;
      background: #3b3f5c;
      color: #fff;
      font-weight: 600;
      font-size: 15px;
    }
    .autocomplete-result:hover {
      background-color: #3d4041;
      color: white;
    }
</style>