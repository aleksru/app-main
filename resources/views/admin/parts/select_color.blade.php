<select class="form-control" name="{{$name}}">
    <option value="{{ old($name, $status->color ?? '') }}"
            class="bg-{{ old($name, $status->color ?? '') }}"
            selected>{{ old($name, $status->color ?? '') }}
    </option>
    @foreach(get_class_colors() as $color)
        <option value="{{ $color }}" class="bg-{{$color}}">{{$color}}</option>
    @endforeach
</select>