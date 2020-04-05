<template>
    <modal name="products"
           :adaptive="true"
           :draggable="true"
           width="70%"
           :scrollable="true"
           :reset="true"
           :resizable="true"
           @before-close="beforeClose"
           height="auto">

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
            <div class="row">
                <div class="col-md-12">
                    <span class="badge bg-blue" style="font-size: 15px;padding: 5px;margin-right: 5px;margin-bottom: 5px">
                        В заказе:
                    </span>
                    <span v-for="user in editing_order" class="badge bg-blue"
                          style="font-size: 15px;padding: 5px;margin-right: 5px;margin-bottom: 5px">
                        {{user.name}}
                    </span>
                </div>
            </div>
            <logistic-form  :initial_order="order"
                            :initial_suppliers="suppliers"
                            :initial_couriers="couriers"
                            :stock_statuses="stockStatuses"
                            :logistic_statuses="logisticStatuses"
                            @update-order="onUpdateOrder($event)"
                            :min-margin="minMargin"
                            :min-margin-product="minMarginProduct">
            </logistic-form>
        </div>
    </modal>
</template>

<style scoped>
    .v--modal-overlay {
        z-index: 2000;
    }
</style>

<script>
    import LogisticForm from './LogisticForm.vue';

    export default {
        components: {
            LogisticForm
        },

        props: {
            tableId: null,
            stockStatuses: Array,
            logisticStatuses: Array,
            minMargin: {
                type: Number,
                default: 0
            },
            minMarginProduct: {
                type: Number,
                default: 0
            }

        },
        data() {
            return {
                order: {},
                suppliers: [],
                couriers: [],
                editing_order: []
            }
        },

        methods: {
            beforeClose (event) {
                this.editing_order = [];
                this.leaveTableChannel();
            },

            async getOrdersDetails(orderId) {
                const response = await axios.post('/realizations/' + orderId, {query: ''});

                return response;
            },

            async createEventTableUpdate(){
                let response = await axios.get(`/logistics/on-update-logist-table/${this.order.id}`);

                return response.data;
            },

            onUpdateOrder(e){
                this.order = Object.assign(this.order, e);
                setTimeout(() => {
                    this.hideModal();
                }, 1000);
                this.createEventTableUpdate();
            },

            showModal () {
                this.$modal.show('products');
            },

            hideModal () {
                this.order = {};
                this.$modal.hide('products');
            },

            async getSuppliers(){
                let response = await axios.get('/supplier/get');
                this.suppliers = response.data;

                return response.data;
            },

            async getCouriers(){
                let response = await axios.get(`/courier/order/${this.order.id}/get`);
                this.couriers = response.data;

                return response.data;
            },

            async orderProcess(orderId){
                try{
                    let response = await this.getOrdersDetails(orderId);
                    this.order = response.data;
                    this.getCouriers();
                    this.channelOrder();
                    this.showModal();
                }catch(e){
                    toast.error('Произошла ошибка. Попробуйте еще');
                    throw e;
                }
            },

            channelOrder(){
                this.editing_order = [];
                window.Echo.join(`logistic-table.${this.order.id}`)
                    .here((members) => {
                        // запускается, когда вы заходите
                        this.editing_order = members;
                    })
                    .joining( (joiningMember) =>  {
                        // запускается, когда входит другой пользователь
                        let check = this.editing_order.find( (val) => val.id == joiningMember.id );
                        if(check === undefined){
                            this.editing_order.push(joiningMember);
                        }
                    })
                    .leaving( (leavingMember) => {
                        // запускается, когда выходит другой пользователь
                        let index = this.editing_order.findIndex((val) => val.id == leavingMember.id);
                        if(index !== -1){
                            this.editing_order.splice(index, 1);
                        }
                    });
            },

            leaveTableChannel(){
                window.Echo.leave(`logistic-table.${this.order.id}`);
            },

            async sendGoogleTables(orderId){
                let response = await axios.get(`/logistics/send-google-tables/${orderId}`);

                return response.data;
            },

            async orderSendGoogleTables(orderId){
                try{
                    let response = await this.sendGoogleTables(orderId);
                    toast.success(response.message);
                }catch(e){
                    toast.error('Произошла ошибка. Попробуйте еще');
                    throw e;
                }
            }
        },

        mounted() {
            (async () => {
                await this.getSuppliers();
            })();

            $(`#${this.tableId}`).on( 'draw.dt', () => {
                $('.btn-logist-details').click((e) => {
                    if(e.target.dataset.id === undefined){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw Exception('Order id not defined!');
                    }
                    this.orderProcess(e.target.dataset.id);
                });
                $('.btn-send-google').click((e) => {
                    if(e.target.dataset.id === undefined){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw Exception('Order id not defined!');
                    }
                    this.orderSendGoogleTables(e.target.dataset.id);
                });

                $(`#${this.tableId} tbody tr td`).dblclick((e) => {
                    const row = e.target.parentNode;
                    const orderId = row.dataset.orderId;

                    if(orderId === undefined){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw Exception('Order id not defined!');
                    }
                    this.orderProcess(orderId);
                });
            });

            window.Echo.private('logistic-table')
                .listen('OrderConfirmedEvent', (e) => {
                    $(`#${this.tableId}`).DataTable().ajax.reload(null, false);
                })
                .listen('UpdateRealizationsConfirmedOrderEvent', (e) => {
                    $(`#${this.tableId}`).DataTable().ajax.reload(null, false);
                })
                .listen('LogistTableUpdateEvent', (e) => {
                    $(`#${this.tableId}`).DataTable().ajax.reload(null, false);
                    if(e.order){
                        toast.info(`Заказ #${e.order.id} обновлен`);
                        if(this.order.id === e.order.id) {
                            (async () => {
                                let newOrder = await this.getOrdersDetails(this.order.id);
                                this.order = Object.assign(this.order, newOrder.data);
                            })();
                        }
                    }
                });
        },
    }
</script>