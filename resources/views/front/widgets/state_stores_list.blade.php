@foreach($stateStores as $stateStore)
    <span class="badge bg-navy" style="font-size: 13px;padding: 5px">
        @if($stateStore->isOnline())
            <i class="fa fa-circle text-success"></i>
        @else
            <i class="fa fa-circle text-danger"></i>
        @endif
        {{$stateStore->name}}
    </span>
@endforeach
