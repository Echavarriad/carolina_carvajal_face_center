<script>
	import {required, email,} from 'vuelidate/lib/validators';
	// import 'bootstrap/dist/css/bootstrap.css';
	import 'bootstrap-vue/dist/bootstrap-vue.css'; 
	export default{
		props : ['data_user', 'content', 'documents'],
		data(){
			return{				
				base : window.base_url,
				user: {},
				email: null
			}
		},
		mounted(){		
			this.user= this.data_user;
			this.email= this.user.email;
			this.$root.changeStatusGif(false);	
		},
		validations: {
			user : {
				name: {required},
				lastname: {required},
				email: {required, email},
				mobile: {required},
			}
  		},
		methods:{
			updateAccount(){				
			 	this.$v.user.$touch();
				if (this.$v.user.$invalid) {	      	
					return false;
				}
				this.$root.changeStatusGif(true);
				axios.post(`ajax/update-account`, this.user).then(response => {
					this.$root.changeStatusGif(false);	      		
					if (response.data.status == 'update') {
						this.email = this.user.email;
						this.$swal({  
							icon: 'success',
							title: response.data.message, 
							confirmButtonText: this.content.acept,
						});
					}else{	      
						this.user.email = this.email;			
						this.$toast.error(response.data.message);
					}

				});
			},
		}
	}
</script>


