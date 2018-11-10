@if (!empty($edit))
    <a href="{{ $edit['route'] }}" class="btn btn-xs btn-primary">
        <i class="fa fa-pencil"></i>
    </a>
@endif
@if (!empty($delete))
    <button class="btn btn-xs btn-danger btn-delete" data-route="{{ $delete['route'] }}"
            data-name="{{ $delete['name'] }}" data-id="{{ $delete['id'] }}">
        <i class="fa fa-trash"></i>
    </button>
@endif