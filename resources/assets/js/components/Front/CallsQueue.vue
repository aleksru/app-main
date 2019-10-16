<template>
    <div>
        <div class="box-header with-border">
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Оператор</th>
                        <th>Клиент</th>
                        <th>Статус</th>
                        <th>Магазин</th>
                        <th>Создан</th>
                    </tr>
                    <tr v-for="(order, index) in orders" :key="order.id">
                        <td>
                            <a :href="'/orders/' + order.id + '/edit'" target="_blank">
                                {{order.id}}
                                    <i class="fa fa-address-card-o" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td>{{order.operator ? order.operator.name : '-'}}</td>
                        <td>{{order.client ? order.client.name : 'No name'}} / {{order.client ? order.client.phone : ''}}</td>
                        <td>
                            <span :class="'label label-' + (order.status ? order.status.color : '')">
                                {{order.status ? order.status.status : 'No status'}}
                            </span>
                            {{order.communication_time ? (' / Перезвон: ' + order.communication_time) : ''}}</td>
                        <td>{{order.store ? order.store.name : 'No store'}}</td>
                        <td>{{order.created_at}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            init_orders: [Array, null],
        },

        data() {
            return {
                orders: [],
            }
        },

        methods: {
            async getMissedCalls(){
                let res = await this.$axios.get('/ringing');

                return res;
            },

            updateTable(){
                this.getMissedCalls().then((res) => {
                    this.orders = res.data.orders;
                }).catch((err) => {
                    console.log(err);
                });
            },

            addElemOnStartArr(order){
                let orders = [order, ...this.orders];
                this.orders = orders;
            },

            deleteOrder(id){
                let index = this.orders.findIndex((currentValue) => currentValue.id == id);
                this.orders.splice(index, 1);
            }
        },

        mounted(){
            this.orders = this.init_orders;
            window.setInterval(() => {
                this.updateTable();
            }, 10000);

            window.Echo.channel(`order`)
                .listen('CreatedOrderEvent', (e) => {
                    this.addElemOnStartArr(e.order);
                })
                .listen('UpdatedOrderEvent', (e) => {
                    this.deleteOrder(e.order.id);
            });
        },
    }
</script>