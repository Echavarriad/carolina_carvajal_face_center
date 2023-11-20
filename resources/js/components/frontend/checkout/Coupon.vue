<script>
	export default{
    props : ['content'],
		data(){
			return {
        coupon : null,
			}
		},
		mounted(){   
		},
		methods:{
      async applyCoupon(){
        if (this.coupon == null) {
          this.$swal(this.content.enter_code_coupon);
          return false;
        }
        this.$root.changeStatusGif(true);
        axios.post('ajax/apply-coupon', {code : this.coupon})
          .then(res => {
            if (res.data.status == true) {
              this.$root.changeStatusGif(false);
              this.coupon = null;
              this.$emit('updatecart');
              this.$swal(res.data.message);
            }else{
              this.coupon = null;
              this.$root.changeStatusGif(false);
              this.$swal(res.data.message);
            }
          })
      }     
		}
	}
</script>