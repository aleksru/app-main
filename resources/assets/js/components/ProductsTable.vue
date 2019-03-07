<template>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Товары</h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 1000px;">

                <div class="input-group-btn">
                    <div class="col-sm-12">
                        <button class="btn btn-primary pull-right" @click.prevent="submit()">
                            <i class="fa fa-save"></i> Сохранить
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Кол-во</th>
                    <th>Модель</th>
                    <th>IMEI</th>
                    <th>Цена</th>
                    <th>Закупка</th>
                    <th>Поставщик</th>
                    <th>Зп Курьера</th>
                    <th>Прибыль</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in products">
                    <td style="width: 2%">{{ index + 1  }}</td>
                    <td style="width: 5%">
                        <!--//quantity-->
                        <input type="number" min="1" style="width: 100%" v-model="products[index].quantity">
                    </td>
                    <!--//Model-->
                    <td style="width: 15%"> {{  products[index].product.product_name }} </td>
                    <td style="width: 10%">
                        <!--//imei-->
                        <input type="text" class="form-control" v-model="products[index].imei">
                    </td>
                    <td style="width: 10%">
                        <!--price-->
                        <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].price">
                    </td>
                    <td style="width: 10%">
                        <!--price_opt-->
                        <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].price_opt">
                    </td>
                    <td style="width: 10%">
                        <v-select label="name"
                                  :options="suppliers"
                                  v-model="products[index].supplier">
                        </v-select>
                    </td>
                    <td style="width: 10%">
                        <!--courier_payment-->
                        <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].courier_payment">
                    </td>
                    <td style="width: 5%">
                        {{  delta(index) }}
                    </td>
                    <td style="width: 5%">
                        <i class="btn btn-danger fa fa-trash" @click.prevent="deleteProduct(index)"></i>
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td></td>
                    <td>Итого</td>
                    <td>{{ summ_price_products }}</td>
                    <td>{{ summ_opt }}</td>
                    <td>  </td>
                    <td> {{ courier_payments }} </td>
                    <td> {{ profit }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


<script>
 export default {
        props: {
            initial_data: Array,
            initial_order: '',
            initial_price_delivery: 0,
            suppliers: Array,
        },
        data() {
            return {
                products: Array,
                productSearch: [],
                selectedProduct: null,
                showCreateProduct: false,
                newProductName: null,
                dataSuppliers: {},
            }
        },
        methods: {
            submit(){
                for(let i = 0; i < this.products.length; i++){
                    this.products[i].supplier_id = this.products[i].supplier ? this.products[i].supplier.id : null;
                }

                axios.post('/product-orders/' + this.initial_order, {'products': this.products}).then(response => {
                    this.products = response.data.products;
                    toast.success(response.data.message);

                }).catch(error => {
                    if (error.response.status === 422) {
                        //this.errors = error.response.data.errors || {};
                    }
                });
            },

            delta(index) {
                return this.products[index].delta = this.products[index].quantity * (this.products[index].price
                                                            - this.products[index].price_opt)
                                                            - this.products[index].courier_payment;
            },

            deleteProduct(index) {
                this.products.splice(index, 1);
            },
        },

        mounted() {
            this.products = this.initial_data;

            VueBus.$on('addProduct', (prod) => {
                this.products.push(prod);
            });
        },

        computed: {
            profit() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += this.products[i].delta;
                    }
                }

                return summ - this.initial_price_delivery;
            },

            courier_payments() {
                let summ = parseInt(this.initial_price_delivery);

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].courier_payment);
                    }
                }

                return summ;
            },

            summ_opt() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].price_opt) * this.products[i].quantity;
                    }
                }

                return summ;
            },

            summ_price_products() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].price)* this.products[i].quantity;
                    }
                }

                return summ;
            },

        }
    }
</script>