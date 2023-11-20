<script>
	export default{
		props:[],
		data(){
			return {
                term:'',
				products: []
			}
		},
		mounted(){
		},
        methods:{
            searchProduct(){
                if(this.term.length == 0){
                    this.products= [];
                    return false;                    
                }
                if(this.term.length < 3){
                    return false;
                }
                axios.get(`ajax/search-products/${this.term}`)
				.then(resp => {
					if(resp.data.status){
                        this.products= resp.data.records;
                    }
				});
            }
        }
	}
</script>