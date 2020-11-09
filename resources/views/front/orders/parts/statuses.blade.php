<div class="col-sm-4">
    <label for="name" class="control-label">Статус</label>
    <select class="js-example-basic-single form-control" name="status_id">
        <option value="{{ $order->status->id ?? null }}" selected>{{ $order->status->status ?? 'Не выбран' }}</option>
        <option value="{{ null }}">  </option>
    </select>
</div>

@if($order->status && stripos($order->status->status, 'отказ') !== false)
    <div class="col-sm-4">
        <label for="name" class="control-label">Причина отказа</label>
        <select class="js-example-reasons-single form-control" name="denial_reason_id">
            <option value="{{ $order->denialReason->id ?? null }}" selected>{{ $order->denialReason->reason ?? 'Не выбран' }}</option>
            <option value="{{ null }}">  </option>
        </select>
    </div>
@endif

<div class="col-sm-4">
    <label for="name" class="control-label">Подстатус</label>
    <select class="js-example-substatus-single form-control" name="substatus_id">
        <option value="{{ null }}"></option>
    </select>
</div>

@push('scripts')
<script>
    let statuses = {!!   json_encode($operatorStatuses) !!};
    let substatus = {!!  json_encode($order->subStatus) !!};
    let substatuses = {!!  json_encode($order->status ? $order->status->subStatuses : []) !!};

   for(let sub of substatuses) {
       sub.text = sub.name;
       if(substatus && sub.id == substatus.id){
           sub.selected = true;
       }
   }

    $(function() {
        $('.js-example-basic-single').select2({
            data: statuses,
            allowClear: true,
            placeholder: "Выберите статус...",
        });
        $('.js-example-substatus-single').select2({
            data: substatuses,
            allowClear: true,
            placeholder: "Выберите подстатус...",
        });

        $('.js-example-basic-single').on('select2:clear', function (e){
            $('.js-example-substatus-single').empty();
            let newOption = new Option('', '', false, false);
        });

        $('.js-example-basic-single').on('select2:select', function (e) {
            axios.get(`/statuses/substatuses/${e.params.data.id}`)
                .then((res) => {
                    $('.js-example-substatus-single').empty();
                    let newOption = new Option('', '', false, false);
                    $('.js-example-substatus-single').append(newOption);
                    for(let sub of res.data.data) {
                        let newOption = new Option(sub.name, sub.id, false, false);
                        $('.js-example-substatus-single').append(newOption);
                    }
            }).catch((err) => {
                console.log(err);
            });
        });
    });

</script>
@endpush
