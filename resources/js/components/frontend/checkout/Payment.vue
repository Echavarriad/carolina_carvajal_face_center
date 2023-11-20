<script>
	export default{
		props : ['data_cart','auth', 'content', 'gateways', 'url_checkout'],
		data(){
			return {
				cart: {},
				fields_payment : [],
				url_wompi : null,
				gateway: {}
		  	}
		},
		async mounted(){ 
			this.gateway = this.gateways[0] ;
			this.cart= this.data_cart;
			if(this.cart.shipping_rate == null){
				const resp= await this.$swal({  
						icon: 'error',
						html: `<p>No se ha hecho el cálculo del envío, será redirigido al checkout.</p>`, 
						showCancelButton: false,
						confirmButtonText: 'Aceptar',
					});
					if(resp.isConfirmed){
						window.location.href= this.url_checkout;
					}
			}
			this.$root.changeStatusGif(false);
		},
		methods:{ 
			loadCart(){
				axios.post('ajax/get-cart').then(function (res) {
					this.cart = res.data;
					this.step = 2;             
						this.$root.changeStatusGif(false);  
				}.bind(this))
				.catch(function(error){
					console.log('Erro loading cart');
				});
			},   
			finalizePurchase(){
				this.$root.changeStatusGif(true);
				axios.post('ajax/finalize-purchase', this.gateway)
				.then(res => {
					if(res.data.status){
						this.url_wompi = res.data.payment.url_pay;
						this.fields_payment = res.data.payment.fields;
						let self = this;
						setTimeout(function(){ self.$refs.form_checkout.submit(); }, 1500);
					}else{
					
					}
				});
			},
			removeCoupon(){
				this.$root.changeStatusGif(true);
				axios.post('ajax/remove-coupon')
				.then(res => {
					if (res.data.status) {              
					this.loadCart();
					this.$swal(res.data.message);
					}
				}).catch(error => {

				});
			}
		}
	}
</script>