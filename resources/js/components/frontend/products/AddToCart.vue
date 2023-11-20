<script>
	export default{
		props : ['data_product', 'content'],
		data(){
			return {
				quantity : 1,
				active_gif : false,
				product : {},
				cart : [],
				variation : {
					index: 0,
					id:null , 
					complete: false , 
					stock : 0,
					message : this.content.choose_all_option
				},
			}
		},
		mounted(){
			this.product = this.data_product;
			this.$root.changeStatusGif(false);
		},
		methods : {
			activeGif(bool){
				this.$root.changeStatusGif(bool);
			},
			selectedVariation(data){
				this.variation = data;
				if(this.variation.id == null){
					this.showAddCart = false;
				}else{					
					this.$refs.gallery.goToImageFromVariation(this.variation.index)
					this.showAddCart = true;
				}
			},
			addQty(qty){
				if(this.quantity + qty < 1){
					return false;
				}
				this.quantity = Number(this.quantity) + Number(qty);
			},
			updateItem(){
				if(Number.isNaN(this.quantity) || this.quantity < 1){
					this.quantity = 1;
				return false;
				}
				Number(this.quantity);
			},
			addToCart(){				
				if (this.product.type_product == 'simple' && this.quantity > (this.product.stock - this.product.reserved_stock)) {
					this.$swal(this.content.not_stock);
					return false;
				}
				let data ={
					product : this.product.id,
					quantity: this.quantity
				};
				if(this.product.type_product == 'configurable'){					
					if(!this.variation.complete){
						this.$swal(this.variation.message);
						return false;
					}else if(this.quantity > this.variation.stock){
					this.$swal(this.content.not_stock);
					return false;
					}				 	
					data.variation = this.variation.id;
				}				 	
				this.activeGif(true);
				axios.post('ajax/add-to-cart' , data)
				.then(res => {
					this.activeGif(false);
					if(res.data.status == true){
						this.$root.incrementcart(Number(this.quantity));
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