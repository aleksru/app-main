@php

    /**
     * Опции инпута
     *
     * @var string $name Название для поля инпута. Обязательное поле.
     * @var boolean $searchable Можно ли искать значения, печатая. По умолчанию true.
     * @var boolean $empty Можно ли использовать пустые значения. По умолчанию false.
     * @var boolean $multiple Можно ли выбирать несколько значений. По умолчанию false.
     *
     * Опции значений
     * @var $value Начальное значение инпута. Может быть любым одиночным значением, либо массивом значений в случае с $multiple = true.
     * @var array $options Массив значений, из которых будет идти выбор.
     * @var string $valueColumn Название свойства объекта/массива, которое будет использоваться в качестве значения в инпуте. По умолчанию id.
     * @var string $labelColumn Название свойства объекта/массива, которое будет использоваться в качестве отображения в инпуте. По умолчанию name.
     * @var boolean $mapLabels Присваивает значениям соответствующие метки из опций. Используется, когда начальные значения передаются без меток.
     *
     * Опции поиска
     * @var string $search Определяет, будет ли использовать ajax поиск. Передается маршрут поиска. Если маршрут не указан, ajax поиск не используется.
     * @var string $queryParam Название параметра поиска, который будет передаваться в запросе. По умолчанию 'query'.
     * @var string $selectParam Название параметра выборки, который будет передаваться в запросе. По умолчанию 'select'.
     * @var string $returnDataColumn Название параметра, в котором будет возвращет результат ajax-поиска. По умолчанию считается, что сразу возвращается массив с результатом.
     */

    $value          = $value ?? null;
    $placeholder    = $placeholder ?? '';
    $simple         = $simple ?? false;
    $multiple       = $multiple ?? false;
    $searchable     = $searchable ?? true;
    $empty          = $empty ?? false;
    $options        = $options ?? [];
    $valueColumn    = $valueColumn ?? 'id';
    $labelColumn    = $labelColumn ?? 'name';
    $mapLabels      = $mapLabels ?? true;
    $search         = $search ?? false;

    // Если поиск идет аяксом, а не из массива опций
    if ($search) {
        $queryParam     = $queryParam ?? 'query';
        $selectParam    = $selectParam ?? 'select';
        $returnDataColumn     = $returnDataColumn ?? null;
    }

    /**
     * Обрабатываем случаи, когда начальное значение идет без колонкци с лейблом, а в опциях лейблы есть.
     * Для таких случаев для всех переданных значений находим нужные лейблы.
     *
     * Не работает с ajax-поиском, т.е. если передан $search! Потому что в таком случае
     * для поиска нужного лейбла нам бы пришлось делать ajax-запросы, а это еще больше логики.
     * Поэтому пока не работает и в случае с ajax-поиском, начальные значения нужно передавать уже с лейблом.
     */
    if (!$simple && !$search && $mapLabels) {
        // Функция для нахождения лейбла
        $findLabel = function ($value) use ($valueColumn, $labelColumn, $options) {
            $newValue = [];
            $newValue[$valueColumn] = $value;
            foreach ($options as $option) {
                if ($option[$valueColumn] == $value)
                    $newValue[$labelColumn] = $option[$labelColumn];
            }

            return $newValue;
        };
        if (!$multiple) {
            if (!isset($value[$labelColumn])) {
                $value = $findLabel($value);
            }
        }
        else {
            foreach ($value as $index => $val) {
                if (!isset($val[$labelColumn])) {
                    $value[$index] = $findLabel($val);
                }
            }
        }
    }

@endphp

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">

    <label for="{{ $name }}" class="col-sm-2 control-label">
        {{ $label ?? '' }}
    </label>

    {{-- Обертка над vue-multiselect, которая делает ajax-запросы --}}
    <vue-select name="{{ $name }}" :original_value="{{ old('vue-select-' . $name, json_encode($value)) }}" :multiple="{{ $multiple ? 'true' : 'false' }}"
                @if (!$simple) value_column="{{ $valueColumn }}" label_column="{{ $labelColumn }}" @else :original_options="{{ json_encode($options) }}" @endif
                @if ($search) search_route="{{ $search }}" query_param="{{ $queryParam }}" select_param="{{ $selectParam }}" return_data_column="{{ $returnDataColumn }}" @endif
                inline-template>

        <div class="col-sm-8" v-cloak>

            {{-- Создаем json инпут для работы old хелпера. Будет не очень хорошо, если данные собьются при ошибке валидации. --}}
            <input type="hidden" name="vue-select-{{ $name }}" :value="JSON.stringify(value)">

            {{-- Создается либо один скрытый инпут для одиночного выбора --}}
            <input v-if="!this.multiple" class="form-control" :name="name" :value="getOptionValue(value)" type="hidden">
            {{-- Либо создается массив инпутов для множественного выбора --}}
            <div v-if="this.multiple">
                <input v-for="singleValue in value" type="hidden" :name="name + '[]'" :value="getOptionValue(singleValue)">
            </div>

            {{-- Настроенный vue-multiselect --}}
            <vue-multiselect v-model="value" placeholder="{{ $placeholder }}" open-direction="bottom" :show-no-results="false"
                             deselect-label="Удалить" selected-label="Выбрано" select-label="Enter для выбора"
                             @if (!$simple) track-by="{{ $valueColumn }}" label="{{ $labelColumn }}" @endif
                             @if (!$searchable) :searchable="false" @endif
                             @if (!$empty) :allow-empty="false" @endif
                             @if ($multiple) :multiple="true" :close-on-select="false" @endif
                             @if ($search) :options="options" :internal-search="false" :loading="isLoading" @search-change="search" :options-limit="10"
            @else :options="{{ json_encode($options) }}" @endif
            >

            {{-- Рендерим нужный нам темплейт --}}
            <template slot="tag" slot-scope="props">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary btn-flat" @click="toggleDisplayedLabel">
                    @if (!$simple)
                    @{{ props.option[displayed_column] }}
                    @else
                    @{{ props.option }}
                    @endif
                    </button>
                    <button type="button" class="btn btn-sm btn-primary btn-flat btn-addon" @click="props.remove(props.option)">
                    <i class="fa fa-remove"></i>
                    </button>
                </div>
            </template>

            </vue-multiselect>

            {{-- Стандартный спан с ошибкой --}}
            @if ($errors->first($name))
                <span class="glyphicon form-control-feedback" class="glyphicon-remove"></span>
                <span class="help-block">
                <strong class="text-danger">{{ $errors->first($name) }}</strong>
            </span>
            @endif

        </div>

    </vue-select>
</div>