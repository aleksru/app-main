@extends('layouts.adminlte.app')
@section('content_header')
    <section class="content-header">
        <h1>
            Чат - {{$chat->name}}
        </h1>
        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
        {{--<li class="active">Here</li>--}}
        {{--</ol>--}}
    </section>
@endsection
@section('content')
    <div class="col-md-6">
        <chat
            :init_user="{{\Illuminate\Support\Facades\Auth::user()}}"
            :init_messages='@json($messages)'
            :init_chat_id="{{$chat->id}}">
        </chat>
    </div>
@endsection