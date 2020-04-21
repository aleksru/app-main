<?
/**
 * @var \App\Services\Docs\Client\VoucherData $voucherData
 * @var \App\Models\Realization $realization
 */
?>

<div class="main" style="
  width: 978px;
  margin: 0 auto;
  font-size: 17px;
  font-family: DejaVu Sans, sans-serif !important;
">
    <br/>

    <div style="font-weight: bold; font-size: 15pt; padding-left:5px;">
        <div></div>
        <div style="text-align: center;">
            <span>Счет № {{$voucherData->getNumberOrder()}} </span><br/>
            <span>Дата доставки {{$voucherData->getDateDelivery()}}</span>
        </div>
    </div>
    <div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

    <table width="100%" style="margin-bottom: 10px">
        <tr>
            <td style="width: 30mm; vertical-align: top;">
                <div style=" padding-left:2px; ">Поставщик:    </div>
            </td>
            <td>
                <div style="padding-left:2px;">
                    {{--ООО "" ИНН , КПП ,<br>--}}
                    {{--<span style="font-weight: normal;">, Российская Федерация, г. , Невский пр-кт, д. лит. ,<br> пом. , тел.: +7() , факс: +7()  </span>--}}
                    {{$voucherData->getCorporateInfo()}}
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 30mm; vertical-align: top;">
                <div style=" padding-left:2px;">Покупатель:    </div>
            </td>
            <td>
                <div style="padding-left:2px;">
                    {{$voucherData->getClientInfo()}}
                    {{--ИП , ИНН 7564644646, КПП 45465446456,<br><span style="font-weight: normal;">213245, Российская Федерация, г. ,  пр-кт, д.151 лит. А,<br> пом. , тел.: +7() , факс: +7() </span>     --}}
                </div>
            </td>
        </tr>
    </table>


    <table border="2" width="100%" cellpadding="2" cellspacing="2" style="border-collapse: collapse; width: 100%;">
        <thead>
        <tr>
            <th style="width:13mm; ">№</th>

            <th>Товары (работы, услуги)</th>
            <th style="width:50mm;">Серийный номер</th>
            <th style="width:20mm;">Кол-во</th>
            <th style="width:17mm;">Ед.</th>
            <th style="width:27mm;">Цена</th>
            <th style="width:27mm;">Сумма</th>
        </tr>
        </thead>
        <tbody >
            @foreach($voucherData->getRealizations() as $realization)
                <tr>
                    <td style="width:13mm; ">{{$loop->index + 1}}</td>
                    <td>{{$realization->getStringProductName()}}</td>
                    <td>{{$realization->imei}}</td>
                    <td style="width:20mm; ">{{$realization->quantity}}</td>
                    <td style="width:17mm; ">Шт.</td>
                    <td style="width:27mm; text-align: center; ">{{$realization->price}}</td>
                    <td style="width:27mm; text-align: center; ">{{$realization->quantity * $realization->price}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="" border="0" width="100%" cellpadding="1" cellspacing="1">
        <tr>
            <td></td>
            <td style="width:47mm; font-weight:bold;  text-align:right;">Итого к оплате:</td>
            <td style="width:27mm; font-weight:bold;  text-align: right;">{{$voucherData->getFullSumRealizations()}} руб.</td>
        </tr>
    </table>
    <div>Продавец______________________/_________________________</div>
    <br />
    {{--<div style="text-align: center;font-size: 12px">УСЛОВИЯ ПРОДАЖИ И ГАРАНТИЙНОГО ОБСЛУЖИВАНИЯ:</div>--}}
    <div class="war-text" style="font-size: 8px;">
        {!! $voucherData->getWarrantyText() !!}
    </div>
    <br />
    {{--<div style="font-size: 10pt;">--}}
        {{--Я, ____________________________________________________________ со свойствами товара, гарантийными условиями и правилами продажи ознакомлен,--}}
        {{--претензий к внешнему виду не имею.--}}
    {{--</div>--}}
    <br /><br />
    <div style="font-size: 10pt;">
        <div>
            <span style="font-weight: bold;">Заказчик</span>
            <span style="font-weight: bold;margin-left: 410px;">Поставщик</span>
        </div>
        <hr style="margin: 0">
       <div style="font-weight: bold;font-style: italic;font-size: 8px; ">
           <span style="margin-left: 100px;">расшифровка подписи</span>
           <span style="margin-left: 130px;">подпись</span>
           <span style="margin-left: 240px;">расшифровка подписи</span>
           <span style="margin-left: 130px;">подпись</span>
       </div>
    </div>
    <br /><br />
</div>
