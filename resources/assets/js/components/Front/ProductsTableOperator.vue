<template>
    <div>
        <search-product v-if="show_search"
                        @addproduct="addProduct($event)"
                        :initial_product_types="initial_product_types"
        ></search-product>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Товары</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 1000px;">

                        <div class="input-group-btn">
                            <div class="col-sm-12">
                                <button class="btn btn-primary pull-right" @click.prevent="localSubmit()">
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
                        <th>Тип</th>
                        <th>Модель</th>
                        <th>Цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(product, index) in products" :key="product.id">
                        <td>{{ index + 1  }}</td>
                        <td>
                            <input disabled type="text" class="form-control" :value="getProductDesc(product.product_type)">
                        </td>
                        <!--//Model-->
                        <td > {{ product.product.product_name }} </td>
                        <td>
                            <!--price-->
                            <input type="number" class="form-control" step="50" min="1" style="width: 100%" v-model="product.price">
                        </td>
                        <td style="width: 5%">
                            <i class="btn btn-danger fa fa-trash" @click.prevent="deleteProduct(index)"></i>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Итого</td>
                        <td>{{ summ_price_products }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            initial_data: Array,
            initial_order: Number,
            initial_price_delivery: {
                type: Number,
                default: 0
            },
            suppliers: Array,
            show_search: {
                type: Boolean,
                default: false
            },
            initial_product_types: Array
        },
        data() {
            return {
                products: Array,
            }
        },
        methods: {
            getProductDesc(type){
                for(let initType of this.initial_product_types){
                    if(initType.name === type){
                        return initType.desc;
                    }
                }
                return '';
            },
            localSubmit(){
                this.submit().then((res) => {
                    toast.success(res.success);
                }).catch((err) => {
                    toast.error('Ошибка при обновлении товаров!');
                    console.log(err);
                });
            },

            addProduct(product){
                this.getPriceFromPriceList(product.product.id).then(res => {
                    if(res != null){
                        product.price = res;
                    }
                    this.products.push(product);
                }).catch(err => {
                    this.products.push(product);
                    console.log(err);
                })
            },

            async getPriceFromPriceList(productId){
                let res = await axios.get(`/orders/${this.initial_order}/price/${productId}`)
                                    .then(res => res.data.price);

                return res;
            },

            submit(){
                for(let i = 0; i < this.products.length; i++){
                    this.products[i].supplier_id = this.products[i].supplier ? this.products[i].supplier.id : null;
                }

                let res = axios.post('/product-orders/' + this.initial_order, {'products': this.products}).then(response => {
                    this.products = response.data.products;
                    return response.data;
                });

                return res;
//                .catch(function(err) {
//                    toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
//                    throw new Error(err);
//                });
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
                let summ = this.initial_price_delivery;

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
