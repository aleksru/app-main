@include('datatable.datatable',[
     'id' => $tableName,
     'route' => route('reports.datatable'),
     'columns' => [
         '[resources.name]' => [
             'name' => 'Источник',
             'width' => '5%',
         ],
        '[resources.count_lid]' => [
             'name' => 'Кол заказов',
             'width' => '5%',
         ],
        '[resources.approved_main]' => [
             'name' => 'Апрув1',
             'width' => '5%',
         ],
        '[resources.confirm_calls]' => [
             'name' => 'Апрув2',
             'width' => '5%',
         ],
        '[resources.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
         ],
        '[resources.missed_calls]' => [
             'name' => 'Недозвоны',
             'width' => '5%',
         ],
        '[resources.denials]' => [
             'name' => 'Отказы',
             'width' => '5%',
         ],
        '[resources.spam]' =>[
             'name' => 'Мусор',
             'width' => '5%',
         ],
        '[resources.avg_check]' =>[
             'name' => 'Ср чек',
             'width' => '5%',
         ],
         '[resources.product_sum]' =>[
             'name' => 'Сумма осн',
             'width' => '5%',
         ],
         '[resources.other_sum]' =>[
             'name' => 'Сумма Upsale',
             'width' => '5%',
         ],
         '[resources.main_sum]' =>[
             'name' => 'Сумма общ',
             'width' => '5%',
         ],
     ],
 ])