@include('datatable.datatable',[
     'id' => $tableName,
     'route' => route('reports.datatable'),
     'disableHeaderScroll' => true,
     'columns' => [
         '[statistic.day.date]' => [
             'name' => 'Дата',
             'width' => '5%',
         ],
        '[statistic.day.count_orders]' => [
             'name' => 'Кол заказов',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.count_new_orders]' => [
             'name' => 'Новых',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.count_confirm_orders]' => [
             'name' => 'Принято',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.approved_main]' => [
             'name' => 'Апрув общий',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.denial_orders]' => [
             'name' => 'Отказы',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.callbacks]' => [
             'name' => 'Перезвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.missed_call]' =>[
             'name' => 'Недозвоны',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.spam]' =>[
             'name' => 'Мусор',
             'width' => '5%',
             'className' => 'sum',
         ],
         '[statistic.day.sum_products]' =>[
             'name' => 'Принято на сумму осн',
             'width' => '10%',
             'className' => 'sum',
         ],
        '[statistic.day.sum_others]' => [
             'name' => 'Принято прочее',
             'width' => '5%',
             'className' => 'sum',
         ],
        '[statistic.day.sum_main]' => [
             'name' => 'Общая сумма',
             'width' => '10%',
             'className' => 'sum',
         ],
     ],
 ])
