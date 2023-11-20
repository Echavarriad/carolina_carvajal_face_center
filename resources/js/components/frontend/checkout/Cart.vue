<script>
  import getCart from '../functions/get-cart';
	export default{
		props : ['data_cart', 'content'],
		data(){
			return {
				base : window.base_url,
				active_gif : true,
				cart : [],
				has_items : true,
        coupon : null
			}
		},
		mounted(){   
      this.cart = this.data_cart;  
      if (this.cart == null) {
        this.has_items = false;
      } 
      this.$root.changeStatusGif(false);
		},
		methods:{
      async loadCart(){
        const respGetCart= await getCart();
        this.cart= respGetCart.cartData;
        if (this.cart == '') {
          this.has_items = false;              
          this.$root.updatecart(0);
          this.$root.changeStatusGif(false); 
          return false;
        }
        this.$root.updatecart(this.cart.count);
        this.$root.changeStatusGif(false); 

      },
			deleteItem(item){
        this.$root.changeStatusGif(true);
				let id = item.id;
        axios.delete(`/ajax/remove-item/${id}`)
          .then(response => {
            if(response.data.status == 'remove'){
              this.$toast.success(response.data.message);
              this.loadCart();                
              }
        })

			},
			addQuantity(item , qty){
        var sum_qty= Number(item.quantity) + Number(qty);
        if(sum_qty < 1){
          return false;
        }
        this.$root.changeStatusGif(true);
        this.updateQuantity(item , (Number(item.quantity) + Number(qty)));
      },
      updateItem(item){
        this.$root.changeStatusGif(true);
        if(Number.isNaN(item.quantity) || item.quantity < 1){
          this.loadCart();
          return false;
        }
          this.updateQuantity(item , item.quantity);
      },
      async updateQuantity(item, qty){
	      	let data = {
            id : item.id,
            quantity : qty,
            variation : item.variation_id,
          };
          const resp= await axios.post('ajax/update-item-cart' , data);
          if (resp.data.status) { 
              this.loadCart();
          }else{
            await this.loadCart();
            this.$swal(resp.data.message);
          }
      },
      goToProduct(url){
        location.href = url;
      },
      async removeCoupon(){
				this.$root.changeStatusGif(true);
				const resp= await axios.post('ajax/remove-coupon');
				if (resp.data.status) {              
            this.loadCart();
            this.$swal(res.data.message);
					}
			}  
		}
	}
</script>