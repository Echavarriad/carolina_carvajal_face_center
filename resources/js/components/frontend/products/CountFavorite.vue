<script>
	export default{
		props:['qty'],
		data(){
			return {
				count: 0
			}
		},
		mounted(){
			this.count = this.qty;
		}
	}
</script>