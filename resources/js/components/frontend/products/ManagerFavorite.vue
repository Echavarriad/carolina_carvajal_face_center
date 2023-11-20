<script>
	export default{
		props : ['product_id', 'is_in_favorite'],
		data(){
			return {
				add_favorite: true
			}
		},
		mounted(){
			this.add_favorite= this.is_in_favorite ? false : true;
		},
		methods : {
			addProductToFavorite(){
				this.add_favorite = false; 
				axios.post('ajax/add-product-to-favorite' ,{ product_id: this.product_id })
				.then(resp => {
					if(resp.data.status == true){ 
						this.$toast.success(resp.data.message);
						this.$root.incrementfavorite(1);
					}
				})
			},
			removeProductOfFavorite(){
				this.add_favorite = true; 
				axios.post('ajax/remove-product-of-favorite' , { product_id: this.product_id })
				.then(resp => {
					if(resp.data.status == true){ 
						this.$toast.info(resp.data.message);
						this.$root.incrementfavorite(-1);
					}
				});
			}
		}
	}
</script>