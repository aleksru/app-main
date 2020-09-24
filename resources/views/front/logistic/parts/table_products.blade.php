
<div title="{{implode("\n", $products)}}">
    @foreach($products as $product)
        @if($loop->iteration > 3)
            @break
        @endif
        {{ strlen($product) > 50 ? (substr($product, 0, 50) . '...') : $product }}<br/>
    @endforeach
</div>
