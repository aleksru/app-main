@if (!empty($edit))
<!--    <a href="{{ $edit['route'] }}" class="btn btn-xs btn-primary">
        <i class="fa fa-pencil"></i> 
    </a>-->
    <form id="set_user_{{ $orderId }}" method="post" action="{{ $edit['route'] }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name='user_id' value="1">
    </form>
    <form id="set_complete_{{ $orderId }}" method="post" action="{{ $edit['route'] }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name='status' value="1">
    </form>
        <i class="fa fa-user-plus btn btn-xs btn-warning" onclick="$('#set_user_{{ $orderId }}').submit();"></i> 
        <i class="fa fa-check-square-o btn btn-xs btn-success" onclick="$('#set_complete_{{ $orderId }}').submit();"></i> 

@endif
<!--@if (!empty($delete))
    <button class="btn btn-xs btn-danger btn-delete" data-route="{{ $delete['route'] }}"
            data-name="{{ $delete['name'] }}" data-id="{{ $delete['id'] }}">
        <i class="fa fa-trash"></i>
    </button>
@endif-->
