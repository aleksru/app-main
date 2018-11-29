
<script>

    let emptyProduct = {id: null, product_name: '', pivot:{courier_payment: 0, delta: 0, imei: '',  order_id: 0, price: 0, price_opt: 0, product_id: 0, quantity: 0}};

    export default {
        props: {
            initial_data: Array,
            initial_order: ''
        },
        data() {
            return {
                products: Array,
                productSearch: [],
                selectedProduct: null,
                showCreateProduct: false,
                newProductName: null,
            }
        },
        methods: {
            submit(){
//                if(!this.dones){
//                    return toast.error('Проверьте все поля!');
//
//                }
                axios.post('/product-orders', {'products': this.products, 'order': this.initial_order}).then(response => {
//                    this.periods = response.data.periods;
//                    toast.success(response.data.message);

                }).catch(error => {
                    if (error.response.status === 422) {
                        //this.errors = error.response.data.errors || {};
                    }
                });
            },

            delta(index) {
                return this.products[index].pivot.delta = this.products[index].pivot.quantity * (this.products[index].pivot.price
                                                            - this.products[index].pivot.price_opt)
                                                            - this.products[index].pivot.courier_payment;
            },

            deleteProduct(index) {
                this.products.splice(index, 1);
            },

            addProduct(prod = _.cloneDeep(emptyProduct)) {
                if (this.selectedProduct) {
                    prod.product_name = this.selectedProduct.product_name;
                    prod.id = this.selectedProduct.id;
                    prod.pivot.product_id = this.selectedProduct.id;
                    prod.pivot.order_id = this.initial_order;

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

                    prod.product_name = response.data.product.product_name;
                    prod.id = response.data.product.id;
                    prod.pivot.product_id = response.data.product.id;
                    prod.pivot.order_id = this.initial_order;

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
                        summ += this.products[i].pivot.delta;
                    }
                }

                return summ;
            },

            courier_payments() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].pivot.courier_payment);
                    }
                }

                return summ;
            },

            summ_opt() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].pivot.price_opt) * this.products[i].pivot.quantity;
                    }
                }

                return summ;
            },

            summ_price_products() {
                let summ = 0;

                for(let i = 0; i < this.products.length; i++){
                    if (this.products[i]) {
                        summ += parseInt(this.products[i].pivot.price)* this.products[i].pivot.quantity;
                    }
                }

                return summ;
            }

        }
    }
</script>