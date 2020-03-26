<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">
        {{ $label }}
    </label>
    <div class="col-sm-10">
        <textarea id="{{ $id }}" name="{{ $name }}" class="rich-editor-{{ $type ?? 'full' }}">
            {{ $slot }}
        </textarea>
    </div>
</div>


@push('scripts')
<script src="{{ asset('assets/vendors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/vendors/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $(function () {
            $('#{{ $id }}').ckeditor();
        })
    </script>
@endpush
