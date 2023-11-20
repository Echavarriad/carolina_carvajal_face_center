<script>
  export default{
    props:['data_attributes', 'variations', 'product', 'content'],
    data(){
      return {
          opt:[],
          first_attr : null,
          init : false,
          complete : false,
          complete_full : false,
          attr_selected : [],
          variation : {},
          attributes : [],
          indexSelected: 0
       }
    },
    mounted(){
      this.$root.changeStatusGif(false);
      this.attributes = this.data_attributes;
    }, 
    methods :{
        async changeSelection(event, attr){           
            this.opt = event.target.value;
            attr.type = true;
            this.complete = true;
            attr.option_selected = this.opt;
            let self = this;
            let values = '';
            this.attributes.forEach(function(item){
              if (values == '') {
                values +=item.option_selected;
              } else {
                values += '_'+item.option_selected;
              }          		
              if (!item.type) {
                self.complete = false;
              }        	
            })
            if (this.complete) {
                //this.$root.changeStatusGif(true);
                let data = {product_id : this.product.id, values : values};
                const resp= await axios.post('ajax/validate-variation' , data);
                if(resp.data.status == true){
                  this.variation = resp.data.variation;
                  let item= this.variations.find(item => item.id == this.variation.id);
                  this.indexSelected= item.index;
                  this.validateVariation();
                }else{
                  await this.$emit('changegif', false);
                  let dataVar = {id:null , complete: false , stock : 0, message: resp.data.message, index: this.indexSelected};
                  this.$emit('changevar', dataVar);
                  this.$toast.error(resp.data.message);
                }
            }
        }, 
        validateVariation(){
          let dataVar = {id:null , complete: false , stock : 0, message: this.content.not_stock_variation_selected, index: this.indexSelected};		
          var priceElement = document.querySelector('.price');      
          var priceSpecialElement = document.querySelector('.price_special');     
          if(this.variation.stock < 1){
            this.$toast.error(this.content.not_stock_variation_selected);
          }else{
          	  priceElement.innerHTML = this.variation.price;
              priceSpecialElement.innerHTML = this.variation.price_special;
      				dataVar.id = this.variation.values;
              dataVar.complete = true;
              dataVar.stock = this.variation.stock;                      
          } 
          this.$emit('changevar', dataVar);
          this.$emit('changegif', false);
        },
    }
  }
</script>