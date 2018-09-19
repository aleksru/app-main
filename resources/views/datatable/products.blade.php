@foreach ($products as $product)
    {{$product['articul']."  ".$product['name']."  ".$product['price']}} <br>
@endforeach

