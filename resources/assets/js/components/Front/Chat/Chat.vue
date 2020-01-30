<template>
    <div class="box box-primary direct-chat direct-chat-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Direct Chat</h3>
        </div>
        <div class="box-body" style="">
            <div class="direct-chat-messages">
                <div v-for="(message) in messages" :class="'direct-chat-msg' + (message.user_id == init_user.id ? ' right' : '')" :key="message.id">
                    <div class="direct-chat-info clearfix">
                        <span :class="'direct-chat-name' + (message.user_id == init_user.id ? ' pull-right' : ' pull-left')">{{message.user.description}}</span>
                        <span :class="'direct-chat-timestamp' + (message.user_id == init_user.id ? ' pull-left' : ' pull-right')">{{dateFormat(message.created_at)}}</span>
                    </div>
                    <div class="direct-chat-text">
                        {{message.text}}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer" style="">
            <form action="#" method="post">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" v-model="text">
                    <span class="input-group-btn">
                        <button :disabled="text == ''" type="submit" class="btn btn-primary btn-flat" @click.prevent.default="sendMessage">Отправить</button>
                      </span>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
    .direct-chat-messages{
        min-height: 500px;
    }
</style>

<script>
    export default {
        props: {
            init_messages: Array,
            init_user: Object,
            init_chat_id: Number
        },

        data() {
            return {
                text: '',
                messages: Array,
            }
        },

        mounted() {
            this.messages = this.init_messages;
            window.Echo.private(`chat.${this.init_chat_id}`)
                .listen('NewChatMessage', (e) => {
                    this.messages.push(e.message);
                });
        },

        updated(){
            this.scrollDown();
        },

        methods: {
            dateFormat(date){
                return window.moment(date).format('DD MMMM YYYY, HH:mm:ss');
            },

            async sendMessage(){
                let res = await this.submit();
                this.text = '';
            },

            async submit(){
                try{
                    let res = await axios.post(`/chat/${this.init_chat_id}/message/create`, {
                        user_id: this.init_user.id,
                        text: this.text
                    });

                    return res;
                }catch(e){
                    console.log(e);
                }
            },

            scrollDown(){
                let body = document.querySelector('.direct-chat-messages');
                body.scrollBy(0, body.scrollHeight);
            }

        },
    }
</script>