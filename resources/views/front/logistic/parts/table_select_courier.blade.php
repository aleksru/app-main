<select name="courier_id" class="form-control select-courier" data-order-id="{{$order->id}}">
    <option value="">Не выбран</option>
    @if($order->courier)
        <option value="{{$order->courier->id}}" selected>{{$order->courier->name}}</option>
    @endif
</select>
