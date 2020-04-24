<?
/**
 * @var \App\Services\Docs\Courier\RouteListData $routeListData
 */
?>

<div class="main" style="
  width: 1200px;
  margin: 0 auto;
  font-size: 14px;
  font-family: DejaVu Sans, sans-serif !important;
">
    <br/>

    <div style="font-weight: bold; font-size: 12pt; padding-left:5px;">
        <div></div>
        <div style="font-weight:bold ">
            <span>Акт приёма-передачи материальных ценностей от {{$routeListData->getDateDelivery()}} </span>
        </div>
    </div>
    <div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

    <table width="100%" style="margin-bottom: 10px">
        <tr>
            <td style="width: 30mm; vertical-align: top;">
                <div style=" padding-left:2px; ">Продавец: </div>
            </td>
            <td>
                <div style="padding-left:2px;">
                    {{$routeListData->getCorporateInfo()}}
                </div>
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-bottom: 10px">ФИО курьера: {{ $routeListData->getCourier() }}</div>

    <table border="2" width="100%" cellpadding="2" cellspacing="2" style="border-collapse: collapse; width: 100%; font-size: 10px">
        <thead>
        <tr>
            <th style="width:2mm; ">№ п.п.</th>
            <th style="width:13mm; ">ФИО клиента</th>
            <th style="width:3mm; ">Время доставки</th>
            <th style="width:20mm; ">Адрес доставки</th>
            <th style="width:13mm; ">Контактный телефон</th>
            <th style="width:13mm; ">Наименование товара</th>
            <th style="width:13mm; ">IMEI</th>
            <th style="width:9mm; ">Стоимость товара</th>
            <th style="width:13mm; ">Комментарий оператора</th>
            <th style="width:13mm; ">Примечание</th>
        </tr>
        </thead>
        <tbody >

        @foreach($routeListData->getOrders() as $order)

            <tr>
                <td style="width:2mm;text-align: center; ">{{$loop->index + 1}}</td>
                <td style="width:10mm;text-align: center; ">{{ $order->client->name }}</td>
                <td style="width:3mm;text-align: center; ">{{$order->deliveryPeriod ? $order->deliveryPeriod->period_text : ''}}</td>
                <td style="width:20mm;text-align: center; ">{{$order->full_address}}</td>
                <td style="width:12mm; text-align: center;">{!!$order->client->all_phones->implode('<br/>')!!}</td>
                <td style="width:27mm; text-align: center; ">
                    @foreach($order->realizations as $realization)
                        {{$realization->product->product_name}} <br/>
                    @endforeach
                </td>
                <td style="width:27mm; text-align: center; ">
                    @foreach($order->realizations as $realization)
                        {{$realization->imei ?? ''}} <br/>
                    @endforeach
                </td>
                <td style="width:15mm; text-align: center; ">
                    @foreach($order->realizations as $realization)
                        {{$realization->price ?? ''}} <br/>
                    @endforeach
                </td>
                <td style="width:17mm; ">{{$order->comment}}</td>
                <td style="width:17mm; "></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br />
    <table width="100%" style="margin-bottom: 10px">
        <tr>
            <td style="width: 70mm; ;font-weight: bold">
                Отправление сдал _____________
            </td>
            <td style="width: 70mm; font-weight: bold">
                Отправление принял _______________
            </td>
            <td style="width: 130mm; font-size: 10px">
                Данной подписью подтверждаю достоверность предоставленных данных обязательств по текущему трудовому договору и исполнение своих обязанностей по текущему договору  и договору о полной материальной ответственности. Материальные ценности по вышеперечисленным отправлениям были приняты в полном соответствии внешнему виду и комплектации. Товарный вид сохранен. Обязуюсь доставить отправления адресатам точно в срок и вернуть суммы по чекам либо материальные ценности.
            </td>
        </tr>
    </table>
</div>
