@foreach ($products as $product)
    {{ ($product['quantity'] ?? '')."-".($product['name'] ?? 'Not Found')."-".($product['price'] ?? '0')."р." }} <br>
@endforeach

