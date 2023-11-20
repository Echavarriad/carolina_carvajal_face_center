<script>
	import { required, email } from 'vuelidate/lib/validators';
	import 'bootstrap-vue/dist/bootstrap-vue.css';
	export default{
		props : ['content', 'departments'],
		data(){
			return{
					address: [],
					cities: [],
					add_address: false,
					before_address: {},
					new_address:{
						address: null,
						name_address: null,
						complement: null,
						state_id: null,
						city_id: null,
						principal: 0
					}
				}
		},
		validations: {
			new_address: {
				state_id: {required},
				city_id: {required},
				name_address: {required},
				address: {required},
			}			
  		},	
		async mounted(){
            await this.getAddresesByUser();
			this.$root.changeStatusGif(false);	
		},
		methods:{
			async getAddresesByUser(){
				const resp= await axios.get('ajax/get-addresses-by-user');
				this.address = resp.data.addresses;
			},
			selectedDepartment(event){
				this.new_address.city_id = null;
				let state = event;
				axios.get(`ajax/cities/${state}`)
				.then(res => {
					this.cities =  res.data;
					return false;
				})      
			},
			selectedDepartmentEdit(event, item){
				item.cities = null;
				this.new_address.city_id = null;
				let state = event.target.value;
				axios.get(`ajax/cities/${state}`)
				.then(res => {
					item.cities =  res.data;
					return false;
				})
			},
			getStates(){
				this.address.city = null;
				let state	= event;
				var cities = {};
				axios.get(`ajax/states/${state}`)
				.then(res => {
					this.states =  res.data;
					return false;
				})        
			},
			async saveAddress(){
				this.$v.new_address.$touch();
				if (this.$v.new_address.$invalid) {
					return false;
				}
				this.$root.changeStatusGif(true);				
				this.add_address = false;
				const resp= await axios.post(`ajax/create-address`, this.new_address);
				if (resp.data.status == 'save') {
					this.resetData();
					await this.getAddresesByUser();
					this.$root.changeStatusGif(false);	
					this.executeSwal('success', resp.data.message);
				}else{	   
					this.$root.changeStatusGif(false);   			
					this.executeSwal('error', resp.data.message);
				}
			},
			async updateAddress(item){
				this.$v.new_address.$touch();
				if (this.$v.new_address.$invalid) {
					return false;
				}
				this.$root.changeStatusGif(true);
				const resp= await axios.post(`ajax/update-address`, this.new_address);
				if (resp.data.status == 'save') {				
					item.edit = false;
					this.resetData();					
					await this.getAddresesByUser();
					this.$root.changeStatusGif(false);	
					this.executeSwal('success', resp.data.message);
				}else{	      			
					this.executeSwal('error', resp.data.message);
				}
			},
			async deleteAddress(item){
				const respSwal=	await this.$swal({  
					icon: 'question',
					title: this.content.confirm_delete_address, 
					showCancelButton: true,
					confirmButtonText: this.content.acept,
					cancelButtonText: this.content.cancel,
				});
				if(respSwal.isConfirmed){
					this.$root.changeStatusGif(true);
					const resp= await axios.delete(`ajax/delete-address/${item.id}`);	      		
					if (resp.data.status) {
						await this.getAddresesByUser();
						this.$root.changeStatusGif(false);	
						this.executeSwal('success', resp.data.message);
					}else{	
						this.executeSwal('error', resp.data.message);   
					}
				}
				
			},
			edit(item){
				if(item.edit == '1'){
					item.edit = '0';
					this.resetData();
				}else{	
					this.add_address = false; 
					this.address.forEach(function(item){
						item.edit = 0;
					})				
					this.new_address.id = item.id;
					this.new_address.address = item.address;
					this.new_address.complement = item.complement;
					this.new_address.name_address = item.name_address;
					this.new_address.state_id = item.state_id;
					this.new_address.city_id = item.city_id;
					this.new_address.principal = 0;
					item.edit = '1';
				}
			},
			addAddress(){
				if(this.add_address){
					this.add_address = false;
				}else{
					this.address.forEach(function(item){
						item.edit = 0;
					})
					this.resetData();	
					this.add_address = true;
				}
			},
			resetData(){
				this.new_address.address = null;
				this.new_address.complement = null;
				this.new_address.name_address = null;
				this.new_address.state_id = null;
				this.new_address.city_id = null;
				this.new_address.principal = 0;
			},
			executeSwal(type, message){
				this.$swal({  
					icon: type,
					title: message, 
					showCancelButton: false,
					confirmButtonText: this.content.acept,
				});
			}
		}
	}
</script>

