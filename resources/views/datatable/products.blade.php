@foreach ($products as $product)
    {{ ($product['quantity'] ?? '')."-".$product['name']."-".$product['price']."р." }} <br>
@endforeach

