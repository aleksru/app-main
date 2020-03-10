<select class="form-control" name="{{$name}}">
    <option value="{{ old('color', $status->color ?? '') }}"
            class="bg-{{ old('color', $status->color ?? '') }}"
            selected>{{ old('color', $status->color ?? '') }}
    </option>
    @foreach(get_class_colors() as $color)
        <option value="{{ $color }}" class="bg-{{$color}}">{{$color}}</option>
    @endforeach
</select>