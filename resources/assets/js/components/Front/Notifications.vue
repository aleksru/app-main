<template>
    <li class="dropdown notifications-menu" @click="onNotifications">
        <!-- Menu toggle button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">{{countNotifications}}</span>
        </a>
        <ul class="dropdown-menu">
            <li class="header">{{ countStrFormatter }}</li>
            <li>
                <ul class="menu">
                    <li v-for="(notify, index) in listNotifications"
                        @click.prevent="onSetReadNotification(notify.id, index)"
                        :key="notify.id">
                        <a href="#">
                            <span v-html="notify.data.content"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            user: Object,
        },

        data() {
            return {
                count: 0,
                listNotifications: [],
            }
        },

        methods: {
            onNotifications(){
                this.getNotifications().then((data) => {
                    this.listNotifications = data.data.content;
                    this.count = this.listNotifications.length;
                }).catch((err) => {
                    console.log(err);
                });
            },

            onSetReadNotification(id, index){
                this.setReadNotify(id).then((data) => {
                    this.count = --this.count;
                    this.listNotifications.splice(index, 1);
                    window.Echo.private(`App.User.${this.user.id}`)
                        .whisper('reading', {
                            id: id
                        });
                }).catch((err) => {
                    console.log(err);
                });
            },

            async getNotifications() {
                let res = await axios.get(`/notifications/user/${this.user.id}/unread`);

                return res;
            },

            async getCountNotifications() {
                let res = await axios.get(`/notifications/user/${this.user.id}/unread/count`);

                return res;
            },

            async setReadNotify(notify_id) {
                let res = await axios.post("/notifications/set-read", {
                    notification_id: notify_id
                });

                return res;
            },
        },

        mounted() {
            this.getCountNotifications().then((data) => {
                this.count = data.data.count;
            }).catch((err) => {
                console.log(err);
            });

            //Пользовательский канал
            window.Echo.private(`App.User.${this.user.id}`)
                .notification((notification) => {
                    console.log(notification);
                    this.count = ++this.count;
                    let text = 'У вас есть новые уведомления!';
                    if(notification.message){
                        text = text + "<br/>" + notification.message;
                    }
                    toast.warning(text, {
                        title: 'Внимание!',
                        timeout: 300 * 1000
                    });
                });

            //Пользовательские события
            window.Echo.private(`App.User.${this.user.id}`)
                .listenForWhisper('reading', (e) => {
                    this.count = --this.count;
                });

            //Если есть группа слушаем канал группы
            if(this.user.group_id) {
                window.Echo.private(`App.Models.UserGroup.${this.user.group_id}`)
                    .notification((notification) => {
                        this.count = ++this.count;
                        toast.warning('У вас есть новые уведомления!', {
                            title: 'Внимание!',
                            timeout: 300 * 1000
                        });
                    });
            }
        },

        computed: {
            countNotifications(){
                this.count < 0 ? this.count = 0 : null;
                return this.count === 0 ? null : this.count;
            },

            countStrFormatter(){
                let text = 'уведомлений';

                switch(this.listNotifications.length) {
                    case 0:
                        return 'У вас нет ' + text;
                    case 1:
                            text = 'уведомление';
                            break;
                    case 2:
                    case 3:
                    case 4:
                        text = 'уведомления';
                        break;
                }

                return `У вас ${this.listNotifications.length} ` + text;
            }
        }
    }
</script>
