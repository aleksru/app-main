

<script>
    let formClient = document.getElementById("user-form");
    let formCommentLogist = document.getElementById("logist-comment-form");
    let formMainOrder = document.getElementById("order-form");

    export default {
        props:{

        },
        data() {
            return {
                count_form: 4,
                success_form: 0,
                is_update_comment_logist: false,
            }
        },
        methods: {
            //определяет порядок отправки форм
            async submitForms(){
                this.clearBlockInfo();
                $('#order_info_modal').modal('toggle');
                this.success_form = 0;

                //обновление товаров
                await this.$refs.ProductsTable.submit().then((res) => {
                    this.addBlockInfo(this.getInfoSuccessItem(res.success));
                    this.setProgressBar();
                });

                //обновление клиента
                await this.sendForm(formClient.action,
                                new FormData(document.getElementById("user-form"))).then((res) => {
                    this.addBlockInfo(this.getInfoSuccessItem(res.success));
                    this.setProgressBar();
                });

                //коммент логиста
                if(this.is_update_comment_logist){
                    await this.sendForm(formCommentLogist.action,
                                new FormData(document.getElementById("logist-comment-form"))).then((res) => {
                        this.addBlockInfo(this.getInfoSuccessItem(res.success));
                        this.setProgressBar();
                    });
                }

                //обновление заказа
                await this.sendForm(formMainOrder.action,
                                new FormData(document.getElementById("order-form"))).then((res) => {
                    this.addBlockInfo(this.getInfoSuccessItem(res.success));
                    this.setProgressBar();
                });
            },

            //отправка формы
            sendForm: async (url, formData) => {
                let res =  await axios.post(url, formData);
                return res.data;
            },

            getInfoSuccessItem(text){
                let div = document.createElement('div');
                div.className = "alert alert-success";
                div.innerHTML = `<i class="fa fa-check" aria-hidden="true"></i>${text}`;

                return div;
            },

            getInfoErrorItem(text){
                let div = document.createElement('div');
                div.className = "alert alert-danger";
                div.innerHTML = `<i class="fa fa-times-circle" aria-hidden="true"></i>${text}`;

                return div;
            },

            addBlockInfo(node){
                document.getElementById('order_info_modal')
                        .querySelector('.modal-body')
                        .appendChild(node);
            },

            clearBlockInfo(){
                document.getElementById('order_info_modal')
                        .querySelector('.modal-body')
                        .innerHTML = '';
            },

            setProgressBar(){
                this.success_form++;
                document.getElementById('order_info_modal')
                        .querySelector('.progress-bar')
                        .style
                        .width = this.success_form * 100 / this.count_form + '%';
            },

            sendProcess(){
                this.submitForms().then(() =>{
                    toast.success('Заказ успешно обновлен!');
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }).catch((err) => {
                    console.log(err);
                    toast.error('Ошибка сервера! Проверьте заполение форм или свяжитесь с администратором.');
                    if(!err.response.data.errors){
                        this.addBlockInfo(this.getInfoErrorItem('Ошибка обновления. Проверьте форму и попробуйте еще раз'));
                    }else {
                        let errors = '';
                        for(let error in err.response.data.errors){
                            errors += ' ' + err.response.data.errors[error].toString() + ', ';
                        }
                        this.addBlockInfo(this.getInfoErrorItem(errors));
                    }
                });
            }
        },

        mounted() {
            document.getElementById('send_all_forms').addEventListener('click', this.sendProcess);
            document.getElementById('send_all_forms').addEventListener('touchstart', this.sendProcess);
            this.is_update_comment_logist = !document.querySelector('[name=comment_logist]').disabled;
            if(!this.is_update_comment_logist){
                this.count_form--;
            }
        },
    }
</script>