<script>
	import { required, minLength, sameAs, helpers, email, numeric, alphaNum} from 'vuelidate/lib/validators';
	import 'bootstrap-vue/dist/bootstrap-vue.css';
	import getCart from '../functions/get-cart';
	import getLogin from '../functions/get-login';
	const isValidPassword = helpers.regex('alphaNum', /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]/) 
	export default{
		props : ['data_cart', 'url_cart', 'departments', 'auth', 'user', 'data_address', 'url_checkout', 'url_payment', 'documents', 'content', 'url_forgot_password'],
		data(){
			return {
				base : window.base_url,
				cart : [], 
				coupon : null,
				name : null,
				lastname : null,
				type_document : null,
				document : null,
				email: null,
				mobile: null,
				state : null,
				city : null,
				address : null,
				complement : null,				
				newsletter : false,
				create_account : false,
				cities : {},
				login : {
					password:null,
					confirm_password: null
				}
			}
		},
		validations: {
			email: {required, email},
			name: {required, minLength: minLength(3)},
			lastname: {required, minLength: minLength(3)},
			mobile: {required},
			document: {required},
			type_document: {required},
			address: {required},
			state: {required},
			city: {required},
			login : {
				password: {required,minLength: minLength(6), isValidPassword},
				confirm_password: {sameAsPassword: sameAs('password')}
			}
		
		},
		created(){
			this.$root.changeStatusGif(true); 
		},
		mounted(){   
			this.cart = this.data_cart;
			if (this.cart == null) {
				location.href = this.url_cart
			}
			if(this.auth) {
				this.$store.state.user = this.user;
				this.completeInformation();
			}else{
				this.email = this.user.customer_email;
				this.name = this.user.customer_name;
				this.lastname = this.user.customer_lastname;
				this.document = this.user.customer_document;
				this.type_document = this.user.customer_type_document;
				this.mobile = this.user.customer_mobile;
				if (this.data_address) {
					this.complement = this.data_address.complement;
					this.address = this.data_address.address;
					this.state = this.data_address.state_id;
					if (this.state != null) {
						this.selectedDepartment(this.state, false);
					}
					this.city = this.data_address.city_id;
				}
			}
			this.$root.changeStatusGif(false); 
		},
		methods:{  
			async loadCart(){
				axios.post('ajax/get-cart').then(function (res) {
					this.cart = res.data;             
					this.$root.changeStatusGif(false);  
				}.bind(this))
					.catch(function(error){
					console.log('Erro loading cart');
				});
			},
			completeInformation(){
				this.name = this.user.name;
				this.lastname = this.user.lastname;
				this.document = this.user.document;
				this.type_document = this.user.type_document;
				this.email = this.user.email;
				this.mobile = this.user.mobile;
				this.state = this.data_address.state_id;
				this.address = this.data_address.address;
				this.complement = this.data_address.complement;
				if (this.state != null) { 
					this.selectedDepartment(this.state, false);
				}
				this.city = this.data_address.city_id;
			} ,
			selectedDepartment(event, bool = true){
				if(bool && this.cart.shipping_description != '' && this.cart.shipping_description != null){
					this.$root.changeStatusGif(true);				
				}
				this.city = null;
				let state= event;
				axios.get(`ajax/cities/${state}`)
				.then(res => {
					this.cities = res.data;
					if(bool && this.cart.shipping_description != '' && this.cart.shipping_description != null){ 
						axios.get(`ajax/update-info-shipping`).then(response => {	      		
							this.loadCart();
						});
					}else{
						this.$root.changeStatusGif(false);
					}
				})
			},   
			shipping(){	
				if (!this.create_account) {
					this.login.password = 'abcd123';
					this.login.confirm_password = 'abcd123';
				}
				this.$v.$touch();
				if (this.$v.$invalid) { 	   	
					return false;
				}	      
				this.$root.changeStatusGif(true);
				if (this.auth) {
					this.updateAccount();
				}else if(this.create_account){
					this.createAccount();
				}else{
					this.buyCustomerGuest();
				}
			},
			updateAccount(){
				let data = {
					id : this.user.id,
					name : this.name,
					lastname : this.lastname,
					document : this.document,
					type_document : this.type_document,
					email : this.email,
					mobile : this.mobile,
					state_id : this.state,
					city_id :this.city,
					address : this.address,
					complement : this.complement,
					newsletter : this.newsletter,
					_checkout : true
				};
				axios.post(`ajax/update-account`, data).then(response => {	      		
					if (response.data.status == 'update') {
						this.calculateShipping();
					}else{	      
						this.email = this.user.email;			
						this.$toast.error(response.data.message);
					}

				});
			},
			createAccount(){
				let data = {
					name : this.name,
					lastname : this.lastname,
					document : this.document,
					type_document : this.type_document,
					email : this.email,
					mobile : this.mobile,
					password : this.login.password,
					_state_id : this.state,
					_city_id :this.city,
					_address : this.address,
					_complement : this.complement,
					newsletter : this.newsletter,
					_checkout : true
				};
				axios.post(`ajax/create-account`, data).then(response => {	      			      		
					if (response.data.status == 'save') {	      			
						this.getLogin();
					}else{
						this.$root.changeStatusGif(false);
						this.$swal({  
							icon: 'error',
							title: response.data.message, 
							showCancelButton: false,
							confirmButtonText: 'Aceptar',
						});
					}
				});
			},
			buyCustomerGuest(){
				// this.$root.changeStatusGif(true);
				let data = {
					name : this.name,
					lastname : this.lastname,
					document : this.document,
					type_document : this.type_document,
					email : this.email,
					mobile : this.mobile,
					state_id : this.state,
					city_id :this.city,
					address : this.address,
					complement : this.complement,
					newsletter : this.newsletter,
					_checkout : true
				};
				axios.post(`ajax/buy-customer-guest`, data).then(response => {	      			      		
					if (response.data.status == 'save') {	
						this.calculateShipping();
					}else{
						this.$root.changeStatusGif(false);
						this.$swal({  
							icon: 'error',
							title: response.data.message, 
							showCancelButton: false,
							confirmButtonText: 'Aceptar',
						});
					}

				});
			},
			calculateShipping(){
				axios.post('ajax/calculate-shipping')
				.then(res => {
					if(res.data.status){
						this.carrier = res.data.carrier;
						location.href=this.url_payment;            
					}else{
						this.$root.changeStatusGif(false);
						this.loadCart();
						this.$swal(res.data.message);
					}
				});
			},
			async validateEmail(){
				if (this.auth) {
					return false;
				}
				this.$root.changeStatusGif(true);
				const data= await axios.post('ajax/validate-email', {email : this.email});
				if(data.status){
					this.$root.changeStatusGif(false);
					const resp= await this.$swal({  
						icon: 'info',
						html: `<p>El correo ya se encuentra registrado, ingrese la contraseña para iniciar sesión.</p><p>Si no recuerda la contraseña puede recuperarla <a href="${this.url_forgot_password}" style="color:#488081;text-decoration:underline;font-weight:bold;">aquí</a>.</p>`, 
						input: "password",
						showCancelButton: true,
						confirmButtonText: 'Iniciar sesión',
						cancelButtonText: 'Comprar como invitado',
						inputPlaceholder: 'Ingrese la contraseña',
						inputValidator: email => {
							if (!email) {
								return 'La contraseña es requerida';
							}else {
								return undefined;
							}
						}
					});
					if(resp.isConfirmed){
						this.login.password= resp.value;
						this.getLogin();
					
					}
					if(resp.isDismissed){
						this.create_account= false;
					}
				}
			},		
			checkCreateAccount(){
				this.create_account = this.create_account ? false : true;
				this.login.password = null;
				this.login.confirm_password = null;
			},
			async getLogin(){
				this.$root.changeStatusGif(true);
				const {status, message} = await getLogin(this.email, this.login.password, this.url_checkout);
                if(!status){
                    this.$root.changeStatusGif(false);
                    this.$swal({ 
                        icon: 'error',
                        title: message, 
                        showCancelButton: false,
                        confirmButtonText: this.content.acept,
                    });
					return false;
                }
				
			},
			removeCoupon(){
				this.$root.changeStatusGif(true);
				axios.post('ajax/remove-coupon')
					.then(res => {
					if (res.data.status) {              
						this.loadCart();
						this.$swal(res.data.message);
					}
				});
			} 
		}
	}
</script>