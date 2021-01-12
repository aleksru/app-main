<template>
    <div class="row">
        <div class="row">
            <div class="col-md-3">
                <label>Кол-во пропущенных в ЛК Манго</label>
            </div>
            <div class="col-md-3">
                <label>Кол-во принятых входящих в ЦРМ</label>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-3">
                <label>Результат</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <input class="form-control" type="text" v-model.number="count_missed">
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" v-model.number="count_incomings">
            </div>
            <div class="col-md-1">
                <span>=</span>
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" v-model="result" disabled>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>

<script>
    export default {
        data() {
            return {
                count_missed: 0,
                count_incomings: 0
            }
        },

        computed: {
            result(){
                let divider = this.count_missed + this.count_incomings;
                if(divider === 0){
                    return 0;
                }

                return (100 / divider  * this.count_missed).toFixed(2);
            },
        }
    }
</script>
