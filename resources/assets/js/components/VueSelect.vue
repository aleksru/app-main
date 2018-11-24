<script>
    import VueMultiselect from 'vue-multiselect';

    export default {
        data() {
            return {
                options: [],
                value: this.original_value,
                isLoading: false,
                displayed_column: this.label_column
            }
        },
        props: ['name', 'multiple', 'original_value', 'value_column', 'label_column', 'search_route', 'query_param', 'select_param', 'return_data_column'],

        components: {
            VueMultiselect
        },

        mounted() {
            if (this.label_column) {
                this.displayed_column = this.label_column;
            }
        },

        methods: {
            /**
             * Функция поиска.
             * Дебаунсим юзер-инпут, после дебаунса делаем поиск.
             */
            search: _.debounce(async function (query) {

                if (query.length < 3)
                    return;

                this.isLoading = true;
                try {
                    let columns = this.value_column != this.label_column ? this.value_column + ',' + this.label_column : this.value_column;
                    let response = await axios.post(this.search_route, { [this.query_param]: query, [this.select_param]: columns });
                    this.options = this.return_data_column ? response.data[this.return_data_column] : response.data;
                }
                catch (e) {
                    console.log(e);
                }
                this.isLoading = false;
            }, 300),

            /**
             * Возвращает самое значение в простом случае, либо свойста колонки значения в случае объекта.
             *
             * @param value
             * @returns {*}
             */
            getOptionValue(value) {
                if (this.value_column)
                    if (value)
                        return value[this.value_column];

                return value;
            },

            /**
             * Переключает отображаемые значения (лейбл или значение)
             */
            toggleDisplayedLabel() {
                if (this.value_column && this.label_column) {
                    if (this.displayed_column == this.value_column) {
                        this.displayed_column = this.label_column;
                    }
                    else {
                        this.displayed_column = this.value_column;
                    }
                }

            }
        },
    }
</script>