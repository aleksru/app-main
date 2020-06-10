<select name="stock_status_id" class="form-control select-stock-statuses" data-order-id="{{$order->id}}">
    <option value="">Не выбран</option>
    @foreach($stockStatuses as $stockStatus)
        <option value="{{$stockStatus->id}}"
                @if($order->stock_status_id == $stockStatus->id) selected @endif>{{$stockStatus->name}}</option>
    @endforeach
</select>
