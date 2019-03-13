<template>
    <modal name="products"
           :adaptive="true"
           :min-width="900"
           :scrollable="true"
           :reset="true"
           :resizable="true"
           height="auto">
        <products-table v-if="order"
                        :initial_data="order.realizations"
                        :initial_order="order.id"
                        :initial_price_delivery="order.delivery_type ? parseInt(order.delivery_type.price) : 0"
                        :suppliers="suppliers_r">
        </products-table>
        <button type="button" class="btn btn-block btn-warning btn-sm" @click="hideModal()">Close</button>
    </modal>
</template>

<style scoped>
    .v--modal-overlay {
        z-index: 2000;
    }
</style>

<script>
    export default {
        props: {
            suppliers_r: Array,
        },
        data() {
            return {
                order: null
            }
        },
        methods: {
            async getRealizations(orderId) {
                let response = await axios.post('realizations/' + orderId, {query: ''});

                return response;
            },

            showModal () {
                this.$modal.show('products');
            },

            hideModal () {
                this.$modal.hide('products');
            }
        },

        mounted() {
            /**
             * Событие перерисовки таблицы
             */
            $('#orders-table').on( 'draw.dt', () => {
                $('.btn-realiz').click( (e) => {
                    this.getRealizations(e.target.dataset.orderId)
                    .then((response) => {
                        this.order = response.data;
                    }).then(() => {
                        this.showModal();
                    }).catch(function () {
                        toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                    });
                });
            });
        },
    }
</script>