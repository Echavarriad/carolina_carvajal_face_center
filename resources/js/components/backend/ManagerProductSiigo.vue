
<script>
	export default{
		props:['data_product'],
		data(){
			return {
        pruduct: Object,
        stock: 0
			}
		},
		created(){			
			this.product= this.data_product;
		},
		methods : {
			createProductInSiigo(){
				this.$swal({
          icon: 'question',
          title: '¿Está seguro de enviar el producto a SIIGO?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
              if(result.value){ 
                $('.loader').fadeIn();
                axios.post(`admin/ajax/create-product-in-siigo`, {product_id: this.product.id})
                  .then(response => {                    
                    $('.loader').fadeOut();
                      if(response.data.status){
                        this.excuteSwalSuccess(response.data.message);
                      }else{
                        this.$toast.error(response.data.message);
                      }
                    });
              }
        });        
			},
      updateProductInSiigo(){
				this.$swal({
          icon: 'question',
          title: '¿Está seguro de actualizar el producto en SIIGO?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
              if(result.value){ 
                $('.loader').fadeIn();
                axios.put(`admin/ajax/update-product-in-siigo`, {product_id: this.product.id})
                  .then(response => {                    
                    $('.loader').fadeOut();
                      if(response.data.status){
                        this.excuteSwalSuccess(response.data.message, false);
                      }else{
                        this.$toast.error(response.data.message);
                      }
                    });
              }
        });        
			},
      deleteProductInSiigo(){
				this.$swal({
          icon: 'question',
          title: '¿Está seguro de eliminar el producto en SIIGO?',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Aceptar'
          }).then((result) => {
              if(result.value){ 
                $('.loader').fadeIn();
                axios.delete(`admin/ajax/delete-product-in-siigo/${this.product.id}`)
                  .then(response => {                    
                    $('.loader').fadeOut();
                      if(response.data.status){
                        this.excuteSwalSuccess(response.data.message);
                      }else{
                        this.$toast.error(response.data.message);
                      }
                });
              }
        });        
			},
      async updateStockInSiigo(){
        // let stock= 0;
        const resp= await this.$swal({  
						icon: 'info',
						html: `<p>Se enviará a SIIGO el stock que se encuentran en el producto o la suma del stock de las variaciones.</p><p>Si necesita enviar otra cantidad a SIIGO por favor ingrésela en el siguiente campo.</p>`, 
						input: "number",
						showCancelButton: true,
						confirmButtonText: 'Enviar stock',
						cancelButtonText: 'Cancelar',
						inputPlaceholder: 'Ingrese Cantidad',
						inputValidator: stock => {
              if(stock > 0){
                this.stock= stock;
              }
						}
					});
          if(resp.isConfirmed){
						$('.loader').fadeIn();
              axios.post(`admin/ajax/update-quantity-in-siigo`, {stock: this.stock, product_id: this.product.id})
                .then(response => {                    
                  $('.loader').fadeOut();
                    if(response.data.status){
                      this.excuteSwalSuccess(response.data.message);
                    }else{
                      this.$toast.error(response.data.message);
                    }
            });
					}
          

      },
      excuteSwalSuccess(message, reload= true){
        this.$swal({  
            icon: 'success',
            title: message, 
            confirmButtonText: 'OK',
        }).then((result) => {
            if(result.value){
              location.reload();
            }
        });
      }
    }
	}
</script>

	
