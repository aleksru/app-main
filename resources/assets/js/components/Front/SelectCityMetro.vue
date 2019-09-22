<template>
    <div>
        <div class="col-sm-3">
            <label for="city_id" class="control-label">Город доставки</label>
            <input name="city_id" type="text" :value="selectedCity" v-show="false"/>
            <v-select label="name" index="id" :value="valueCity" :options="options" v-model="selectedCity" @input="setCity" @search:focus="onFocusCity">
            </v-select>
        </div>
        <div class="col-sm-3">
            <label for="metro_id" class="control-label">Метро</label>
            <input name="metro_id" type="text" :value="selectedMetro" v-show="false"/>
            <v-select label="name" index="id" :value="valueMetro" :options="optionsMetros" v-model="selectedMetro">
            </v-select>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            options: Array,
            valueCity: null,
            valueMetro: null
        },
        data() {
            return {
                selectedCity: null,
                selectedMetro: null,
                optionsMetros: [],
            }
        },
        methods: {
            onFocusCity(){
                this.selectedMetro = null;
            },

            async setCity(val){
                if(val){
                    try{
                        let metros = await this.getMetros(val);
                        this.optionsMetros = metros.data.metros;
                    }catch (err){
                        console.log(err);
                    }
                }else{
                    this.optionsMetros = [];
                    this.selectedMetro = null;
                }
            },

            async getMetros(city_id){
              let res = await axios.get(`/metro/city/${city_id}`);

              return res;
            },
        },

        mounted() {
            this.selectedCity = this.valueCity;
            this.selectedMetro = this.valueMetro;
        },
    }
</script>