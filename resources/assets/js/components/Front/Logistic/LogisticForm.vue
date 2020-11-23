<template>
    <div>
        <div class="box-header">
            <h3 class="box-title">Позиции заказа №{{order.id}}</h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 1000px;">

                    <div class="input-group-btn">
                        <div class="col-sm-12">
                            <button class="btn btn-primary pull-right" @click.prevent="submit" :disabled="disableBtnSubmit">
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Коммент СКЛАД</label>

                            <div class="col-sm-8">
                                <textarea class="form-control" v-model="order.comment_stock"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Статус СКЛАДА</label>

                            <div class="col-sm-8">
                                <v-select label="name"
                                          index="id"
                                          :options="stock_statuses"
                                          v-model="order.stock_status_id">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Причина отказа</label>

                            <div class="col-sm-8">
                                <v-select label="name"
                                          index="id"
                                          :options="logistic_statuses"
                                          v-model="order.logistic_status_id">
                                </v-select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <form class="form-horizontal">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Коммент ЛОГИСТ</label>

                            <div class="col-sm-8">
                                <textarea class="form-control" v-model="order.comment_logist"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Зп курьера</label>

                            <div class="col-sm-8">
                                <input type="number" min="0" step="50" class="form-control" v-model="order.courier_payment">
                            </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <div class="checkbox">-->
<!--                                <label>-->
<!--                                    <input type="checkbox" v-model="isShowRefusals">-->
<!--                                    Показать отказы-->
<!--                                 </label>-->
<!--                            </div>-->
<!--                        </div>-->
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
                                <th>Закупка</th>
                                <th>Продажа</th>
                                <th>Поставщик</th>
                                <th>Статус</th>
                                <th>Причина отказа</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in order.realizations"
                                :key="product.id"
                                :class="productMarginCheckByProductType(index) ? '' : 'bg-red'"
                                :style="order.realizations[index].reason_refusal_id !== null ? {opacity: 0.4} : ''">
                                <td style="width: 2%">{{ index + 1  }}</td>
                                <!--//Model-->
                                <td style="width: 15%"> {{ order.realizations[index].product.product_name }} </td>
                                <td style="width: 10%" >
                                    <!--//imei-->
                                    <input :disabled="!showImeiForProduct(index)" type="text" class="form-control" v-model="order.realizations[index].imei">
                                </td>
                                <td style="width: 7%">
                                    <!--//price_opt //v-model.number="order.realizations[index].price_opt"-->
                                    <input v-show="!isService(index)"
                                           type="number"
                                           class="form-control"
                                           @focus="order.realizations[index].price_opt === 0 ? order.realizations[index].price_opt = null : ''"
                                           @blur="order.realizations[index].price_opt === null ? order.realizations[index].price_opt = 0 : ''"
                                           v-model.number="order.realizations[index].price_opt">
                                    <span v-if="isProduct(index)"
                                          v-show="!productMarginCheckByProductType(index)"
                                          class="help-block"
                                          style="color: #ffffff; font-size: 11px">Минимальная маржа {{minMarginProduct}}
                                    </span>
                                </td>
                                <td style="width: 7%">
                                    <!--//price-->
                                    <input disabled type="number" min="0" step="50" class="form-control" v-model="order.realizations[index].price">
                                </td>
                                <td style="width: 10%">
                                    <v-select v-if="showSuppliersForProduct(index)"
                                              label="name"
                                              index="id"
                                              :options="initial_suppliers"
                                              v-model="order.realizations[index].supplier_id">
                                    </v-select>
                                </td>
                                <td style="width: 10%">
                                    <v-select v-show=" ! isService(index) "
                                              label="name"
                                              index="id"
                                              :options="realization_statuses"
                                              v-model="order.realizations[index].realization_status_id">
                                    </v-select>
                                </td>
                                <td style="width: 10%">
                                    <v-select label="name"
                                              index="id"
                                              :options="logistic_statuses"
                                              v-model="order.realizations[index].reason_refusal_id">
                                    </v-select>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Итого:</th>
                                <th></th>
                                <th></th>
                                <th>{{sumPurchase}}</th>
                                <th>{{sumSale}}</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Прибыль:</th>
                                <th :class="(sumProfit < minMargin) ? 'bg-red' : 'bg-green'">{{sumProfit}}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
</style>

<script>
    const PRODUCT_TYPE = 'PRODUCT';
    const ACCESSORY_TYPE = 'ACCESSORY';
    const SERVICE_TYPE = 'SERVICE';

    export default {
        props: {
            initial_order: Object,
            initial_suppliers: Array,
            initial_couriers: Array,
            stock_statuses: Array,
            logistic_statuses: Array,
            realization_statuses: Array,
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
                order: this.initial_order,
                isShowRefusals: false,
                disableBtnSubmit: false
            }
        },
        methods: {
            showRealizationRefusal(index){
                if(this.order.realizations[index].reason_refusal_id !== null){
                    return this.isShowRefusals;
                };
                return true;
            },
            isProduct(i){
                return this.order.realizations[i].product_type === PRODUCT_TYPE
            },
            isAccessory(i){
                return this.order.realizations[i].product_type === ACCESSORY_TYPE
            },
            isService(i){
                return this.order.realizations[i].product_type === SERVICE_TYPE
            },

            showSuppliersForProduct(index){
                return this.order.realizations[index].product_type === PRODUCT_TYPE ||
                    this.order.realizations[index].product_type === ACCESSORY_TYPE;
            },

            showImeiForProduct(index){
                return this.order.realizations[index].product_type === PRODUCT_TYPE;
            },

            productMarginCheckByProductType(index){
                return this.order.realizations[index].product_type === PRODUCT_TYPE ? this.productMarginCheck(index) : true;
            },

            productMarginCheck(index){
                return parseInt(this.order.realizations[index].price - this.order.realizations[index].price_opt) >= this.minMarginProduct;
            },

            async submit(){
                const idToastLoading  = toast.loading('Обновление...');
                this.disableBtnSubmit = ! this.disableBtnSubmit;
                try{
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
                    if(e.response.data.errors){
                        for(let error in e.response.data.errors){
                            e.response.data.errors[error].map((e) => toast.error(e));
                        }
                    }else{
                        toast.error('Произошла ошибка. Попробуйте еще');
                    }
                    throw e;
                }
                this.disableBtnSubmit = ! this.disableBtnSubmit;
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

        computed: {
            sumSale(){
                return this.order.realizations.reduce((prev, curr) => {
                    let price = curr.reason_refusal_id === null ? parseInt(curr.price) : 0;
                    return prev + (isNaN(price) ? 0 : price);
                }, 0);
            },
            sumPurchase(){
                return this.order.realizations.reduce((prev, curr) => {
                    let price = curr.reason_refusal_id === null ? parseInt(curr.price_opt) : 0;
                    return prev + (isNaN(price) ? 0 : price);
                }, 0);
            },

            sumProfit(){
                let payment = parseInt(this.order.courier_payment);
                return this.sumSale - this.sumPurchase - (isNaN(payment) ? 0 : payment);
            }
        },
        watch: {
            sumProfit: _.throttle (function(newSum, oldOld){
                if(newSum < this.minMargin){
                    toast.warning(`Прибыль заказа меньше минимальной!`, {title: 'Внимание!', timeout: 2500});
                }
            }, 2000),
        },
    }
</script>
