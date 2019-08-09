<li class="header">У вас {{$notifications->count()}} уведомлений</li>
<li>
    <!-- Inner Menu: contains the notifications -->
    <ul class="menu">
        <!-- start notification -->
        @foreach($notifications as $notification)
            <li>
                <a href="#" data-id="{{$notification->id}}">
                    <span>{!! $notification->data['content'] !!}</span>
                </a>
            </li>
        @endforeach
        {{--<!-- end notification -->--}}
    </ul>
</li>
{{--<li class="footer"><a href="#">View all</a></li>--}}