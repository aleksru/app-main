<div class="callout callout-info">
    @foreach($periods as $period)
        <span class="badge @if($period->failDeliveryDate->isEmpty())bg-green @else bg-red @endif"
              style="font-size: 15px;margin-left: 5%; padding: 10px">
            {{$period->period ?? $period->name}}
        </span>
    @endforeach
</div>



