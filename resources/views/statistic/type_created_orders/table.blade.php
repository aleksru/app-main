<?php
/**
 * @var App\Services\Statistic\Abstractions\BaseReportTableRender $DTRender
 */
?>

@include('datatable.datatable', [
    'id' => "table-{$render->getName()}",
    'route' => $render->getRouteDatatable(),
    'columns' => $render->getColumns()
])


@push('scripts')
    <script>
        $(function () {
            let table = $("#{{'table-' . $render->getName()}}").DataTable();
            let inputDateFrom = $("#report-dates input[name='dateFrom']");
            let inputDateTo = $("#report-dates input[name='dateTo']");
            let selectedStore = $("#report-dates select[name='store_id']").val();


            let url = new URL(window.location);
            let dateFrom = url.searchParams.get("dateFrom");
            let dateTo = url.searchParams.get("dateTo");
            if(dateFrom !== null){
                inputDateFrom.val(dateFrom);
            }
            if(dateTo !== null){
                inputDateTo.val(dateTo);
            }

            if((dateTo !== null ||  dateFrom !== null) && selectedStore){
                send();
            }
            console.log(selectedStore);

            function send() {
                exchangeData.dateFrom = (inputDateFrom.val() === undefined) ? dateFrom : inputDateFrom.val();
                exchangeData.dateTo = (inputDateTo.val() === undefined) ? dateTo : inputDateTo.val();
                exchangeData.store_id = selectedStore;
                table.draw();
            }
        })

    </script>
@endpush
