<div class="row">
    @foreach($periods as $period)
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon @if($period->failDeliveryDate->isEmpty())bg-green @else bg-red @endif">
                    @if($period->failDeliveryDate->isEmpty())
                        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                    @else
                        <i class="fa fa-times" aria-hidden="true"></i>
                    @endif
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{$period->period ?? $period->name}}</span>
                    <div class="btn-group">
                        <button type="button"
                                class="btn
                                        @if($period->failDeliveryDate->isEmpty())btn-danger @else btn-success @endif
                                        btn-toggle-delivery"
                                data-model="{{get_class($period)}}"
                                data-id="{{$period->id}}"
                                data-selected_date="{{$date->format('Y-m-d')}}">
                            @if($period->failDeliveryDate->isEmpty()) Остановить @else Возобновить @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
