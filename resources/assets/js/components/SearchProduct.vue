<template>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Добавление товаров</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <label for="name" class="col-sm-2">Найти товар</label>

                <div class="col-sm-6">
                    <v-select label="product_name" v-model="selectedProduct" :filterable="false" :options="productSearch" @search="onSearchProduct"></v-select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" @click.prevent="addProduct()">
                        <i class="fa fa-plus"></i>Добавить
                    </button>
                </div>
            </div>
            <br/>
            <div class="row" v-show="showCreateProduct">
                <label for="name" class="col-sm-2 control-label">Создать новый товар</label>

                <div class="col-sm-4">
                    <input type="text" class="form-control" name="new_product" v-model="newProductName.product_name">
                </div>
                <div class="col-sm-2">
                    <v-select label="desc"
                              v-model="newProductName.type"
                              :options="initial_product_types"
                              index="name"
                              :searchable="false">
                    </v-select>
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-primary" @click.prevent="createProduct()" :disabled="btnCreateProduct">
                        <i class="fa fa-shopping-bag"></i>Создать товар
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    let emptyProduct = {id: null, product: {}, supplier_in_order: {},  courier_payment: 0, delta: 0, imei: '',  order_id: 0, price: 0, price_opt: 0, product_id: null, quantity: 1};
    let newProduct = {product_name: null, type: null};

    export default {
        props: {
            initial_product_types: Array
        },
        data() {
            return {
                productSearch: [],
                selectedProduct: null,
                showCreateProduct: false,
                newProductName: null,
            }
        },
        methods: {
            addProduct(prod = _.cloneDeep(emptyProduct)) {
                if (this.selectedProduct) {
                    prod.product.product_name = this.selectedProduct.product_name;
                    prod.product.id = this.selectedProduct.id;
                    prod.product_id = this.selectedProduct.id;

                    this.selectedProduct = null;
                    this.showCreateProduct = false;

                    this.$emit('addproduct', prod);
                }
            },

            onSearchProduct: _.debounce(async function(search) {
                if(search.length > 2) {
                    let response = await axios.post('/product-search', {query: search}).catch(function(e){
                        toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                        throw Error(e);
                    });
                    this.productSearch =  response.data.products;
                    if (this.productSearch.length === 0) {
                        this.showCreateProduct = true;
                    }
                }
            }, 250),

            async createProduct(){
                let response = await axios.post('/product-create', this.newProductName).catch(function(e){
                    toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                    throw Error(e);
                });
                let prod = _.cloneDeep(emptyProduct);

                prod.product.product_name = response.data.product.product_name;
                prod.product.product_id = response.data.product.id;
                prod.product_id = response.data.product.id;

                this.$emit('addproduct', prod);

                this.showCreateProduct = false;
                this.newProductName = {...newProduct};

                toast.success(response.data.message);
            }
        },
        created(){
            this.newProductName = {...newProduct};
        },

        computed: {
            btnCreateProduct(){
                return ! (this.newProductName.product_name && this.newProductName.type !== null);
            }
        }
    }
</script>