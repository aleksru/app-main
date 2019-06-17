<div class="callout callout-info">
    <span class="badge bg-yellow"
          style="font-size: 25px;padding: 10px">
        {{\Carbon\Carbon::today()->format('d.m.Y')}}:
    </span>
    @foreach($periods as $period)
        <span class="badge @if($period->failDeliveryDate->isEmpty())bg-green @else bg-red @endif"
              style="font-size: 15px;margin-left: 5%; padding: 10px">
            {{$period->period ?? $period->name}}
        </span>
    @endforeach
</div>

<div class="callout callout-info">
    <span class="badge bg-yellow"
          style="font-size: 25px;padding: 10px">
        {{\Carbon\Carbon::tomorrow()->format('d.m.Y')}}:
    </span>
    @foreach($periodsTomorrow as $period)
        <span class="badge @if($period->failDeliveryDate->isEmpty())bg-green @else bg-red @endif"
              style="font-size: 15px;margin-left: 5%; padding: 10px">
            {{$period->period ?? $period->name}}
        </span>
    @endforeach
</div>



