<script>
	export default{
		props : [],
		data(){
            return {
            
            }
		},
		mounted(){
			
        //   let link = document.createElement("a");
        //     link.href = 'http://181.143.90.42:8099/UniFi_Controller_V5_UG.pdf';
        //     link.setAttribute("download", 'Nombre de la ficha técnica');
        //     document.body.appendChild(link);
            
        //     link.click();
		},
		methods : {
			addCart(){		 	
                this.$root.changeStatusGif(true);
                axios.post('ajax/add-to-cart' , data)
                .then(res => {   
                this.$root.changeStatusGif(false);       	
                if(res.data.status == true){          		
                    this.$root.incrementcart(this.quantity);
                    this.product.stock = res.data.quantity;
                    this.$swal({
                        html: res.data.message,
                        showCancelButton: true,
                        cancelButtonText: this.content.continue_buying,
                        confirmButtonText: this.content.finalize_purchase,
                        reverseButtons: true
                        }).then((result) => {
                            if (result.value) {
                            location.href = res.data.url;
                            }
                        })                	  
                }else{
                    this.$swal(res.data.message);
                }
                })

            }
			
		}
	}
</script>