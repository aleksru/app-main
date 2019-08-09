<li class="dropdown notifications-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
    <ul class="dropdown-menu">

    </ul>
</li>

@push('scripts')
    <script>
        $(function () {
            setInterval(processNotifications, 27 * 1000);
            processNotifications();

            async function getNotifications() {
                return axios.get("{{ route('notifications.user', \Illuminate\Support\Facades\Auth::user()->id) }}");
            };

            async function getCountNotifications() {
                return axios.get("{{ route('notifications.user.unread.count', \Illuminate\Support\Facades\Auth::user()->id) }}");
            };

            async function setReadNotify(notify_id) {
                return axios.post("{{route('notifications.set-read')}}", {
                    notification_id: notify_id
                })
            };

            async function processNotifications() {
                let countNotify = await getCountNotifications();
                setCountNotifications(countNotify.data.count);
                if(countNotify.data.message !== null) {
                    toast.warning(countNotify.data.message, {
                        title: 'Внимание!'
                    });
                }
            };

            $('.notifications-menu .dropdown-toggle').click(async function () {
                let res = await getNotifications();
                $('.notifications-menu .dropdown-menu').html(res.data.content);
                $('.notifications-menu .dropdown-menu .menu li a').click(setReadNotification);
            });

            function setCountNotifications(count) {
                count == 0 ? count = '' : null;
                $('.notifications-menu .label-warning').text(count);
                $('.notifications-menu .dropdown-menu .header span').text(count);
            }

            function setReadNotification() {
                setReadNotify(this.dataset.id).then((res) => {
                    this.parentNode.remove();
                    setCountNotifications($('.notifications-menu .dropdown-menu .menu li').length);
                }).catch(function (err) {
                    console.log(err);
                })
            }
        });
    </script>
@endpush
