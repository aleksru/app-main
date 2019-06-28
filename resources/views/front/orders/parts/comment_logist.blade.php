<div class="box box-solid">
    <div class="box-body">
        <h4>Комментарий логиста </h4>

        <form id="logist-comment-form" role="form" method="post" class="form-horizontal" action="{{ route('orders.comment-logist', $order->id) }}">
            {{ csrf_field() }}

            <div class="form-group">
                <div class="col-sm-10">
                    <textarea class="form-control" rows="6" name="comment_logist"
                              @cannot('commentLogist', App\Order::class) disabled @endcannot>{{ $order->comment_logist ?? '' }}</textarea>
                </div>
            </div>

            {{--@can('commentLogist', App\Order::class)--}}
                {{--<div class="col-sm-12">--}}
                    {{--<button form="logist-comment-form" type="submit" class="btn btn-primary pull-right">--}}
                        {{--<i class="fa fa-save"></i> Сохранить--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--@endcan--}}
        </form>
    </div>
</div>