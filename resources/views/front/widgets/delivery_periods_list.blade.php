
@include('front.widgets.delivery_period_item', [
    'periodText' => \Carbon\Carbon::today()->format('d.m.Y'),
    'periods' => $periods
])
@include('front.widgets.delivery_period_item', [
    'periodText' => \Carbon\Carbon::tomorrow()->format('d.m.Y'),
    'periods' => $periodsTomorrow
])
@include('front.widgets.delivery_period_item', [
    'periodText' => \Carbon\Carbon::today()->addDay(2)->format('d.m.Y'),
    'periods' => $periodsTomorrow2
])




