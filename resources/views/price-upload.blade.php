@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (Session::get('message'))
                <div class="alert alert-success" role="alert">
                  <strong>{{Session::get('message')}}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
            @endif
            <div class="card">
                <form action="{{route('upload-price')}}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="exampleFormControlFile1">Загрузка прайс листа</label>
                    <input type="file" class="form-control-file" name="file">  
                  </div>
                     <button class="btn btn-primary" type="submit">Загрузить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

