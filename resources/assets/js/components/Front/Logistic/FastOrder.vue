<template>
    <modal name="products"
           :adaptive="true"
           :min-width="900"
           :scrollable="true"
           :reset="true"
           :resizable="true"
           height="auto">
        <logistic-form  :initial_order="order"
                        :initial_suppliers="suppliers"
                        :initial_couriers="couriers"
                        @update-order="onUpdateOrder($event)">
        </logistic-form>
        <button type="button" class="btn btn-block btn-warning btn-sm" @click="hideModal()">Close</button>
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
            tableId: null
        },
        data() {
            return {
                order: {},
                suppliers: [],
                couriers: []
            }
        },

        methods: {
            async getOrdersDetails(orderId) {
                const response = await axios.post('/realizations/' + orderId, {query: ''});

                return response;
            },

            onUpdateOrder(e){
                this.order = e;
                $(`#${this.tableId}`).DataTable().ajax.reload(null, false);
            },

            showModal () {
                this.$modal.show('products');
            },

            hideModal () {
                this.$modal.hide('products');
            },

            async getSuppliers(){
                let response = await axios.get('/supplier/get');
                this.suppliers = response.data;

                return response.data;
            },

            async getCouriers(){
                let response = await axios.get('/courier/get');
                this.couriers = response.data;

                return response.data;
            }
        },

        mounted() {
            this.getSuppliers();
            this.getCouriers();
            $(`#${this.tableId}`).on( 'draw.dt', () => {
                $('.btn-logist-details').click( async (e) => {
                    if(e.target.dataset.id === undefined){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw Exception('Order id not defined!');
                    }
                    try{
                        let response = await this.getOrdersDetails(e.target.dataset.id);
                        this.order = response.data;
                        this.showModal();
                    }catch(e){
                        toast.error('Произошла ошибка. Попробуйте еще');
                        throw e;
                    }
                });
            });
        },
    }
</script>