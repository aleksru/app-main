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
                    <input type="text" class="form-control"  value="{{ $client->name ?? 'Не указано' }}" name="name">
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
                <div class="col-sm-10">
                    <div class="pretty p-default p-curve p-thick" style="font-size: 150%">
                        <input type="hidden" value="0" name="is_black_list" />
                        <input class="form-control" type="checkbox" value="1" name="is_black_list" @if($client->is_black_list)checked @endif />
                        <div class="state p-danger-o">
                            <label>Чёрный список</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Доп телефоны</label>

                <div class="col-sm-4">
                    <input type="text" class="form-control mask-phone"  value="" name="additional_phones[new]" placeholder="Добавить новый номер">
                </div>
                @foreach($client->additionalPhones as $additionalPhone)
                    <div class="col-sm-6">
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
                    <div class="col-sm-2">
                        <span>Звонки</span>
                        <span class="label label-success">{{ $client->calls()->count() ?? 0 }}</span>
                    </div>
                    <div class="col-sm-2">
                        <span>Заказы</span>
                        <span class="label label-success">{{ $client->orders()->count() ?? 0 }}</span>
                    </div>
                    <div class="col-sm-8" style="font-size: 10px;overflow: scroll; max-height: 250px">
                        @include('front.client.parts.client_orders_table', [ 'orders' => $client->orders ])
                    </div>
                </div>
            @endif
        </form>
        <div class="form-group">
            <div class="col-sm-2">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#send-sms-form-modal">
                    <i class="fa fa-envelope" aria-hidden="true"></i> Отправить смс
                </button>
                <div class="modal fade" id="send-sms-form-modal" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Отправить смс</h4>
                            </div>
                            <div class="modal-body">
                                @include('front.client.parts.form_send_sms', ['client' => $client])
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function(){
            $('.mask-phone').mask('00000000000',{
                onComplete: function(val, e, field){
                    if(val.charAt(0) != 7){
                        field.val('7' + val.substr(1));
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