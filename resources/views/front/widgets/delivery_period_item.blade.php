<div class="callout callout-info">
    <span class="badge bg-yellow"
          style="font-size: 18px;padding: 10px">
        {{ $periodText }}:
    </span>
    @foreach($periods as $period)
        <span class="badge @if($period->failDeliveryDate->isEmpty())bg-green @else bg-red @endif"
              style="font-size: 14px; padding: 10px;margin-left: 3px">
            @if($period instanceof \App\Models\DeliveryPeriod)
                {{$period->period_full}}
            @else
                {{$period->name}}
            @endif
        </span>
    @endforeach
</div>