<template>
    <modal name="operator-call"
           :adaptive="true"
           :min-width="900"
           :scrollable="true"
           :reset="true"
           :resizable="true"
           height="auto">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Вам входящий звонок</h3>
            </div>
            <div class="box-body">
                <p>Клиент: {{client.name}}, {{client.phone}}, <span class="text-red">{{ blackList }}</span>.</p>
                <p>Заказ: <a :href="'/orders/' + orderId + '/edit'" target="_blank">#{{orderId}}</a></p>
            </div>
            <!-- /.box-body -->
        </div>
        <button type="button" class="btn btn-block btn-warning btn-sm" @click="hideModal()">Close</button>
    </modal>
</template>

<style scoped>
    .v--modal-overlay {
        z-index: 2000;
    }
    .box.box-warning {
        font-size: 150%;
    }
</style>

<script>
    export default {
        props: {
            user: Object,
        },
        data() {
            return {
                client: {},
                orderId: null
            }
        },
        methods: {
            showModal () {
                this.$modal.show('operator-call');
            },

            hideModal () {
                this.$modal.hide('operator-call');
                this.client = {};
                this.orderId = null;
            }
        },

        mounted() {
            window.Echo.private(`operator-incomming.${this.user.id}`)
                .listen('OperatorCallConnected', (e) => {
                    this.client = e.client;
                    this.orderId = e.orderId;
                    this.showModal();
                });
        },

        computed: {
            blackList(){
                if('is_black_list' in this.client) {
                    return this.client.is_black_list ? 'В черном списке' : '';
                }

                return '';
            },
        }
    }
</script>