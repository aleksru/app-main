<template>
    <form class="form-horizontal">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">ID заказов.
                <span>Кол-во: {{ prepareIds.length }}</span>
            </label>
            <div class="col-sm-6">
                <textarea rows="6" class="form-control" :value="ids" @change="onStatuses"></textarea>
            </div>

            <label for="name" class="col-sm-2 control-label">Статус оператора</label>
            <div class="col-md-2">
                <v-select label="name"
                          v-model="operator_status_id"
                          :options="initial_operator_statuses"
                          index="id"
                          :searchable="false">
                </v-select>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" @click.prevent="send()" :disabled="checkBtnSend">
                <i class="fa fa-save"></i> Отправить
            </button>
        </div>
    </form>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            initial_operator_statuses: Array
        },
        data() {
            return {
                operator_status_id: null,
                prepareIds: [],
            }
        },
        methods: {
            async send() {
                try{
                    let response = await axios.post('/statuses/orders', {
                        operator_status_id: this.operator_status_id,
                        order_ids: this.prepareIds
                    });
                    toast.success(response.data.message);

                    return response;
                }catch (err){
                    toast.error('Упс что то пошло не так (((');
                    console.log(err);
                }
            },

            onStatuses(e) {
                let str = e.target.value;
                str = str.trim().replace(/[^0-9]/g, ',');
                this.prepareIds = str.split(',').filter(elem => elem !== '');
            }
        },

        computed: {
            ids(){
                return this.prepareIds.toString();
            },

            checkBtnSend(){
                return (this.prepareIds.length == 0 || this.operator_status_id == null);
            }
        }
    }
</script>