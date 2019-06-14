@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Периоды доставки
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{--<div class="alert alert-warning">--}}
        {{--<ul>--}}
            {{--<li></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
    <div class="box-header"></div>
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Дата {{ $selectedDate->format('d.m.Y') }}</h3>
                </div>
                <div class="box-body">
                    <form id="date-form" role="form" method="get" class="form-horizontal" action="{{route('logistics.deliveries')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <input id="input_date" type="date" class="form-control" name="date">
                            </div>
                        </div>
                        <button form="date-form" type="submit" class="btn btn-primary pull-right">
                            Показать
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="delivery_list">
                @include('front.logistic.parts.delivery_list', ['periods' => $periods, 'date' => $selectedDate])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.btn-toggle-delivery').click(process);

            async function process () {
                await sendToggle({
                    'model': this.dataset.model,
                    'id': this.dataset.id,
                    'date': this.dataset.selected_date
                }).then(function (res) {
                    $('.delivery_list').html(res.data.html);
                    $('.btn-toggle-delivery').click(process);
                    toast.success(res.data.message);
                }).catch(function (err) {
                    toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                    console.log(err);
                })
            }

            async function sendToggle(data) {
                return await axios.post("{{route('logistics.delivery.toggle')}}", data);
            }
        });
    </script>
@endpush
