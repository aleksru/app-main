@include('datatable.datatable',[
     'id' => $tableName,
     'route' => route('reports.datatable'),
     'columns' => [
         '[operators.name]' => [
             'name' => 'Оператор',
             'width' => '5%',
         ],
        '[operators.count_lid]' => [
             'name' => 'Кол заказов',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.confirmation_1]' => [
             'name' => 'Подтвержденные',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.missedcall]' => [
             'name' => 'Недозвоны',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.denial]' => [
             'name' => 'Отказы',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.garbage]' => [
             'name' => 'Мусор',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[operators.count_calls]' =>[
             'name' => 'Кол звонков',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.count_time_calls]' =>[
             'name' => 'Длит звонков',
             'width' => '5%',
             'className' => 'sum'
         ],
         '[operators.count_avg_calls]' =>[
             'name' => 'Ср вр звонка',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.avg_check]' => [
             'name' => 'Средний чек',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.main_product_sum]' => [
             'name' => 'Основной товар',
             'width' => '10%',
             'className' => 'sum'
         ],
        '[operators.main_other_sum]' => [
             'name' => 'Товар Upsale',
             'width' => '5%',
             'className' => 'sum'
         ],
        '[operators.main_check]' => [
             'name' => 'Общая сумма',
             'width' => '10%',
             'className' => 'sum'
         ],
     ],
 ])