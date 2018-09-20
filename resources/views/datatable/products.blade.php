@foreach ($products as $product)
    {{$product['articul']."-".$product['name']."-".$product['price']."р."}} <br>
@endforeach

