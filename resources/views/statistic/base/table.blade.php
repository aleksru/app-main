<?php
/**
 * @var App\Services\Statistic\Abstractions\BaseReportTableRender $DTRender
 */
?>

@include('statistic.base.datatable', [
    'id' => "table-{$render->getName()}",
    'route' => $render->getRouteDatatable(),
    'columns' => $render->getColumns()
])


@push('scripts')
    <script>
        $(function () {
            let table = $("#{{'table-' . $render->getName()}}").DataTable();
            new $.fn.dataTable.FixedColumns( table, {
                fixedColumns: true
            } );
            $("#{{'table-' . $render->getName()}}").on( 'init.dt column-sizing.dt', function () {
                let top = $('.dataTables_scrollHead').height() - $('.dataTables_scrollHeadInner').height() - 6;
                $('.DTFC_LeftBodyWrapper .DTFC_LeftBodyLiner').css('top', top);
            });
            let inputDateFrom = $("#report-dates input[name='dateFrom']");
            let inputDateTo = $("#report-dates input[name='dateTo']");

            let url = new URL(window.location);
            let dateFrom = url.searchParams.get("dateFrom");
            let dateTo = url.searchParams.get("dateTo");
            if(dateFrom !== null){
                inputDateFrom.val(dateFrom);
            }
            if(dateTo !== null){
                inputDateTo.val(dateTo);
            }

            if(dateTo !== null ||  dateFrom !== null){
                send();
            }

            function send() {
                exchangeData.dateFrom = (inputDateFrom.val() === undefined) ? dateFrom : inputDateFrom.val();
                exchangeData.dateTo = (inputDateTo.val() === undefined) ? dateTo : inputDateTo.val();
                table.draw();
            }

            $("#{{'table-' . $render->getName()}}").on( 'draw.dt', function () {
                table.columns('.sum').every(function () {
                    let sum = this.data().reduce(function (a, b) {
                        if(typeof a === "string"){
                            a =  a.replace(/\s+/g, '');
                        }
                        if(typeof b === "string"){
                            b =  b.replace(/\s+/g, '');
                        }

                        let x = parseInt(a) || 0;
                        let y = parseInt(b) || 0;
                        return x + y;
                    }, 0);
                    $(this.footer()).html(sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
                });
                table.columns('.avg').every(function () {
                    let count = this.data().length;
                    let sum = this.data().reduce(function (a, b) {
                        if(typeof a === "string"){
                            a =  a.replace(/\s+/g, '');
                        }
                        if(typeof b === "string"){
                            b =  b.replace(/\s+/g, '');
                        }

                        let x = parseInt(a) || 0;
                        let y = parseInt(b) || 0;
                        return x + y;
                    }, 0);
                    let result = (sum / count).toFixed(1);
                    $(this.footer()).html(result.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
                });
            });
        })

    </script>
@endpush
