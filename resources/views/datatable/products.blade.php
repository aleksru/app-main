@foreach ($products as $product)
    {{($product['quantity'] ?? '')."-".$product['articul']."-".$product['name']."-".$product['price']."р."}} <br>
@endforeach

