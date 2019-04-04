@include('datatable.datatable',[
     'id' => $tableName,
     'route' => route('reports.datatable'),
     'columns' => [
         '[products.name]' => [
             'name' => 'Товар',
             'width' => '5%',
         ],
        '[products.count_lid]' => [
             'name' => 'Кол заказов',
             'width' => '5%',
         ],
        '[products.approved_main]' => [
             'name' => 'Апрув1',
             'width' => '5%',
         ],
        '[products.confirm_calls]' => [
             'name' => 'Апрув2',
             'width' => '5%',
         ],
        '[products.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
         ],
        '[products.missed_calls]' => [
             'name' => 'Недозвоны',
             'width' => '5%',
         ],
        '[products.denials]' => [
             'name' => 'Отказы',
             'width' => '5%',
         ],
        '[products.spam]' =>[
             'name' => 'Мусор',
             'width' => '5%',
         ],
        '[products.price]' =>[
             'name' => 'Цена(ср)',
             'width' => '5%',
         ],
         '[products.sum]' =>[
             'name' => 'Сумма общ',
             'width' => '5%',
         ],
     ],
 ])

