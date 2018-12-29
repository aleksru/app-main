
<a href="{{ route('orders.edit', $order->id) }}" target="_blank">
    <i class="fa fa fa-sign-in btn btn-xs btn-success"></i>
</a>

<button class="btn btn-xs btn-warning btn-status" data-route="{{ route('orders.set-status', $order->id) }}"
        data-name="{{ $order->id }}" data-id="{{ $order->id }}">
    <i class="fa fa-check"></i>
</button>
