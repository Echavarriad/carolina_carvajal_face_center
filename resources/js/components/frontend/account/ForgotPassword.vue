<script>
	import { required, email} from 'vuelidate/lib/validators';
	import 'bootstrap-vue/dist/bootstrap-vue.css'; 
	export default{
		props : ['url_login', 'content'],
		data(){
			return{
                email: null
            }
		},
		validations: {
    		email: {required, email}
  		},		
		mounted(){
			this.$root.changeStatusGif(false);	
		},
		methods:{
			async forgotPassword(){
			 	this.$v.$touch()
				if (this.$v.$invalid) {	      	
					return false;
				}
				this.$root.changeStatusGif(true);	
				const resp= await axios.post(`ajax/forgot-password`, {email: this.email});
				this.$root.changeStatusGif(false);
				if (resp.data.status == 'send') {
						this.email= null;
						this.$swal({  
							icon: 'success',
							title: this.content.forgot_password_success,
							html: resp.data.message, 
							confirmButtonText: this.content.acept,
							}).then((result) => {
						if(result.value){
							location.href = this.url_login;
						}
					});
				}else{
					this.$toast.error(resp.data.message);
				}
				
			}
		}
	}
</script>