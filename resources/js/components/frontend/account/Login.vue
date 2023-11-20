<script>
    import { required, email } from 'vuelidate/lib/validators';
	import 'bootstrap-vue/dist/bootstrap-vue.css';
    import getLogin from '../functions/get-login';
	export default{
		props : ['redirect_login', 'content'],
		data(){
			return{
                email: null,
                password : null
            }
		},
		validations: {
    		email: {required, email},
    		password: {required}
        },	
        mounted(){
            this.$root.changeStatusGif(false);
        },
		methods:{
			async getLogin(){            
                this.$v.$touch();
                if (this.$v.$invalid) {		      	
                    return false;
                }
                this.$root.changeStatusGif(true);
                const {status, message} = await getLogin(this.email, this.password, this.redirect_login);
                if(!status){
                    this.$root.changeStatusGif(false);
                    this.$swal({ 
                        icon: 'error',
                        title: message, 
                        showCancelButton: false,
                        confirmButtonText: this.content.acept,
                    });
                }
                
			},
			enterPassword() {
				if (event.which  === 13) {
					this.getLogin();
				}      
			}
		}
	}
</script>

