<div class="box box-solid">
    <div class="box-body">
        <h4> Клиент <a href="{{ route('clients.show', $client->id) }}"><i class="fa fa-id-card" aria-hidden="true"></i></a> </h4>

        <form id="user-form" role="form" method="post" class="form-horizontal" action="{{ isset($client) ? route('clients.update', $client->id) :  route('clients.store') }}">
        {{ csrf_field() }}

        @if (isset($client))
            {{ method_field('PUT') }}
        @endif

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Имя</label>

            <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{ $client->name ?? '' }}" name="name">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Телефон</label>

            <div class="col-sm-10">
                <input type="text" id="phone" class="form-control mask-phone"  value="{{ $client->phone ?? '' }}" name="phone">

            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Описание</label>

            <div class="col-sm-10">
                <textarea class="form-control" rows="2"name="description">{{ $client->description ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Доп телефоны</label>

            <div class="col-sm-4">
                <input type="text" class="form-control mask-phone"  value="" name="additional_phones[new]" placeholder="Добавить новый номер">
            </div>
            @foreach($client->additionalPhones as $additionalPhone)
                <div class="col-sm-4">
                    {{--<input type="text" class="form-control mask-phone"  value="{{ $additionalPhone->phone ?? '' }}" name="additional_phones[{{ $additionalPhone->id }}]">--}}
                    <div class="input-group @if($additionalPhone->main) has-success @endif">
                        <span class="input-group-addon">
                          <input type="checkbox" class="main-check" name="main-phone[{{ $additionalPhone->id }}]" @if($additionalPhone->main) checked @endif>
                        </span>
                        <input type="text" class="form-control mask-phone"  value="{{ $additionalPhone->phone ?? '' }}" name="additional_phones[{{ $additionalPhone->id }}]">
                    </div>
                </div>

            @endforeach
        </div>

        @if (isset($client))
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Звонки</label>

                <div class="col-sm-2">
                    <span class="pull-left badge bg-aqua">{{ $client->calls()->count() ?? 0 }}</span>
                </div>

                <label for="name" class="col-sm-2 control-label">Заказы</label>

                <div class="col-sm-2">
                    <span class="pull-left badge bg-green">{{ $client->orders()->count() ?? 0 }}</span>
                </div>
            </div>
        @endif

        <div class="col-sm-12">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Сохранить
            </button>

        </div>
    </form>
    </div>
</div>

@push('scripts')
    <script>
        $(function(){
            $('.mask-phone').mask('+0 (000) 000-0000',{
                onChange: function(val, e, field){
                    if(val.indexOf('+') >= 0) {
                        val.charAt(val.indexOf('+') + 1) !== 7 ? field.val( val.replace(val.charAt(val.indexOf('+') + 1), 7) )  : '';
                    }
                },
            });

            $('.main-check').click(function(){
                if(!$(this).is(':checked')) {
                    $(this).prop('checked', false);
                    $(this).parent().parent().removeClass('has-success');
                }else{
                    $('.input-group').removeClass('has-success');
                    $('.main-check').prop('checked', false);
                    $(this).prop('checked', true);
                    $(this).parent().parent().addClass('has-success');
                }
            });

        });
    </script>
@endpush