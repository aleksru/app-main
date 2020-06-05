<template>
    <modal name="fast-changes"
           :adaptive="true"
           :draggable="true"
           class="fast-changes"
           width="20%"
           :scrollable="true"
           :reset="true"
           :resizable="true"
           height="auto"
           @closed="clear()">

        <div slot="top-right">
            <button @click="hideModal()">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <div class="box-header with-border" style="min-height: 45px">
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool btn-lg" @click="hideModal()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form class="form-horizontal">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Статус склада</label>

                            <div class="col-sm-8">
                                <v-select label="name"
                                          index="id"
                                          :options="stock_statuses"
                                          v-model="stock_status_id">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Курьер</label>

                            <div class="col-sm-8">
                                <v-select label="name"
                                          index="id"
                                          :options="couriers"
                                          v-model="courier_id">
                                </v-select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<style>
    .v--modal-overlay {
        z-index: 2000;
    }

    .fast-changes.v--modal-overlay .v--modal-box {
        overflow: visible!important;
    }
</style>

<script>
    const MODAL_NAME = 'fast-changes';
    export default {
        props: {
            stock_statuses: Array,
            tableId: null,
        },
        data() {
            return {
                orderId: null,
                stock_status_id: null,
                couriers: null,
                courier_id: null
            }
        },

        methods: {
            showModal () {
                this.$modal.show(MODAL_NAME);
            },

            hideModal () {
                this.clear();
                this.$modal.hide(MODAL_NAME);
            },

            clear(){
                this.orderId = null;
                this.stock_status_id = null;
                this.couriers = null;
                this.courier_id = null;
            },

            onChange(){
                let data = {};
                if(this.stock_status_id !== null){
                    data = {...data, stock_status_id: this.stock_status_id}
                }
                if(this.courier_id !== null){
                    data = {...data, courier_id: this.courier_id}
                }
                axios.post(`/orders-logistic/${this.orderId}/update`, data)
                    .then((res) => {
                        toast.success('Успешно обновлено!');
                        this.hideModal();
                    }).catch((err) => {
                        console.log(err);
                        toast.error('Произошла ошибка!');
                    });
            },

            async getCouriers(){
                let response = await axios.get(`/courier/order/${this.orderId}/get`);
                this.couriers = response.data;

                return response.data;
            },
        },

        mounted() {
            $(`#${this.tableId}`).on( 'draw.dt', () => {
                $('.btn-fast-changes').click(async (e) => {
                    if(e.target.dataset.id === undefined){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw Exception('Order id not defined!');
                    }
                    this.orderId = e.target.dataset.id;
                    await this.getCouriers();
                    this.showModal();
                });
            });
        },

        watch: {
            stock_status_id: _.throttle (function(newStatusId, oldStatusId){
               if(this.stock_status_id !== null){
                   this.onChange();
               }
            }, 2000),

            courier_id: _.throttle (function(newStatusId, oldStatusId){
                if(this.courier_id !== null){
                    this.onChange();
                }
            }, 2000),
        },
    }
</script>