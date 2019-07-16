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
             'className' => 'sum',
         ],
        '[products.approved_main]' => [
             'name' => 'Апрув1',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.confirm_calls]' => [
             'name' => 'Апрув2',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.missed_calls]' => [
             'name' => 'Недозвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.denials]' => [
             'name' => 'Отказы',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.spam]' =>[
             'name' => 'Мусор',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[products.price]' =>[
             'name' => 'Цена(ср)',
             'width' => '5%',
             'className' => 'sum',
         ],
         '[products.sum]' =>[
             'name' => 'Сумма общ',
             'width' => '10%',
             'className' => 'sum',
         ],
     ],
 ])

