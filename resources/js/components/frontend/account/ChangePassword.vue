<template>
	<div class="acc_form_content">
		<a v-if="show_form == 'not' && !toogle" href="#" @click.prevent="showForm">{{ links.change_password }}</a>
		<form action="" v-if="toogle">
			<span v-if="show_form == 'not'" @click="showForm(false)">X</span>
			<div class="_input">
				<b-form-input  type="password" :placeholder="forms.current_password" v-model.trim="$v.current_password.$model" :state="$v.current_password.$dirty ? !$v.current_password.$invalid : null"></b-form-input>
				<p class="error" v-if="!$v.current_password.required && current_password != null">{{ messages.required_current_password }}</p>
			</div>
			<div class="_input">
				<b-form-input type="password" :placeholder="forms.new_password" v-model="$v.password.$model" :state="$v.password.$dirty ? !$v.password.$invalid : null" autocomplete="new-password"></b-form-input>
				<p class="error" v-if="!$v.password.required && password != null">{{messages.required_new_password}}</p>
				<p class="error" v-if="!$v.password.minLength && password != null">{{messages.min_password}} {{ $v.password.$params.minLength.min }} {{messages.characters}}</p>
				<p class="error" v-if="!$v.password.isValidPassword && password != null">{{messages.letter_number_password}}</p>
			</div>
			<div class="_input">
				<b-form-input type="password" :placeholder="forms.confirm_password" v-model.trim="$v.confirm_password.$model" :state="$v.confirm_password.$dirty ? !$v.confirm_password.$invalid : null" autocomplete="new-password" v-on:keyup.enter="enterPassword"></b-form-input>
				<p class="error" v-if="!$v.confirm_password.sameAsPassword && confirm_password != null">{{messages.not_same_password}}</p>
			</div>
			<div class="_button">
				<button type="button" v-on:click="changePassword">{{links.change_password}}</button>
			</div>
		</form>
	</div>	
</template>
<script>
	import { required, minLength, sameAs, helpers } from 'vuelidate/lib/validators';
	import 'bootstrap-vue/dist/bootstrap-vue.css'; 
	const isValidPassword = helpers.regex('alphaNum', /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]/) 
	export default{
		props : ['url_login', 'content', 'messages', 'forms', 'links', 'show_form'],
		data(){
			return{
				current_password : null,
				password : null,
				confirm_password : null,
				toogle: false
			}
		},		
		validations: {
			current_password: {required},
			password: {required,minLength: minLength(6), isValidPassword},
			confirm_password: {sameAsPassword: sameAs('password')}
		},
		mounted(){
			if(this.show_form == 'yes'){
				this.toogle = true;
			}
			this.$root.changeStatusGif(false);		
		},
		methods:{
			 async changePassword(){
			 	this.$v.$touch()
				if (this.$v.$invalid) {	      	
					return false;
				}
				this.$root.changeStatusGif(true);	
				let data = {
					current_password : this.current_password,
					password : this.password,
				};
				const resp= await axios.post(`ajax/change-password`, data);
				this.$root.changeStatusGif(false);
				if (resp.data.status == 'change') {
					this.show_form= false;
					this.$swal({  
						icon: 'success',
						title: resp.data.message, 
						confirmButtonText: this.content.acept,
					}).then((result) => {
						if(result.value){
							let self = this;
							setTimeout(function(){ location.href=self.url_login; }, 500);
						}
					});
				}else{
					this.$toast.error(resp.data.message);
				}
			},
			enterPassword(){
				if (event.which  === 13) {
					this.changePassword();
				}
			},
			showForm(bool = true){
				this.toogle= bool;
			}
		}
	}
</script>