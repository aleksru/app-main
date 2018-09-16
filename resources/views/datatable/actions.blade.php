@if (!empty($edit))
<!--    <a href="{{ $edit['route'] }}" class="btn btn-xs btn-primary">
        <i class="fa fa-pencil"></i> 
    </a>-->
    <form id="set_user" method="post" action="{{ $edit['route'] }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name='user_id' value="1">
    </form>
    <form id="set_complete" method="post" action="{{ $edit['route'] }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name='status' value="1">
    </form>
    <a href="#" class="btn btn-xs btn-warning">
        <i class="fa fa-user-plus" onclick="$('#set_user').submit();"></i> 
    </a>
    <a href="#" class="btn btn-xs btn-success">
        <i class="fa fa-check-square-o" onclick="$('#set_complete').submit();"></i> 
    </a>
@endif
<!--@if (!empty($delete))
    <button class="btn btn-xs btn-danger btn-delete" data-route="{{ $delete['route'] }}"
            data-name="{{ $delete['name'] }}" data-id="{{ $delete['id'] }}">
        <i class="fa fa-trash"></i>
    </button>
@endif-->
