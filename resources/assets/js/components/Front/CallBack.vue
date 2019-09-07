<template>
    <div class="btn-group">
        <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-phone" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu">
            <li v-for="(phone, index) in phones" role="presentation" @click.prevent="process(index)">
                <a role="menuitem" tabindex="-1" href="#">
                    <i class="fa fa-phone" aria-hidden="true"></i>{{phone}}
                </a>
            </li>
        </ul>
    </div>
</template>

<style scoped>
    button .fa.fa-phone{
        font-size: 200%;
    }
    .fa.fa-phone{
        color: green;
    }
</style>

<script>
    export default {
        props: {
            phones: Array,
            operator: Object,
            store: Object
        },

        data() {
            return {
                commandId: null,
                toastLoadingId: null,
            }
        },

        methods: {
            process(index){
                this.send(index).then((res) => {
                    this.commandId = res.data.command_id;
                    this.toastLoadingId = toast.loading('', {title: 'Соединение...'});
                    this.listenChannelCallResult();
                }).catch((err) => {
                    toast.hide(this.toastLoadingId);
                    console.log(err);
                    if(err.response.data.message){
                        toast.error(err.response.data.message, {timeout: 10 * 1000});
                    }
                })
            },

            onResult(e){
                toast.hide(this.toastLoadingId);
                toast.success(e.result, {timeout: 10 * 1000});
                this.leaveChannelCallResult();
            },

            async send(index){
                let res = await axios.post(`/callback/${this.operator.id}`, {
                    phone: this.phones[index],
                    store_phone: this.store ? this.store.phone : null,
                });

                return res;
            },

            listenChannelCallResult(){
                window.Echo.private(this.channel)
                    .listen('ResultCallBack', this.onResult);
            },

            leaveChannelCallResult(){
                window.Echo.leaveChannel(this.channel);
            },

        },

        computed: {
            channel(){
                return `operator-callback.${this.commandId}`;
            }

        }
    }
</script>