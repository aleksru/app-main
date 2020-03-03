<template>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Позиции заказа</h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 1000px;">

                    <div class="input-group-btn">
                        <div class="col-sm-12">
                            <button class="btn btn-primary pull-right" @click.prevent="submit">
                                <i class="fa fa-save"></i> Сохранить
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <form class="form-horizontal">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Коммент СКЛАД</label>

                            <div class="col-sm-8">
                                <textarea class="form-control" v-model="order.comment_stock"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <form class="form-horizontal">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Курьер</label>

                            <div class="col-sm-8">
                                <v-select label="name"
                                          index="id"
                                          :options="initial_couriers"
                                          v-model="order.courier_id">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Зп курьера</label>

                            <div class="col-sm-8">
                                <input type="number" class="form-control" v-model="order.courier_payment">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Модель</th>
                                <th>IMEI</th>
                                <th>Поставщик</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in order.realizations" :key="product.id">
                                <td style="width: 2%">{{ index + 1  }}</td>
                                <!--//Model-->
                                <td style="width: 15%"> {{ order.realizations[index].product.product_name }} </td>
                                <td style="width: 10%">
                                    <!--//imei-->
                                    <input type="text" class="form-control" v-model="order.realizations[index].imei">
                                </td>
                                <td style="width: 10%">
                                    <v-select label="name"
                                              index="id"
                                              :options="initial_suppliers"
                                              v-model="order.realizations[index].supplier_id">
                                    </v-select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

    export default {
        props: {
            initial_order: Object,
            initial_suppliers: Array,
            initial_couriers: Array
        },

        data() {
            return {
                order: this.initial_order
            }
        },
        methods: {
            async submit(){
                try{
                    const idToastLoading  = toast.loading('Обновление...');
                    const responseOrder = await this.submitOrder();
                    const responseRealizations = [];

                    for (let i = 0; i < this.order.realizations.length; i++) {
                        let response = await this.submitRealizations(i);
                        responseRealizations.push(response.data);
                    }
                    responseOrder.data.realizations = responseRealizations;
                    this.$emit('update-order', responseOrder.data);
                    toast.hide(idToastLoading);
                    toast.success('Успешно обновлено!');
                }catch (e){
                    toast.hide(idToastLoading);
                    toast.error('Произошла ошибка. Попробуйте еще');
                    throw e;
                }
            },
            async submitOrder(){
                const response = await axios.post(`/orders-logistic/${this.order.id}/update`, this.order);
                return response;
            },

            async submitRealizations(index){
                const response = await axios.post(`/realizations-logistic/${this.order.realizations[index].id}/update`,
                                                                                        this.order.realizations[index]);
                return response;
            },
        },
    }
</script>