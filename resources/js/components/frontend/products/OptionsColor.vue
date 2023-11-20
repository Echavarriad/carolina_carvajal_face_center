<script>
	export default{
		props : ['data_product', 'content'],
		data(){
			return {
				show_sections : false,
				show_continue : true,
				product : {},
				color: '',
				id_color: '',
				quantity: '',
				sections_color:[],
				section_send : false,
				sections_finally : [],
				id : 3,
				sections: [
					{	
						id: 1,
						qty: '',
						meters: '',
					},
					{
						id: 2,
						qty: '',
						meters: '',
					},
					{
						id: 3,
						qty: '',
						meters: '',
					},
				]

			}
		},
		mounted(){
			this.product = this.data_product;
		},
		computed:{
		},
		methods : {
			selectColor(){
				this.$root.changeStatusGif(true);
				this.id_color = event.target.options[event.target.options.selectedIndex].dataset.id;
				this.color = event.target.value;
				let data ={
					product : this.product.Referencia,
					color: this.color
				};	
				axios.post('ajax/get-qty-color' , data)
				.then(res => {   				
					let self = this;
					setTimeout(function(){ 
					self.$root.changeStatusGif(false);  }, 800);	
					if(res.data.status == true){          		
						this.sections_color= res.data.records; 
					}else{ 
						this.$toast.error(res.data.message);
					}
				})
			},
			addSection(){
				this.sections.push({id : this.id++, qty : '', meters : ''});
				this.section_send = false;
			},
			updateQty(){
				if(Number.isNaN(this.quantity) || this.quantity < 1){
					this.quantity = 1;
				return false;
				}
				Number(this.quantity);
			},
			addCart(){
				if (this.id_color == '') {
					this.$swal(this.content.select_color);
					return false;
				}
				if (this.quantity == '') {
					this.$swal(this.content.enter_quantity_meters);
					return false;
				}
				if(this.show_sections){
					let qty_add = this.calculateSections()
					if(this.quantity != qty_add){
						let dif = this.quantity - qty_add;
						this.$swal(this.content.validate_meters + ' Falta agregar ' + dif + ' metros.');
						this.sections_finally = [];
						return false;
					}
				}else{
					this.sections_finally.push({qty: 1, meters: this.quantity})
				}
				let data ={
					product : this.product.Referencia,
					quantity: this.quantity,
					color: this.color == '' ? '-1': this.color,
					sections : this.sections_finally
				};			
				console.log(data)	
				this.sections_finally = [],
				// this.$nextTick(function () {
				// 	$('.modal_content').removeClass('__active');
				// 	$('html, body').removeClass('__no-scroll');
				// });
				this.$root.changeStatusGif(true);
				axios.post('ajax/add-to-cart' , data)
				.then(res => {   
					this.$root.changeStatusGif(false); 
					this.section_send = true; 					
					if(res.data.status == true){          		
						this.$root.incrementcart(Number(res.data.sections_exit.length)); 
						this.quantity= '',
						this.product.stock = res.data.quantity;
						this.sections = res.data.sections_exit;
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
						if(res.data.insert_exit){
							this.$nextTick(function () {
								$('.modal_content').removeClass('__active');
								$('html, body').removeClass('__no-scroll');
							});
						}                	  
					}else{
						this.$toast.error(res.data.message);						
						this.sections = res.data.sections_exit;
						if(res.data.insert_exit == true){
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
						}  
					}
				})
      		},
			calculateSections(){
				let total_meters = 0;
				let self = this;
				this.sections.forEach(function(item){
					if(item.qty > 0 && item.meters > 0){
						total_meters += item.qty*item.meters;
						self.sections_finally.push(item);
					}
				});
				return total_meters;
			},										
			selectType(type){
				if(type == 'continue'){
					if(!this.show_continue){
						this.show_continue = true;
						this.show_sections = false;
					}	
				}else{
					if(!this.show_sections){
						this.show_sections = true;
						this.show_continue = false;
					}	
				}
			}
		}
	}
</script>