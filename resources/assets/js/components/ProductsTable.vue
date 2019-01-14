
<script>

    let emptyProduct = {id: null, product: {}, supplier_in_order: {},  courier_payment: 0, delta: 0, imei: '',  order_id: 0, price: 0, price_opt: 0, product_id: null, quantity: 1};

    export default {
        props: {
            initial_data: Array,
            initial_order: '',
            initial_price_delivery: 0,
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

            addProduct(prod = _.cloneDeep(emptyProduct)) {
                if (this.selectedProduct) {
                    prod.product.product_name = this.selectedProduct.product_name;
                    prod.product.id = this.selectedProduct.id;
                    prod.product_id = this.selectedProduct.id;

                    this.products.push(prod);
                    this.selectedProduct = null;
                    this.showCreateProduct = false;
                }
            },

            async onSearchProduct(search, loading){
                if(search.length > 2) {
                    let response = await axios.post('/product-search', {query: search});
                    this.productSearch =  response.data.products;

                    if (this.productSearch.length === 0) {
                        this.showCreateProduct = true;
                    }
                }
            },

            async createProduct(){
                if (this.newProductName) {
                    let response = await axios.post('/product-create', {product: this.newProductName});
                    let prod = _.cloneDeep(emptyProduct);

                    prod.product.product_name = response.data.product.product_name;
                    prod.product.product_id = response.data.product.id;
                    prod.product_id = response.data.product.id;

                    this.products.push(prod);
                    this.showCreateProduct = false;
                    this.newProductName = null;

                    toast.success(response.data.message);

                }
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

                return summ;
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