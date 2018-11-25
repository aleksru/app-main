
<script>

    let emptyProduct = {'product_name': '', pivot:{'courier_payment': 0, 'delta': 0, 'imei': '',  'order_id': 0, 'price': 0, 'price_opt': 0, 'product_id': 0, 'quantity': 0}};

    export default {
        props: {
            initial_data: Array,
            initial_order: ''
        },
        data() {
            return {
                products: Array,
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

            deletePeriod(index){

//                if(!this.periods[index].id){
//                    return this.periods.splice(index, 1);
//                }
//
//                let id      = this.periods[index].id;
//                let name    = this.periods[index].period;
//
//                let toastID = 'toast-delete-' + id;
//
//                toast.confirm('Вы действительно хотите удалить элемент "' + name + '"?', () => {
//                    let loading = toast.loading('Идет удаление "' + name + '"');
//                    axios.delete('delivery-periods/'+ id)
//                        .then((response) => {
//                            toast.hide(loading);
//                            this.periods.splice(index, 1);
//                            toast.success(response.data.message);
//                        })
//                        .catch((error) => {
//                            console.log(error);
//                            toast.hide(loading);
//                            toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
//                        })
//                }, null, { id: toastID });
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
                this.products.push(prod);
            }
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