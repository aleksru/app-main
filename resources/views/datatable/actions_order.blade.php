
<a href="{{ route('orders.edit', $order->id) }}" target="_blank">
    <i class="fa fa fa-sign-in btn btn-xs btn-success"></i>
</a>
{{--@if($order->views->count() > 0)--}}
    {{--<i class="fa fa-eye" aria-hidden="true" title="{{$order->views->implode('name', ', ')}}"></i>--}}
{{--@endif--}}

{{--<button class="btn btn-xs btn-warning btn-status" data-route="{{ route('orders.set-status', $order->id) }}"--}}
        {{--data-name="{{ $order->id }}" data-id="{{ $order->id }}">--}}
    {{--<i class="fa fa-check"></i>--}}
{{--</button>--}}
