
<div title="{{implode("\n", $products)}}"
     style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;max-width:150px"
>
    @foreach($products as $product)
        @if($loop->iteration > 3)
            @break
        @endif
        {{ $product }}<br/>
    @endforeach
</div>
