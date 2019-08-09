@include('datatable.datatable',[
     'id' => $tableName,
     'route' => route('reports.datatable'),
     'disableHeaderScroll' => true,
     'columns' => [
         '[resources.name]' => [
             'name' => 'Источник',
             'width' => '5%',
         ],
        '[resources.count_lid]' => [
             'name' => 'Кол заказов',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.approved_main]' => [
             'name' => 'Апрув1',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.confirm_calls]' => [
             'name' => 'Апрув2',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.missed_calls]' => [
             'name' => 'Недозвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.denials]' => [
             'name' => 'Отказы',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.spam]' =>[
             'name' => 'Мусор',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[resources.avg_check]' =>[
             'name' => 'Ср чек',
             'width' => '5%',
             'className' => 'sum',
         ],
         '[resources.product_sum]' =>[
             'name' => 'Сумма осн',
             'width' => '10%',
             'className' => 'sum',
         ],
         '[resources.other_sum]' =>[
             'name' => 'Сумма Upsale',
             'width' => '5%',
             'className' => 'sum',
         ],
         '[resources.main_sum]' =>[
             'name' => 'Сумма общ',
             'width' => '10%',
             'className' => 'sum',
         ],
     ],
 ])
