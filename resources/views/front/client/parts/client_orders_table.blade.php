@if( ! empty($orders) )
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>#</td>
                        <td>Статус</td>
                        <td>Создан</td>
                        <td>Обновлен</td>
                    </tr>
                    @foreach($orders as $order)
                        <tr>
                            <td><a href=" {{ route('orders.edit', $order->id) }}" target="_blank"> {{ $order->id }}</a></td>
                            <td>{{ $order->status ? $order->status->status : '' }}</td>
                            <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
