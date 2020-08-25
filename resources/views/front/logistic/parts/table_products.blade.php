@foreach($products as $product)
    {{ strlen($product) > 50 ? (substr($product, 0, 50) . '...') : $product }}<br/>
@endforeach
