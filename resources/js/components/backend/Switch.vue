<template>
	<label class="switch" role="button">
        <input type="checkbox" :value="active" v-model="checked"  @change="update"/>
        <div class="circle" :class="{'circle-on': checked,'circle-off': !checked}">
            <span>{{ label }}</span>
        </div>
    </label>
</template>
<script>
	export default{
		props:['status', 'url', 'id'],
		data(){
			return {
                label: "",
                checked: 0,
                active: 0,
                dataOn: 'SI',
                dataOff: 'NO'
			}
		},
		mounted(){
            this.checked= this.status == 0 ? false : true;
            this.active= this.status;
            this.label = this.checked ? this.dataOn : this.dataOff;
		},
		methods : {
            update() {
                this.label = this.checked ? this.dataOn : this.dataOff;
                let data={
                    id: this.id,
                    status: this.checked
                };
                axios.post(this.url, data)
                    .then(response => {
                    if(response.data.status){
                        if(response.data.type == 'success'){
                            this.$toast.success(response.data.message)
                        }else if(response.data.type == 'warning'){
                            this.$toast.warning(response.data.message)
                        }else{
                            this.$toast.error(response.data.message)
                            this.checked= !this.checked;
                            this.label = this.checked ? this.dataOn : this.dataOff;
                        }
                    }
                    }).catch(err => {
                        console.log('Error al eleiminar');
                });
                
            }
		}
	}
</script>
<style lang="scss">
    $default: black;
    $active: #309f92;
    $inactive: #3d4041;
    $text: #9d9d9d;
    $text-hover: white;
    $size: 12px;
    $w: 66px;
    $h: 22px;
    $circle: $h - 4;
    $toggle-dis: $w - $circle - 2;

    .switch {
        position: relative;
        display: inline-block;
        width: $w;
        height: $h;
        border-radius: 4px;
        .circle {
            display: flex;
            align-items: center;
            width: 100%;
            height: $h - 2;
            border-radius: 4px;
            background-color: $default;

            & > span {
                font-size: $size;
                color: $text;
                padding-left: $h + 4;
            }

            &::before {
                position: absolute;
                content: "";
                height: $circle;
                width: $circle;
                left: 1px;
                top: 1px;
                border-radius: 4px;
                background-color: white;
                -webkit-transition: 0.4s;
                transition: 0.4s;
            }

            &:hover {
                background-color: lighten($default, 20);

                & > span {
                    color: $text-hover;
                }
            }
        }

        .circle-on {
            background-color: $active;

            & > span {
                color: white;
            }

            &:hover {
                background-color: white;
                border: 1px $active solid;

                &::before {
                    background-color: $active;
                }

                & > span {
                    color: $active;
                }
            }
        }

        .circle-off {
            background-color: $inactive;

            & > span {
                color: white;
            }

            &:hover {
                background-color: white;
                border: 1px $inactive solid;

                &::before {
                    background-color: $inactive;
                }

                & > span {
                    color: $inactive;
                }
            }
        }

        & input {
            display: none;

            &:checked + .circle:before {
                -webkit-transform: translateX($toggle-dis);
                -ms-transform: translateX($toggle-dis);
                transform: translateX($toggle-dis);
            }
        }
    }
</style>
