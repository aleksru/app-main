<?
/**
 * @var \App\Services\Docs\Courier\CheckListData $checkListData
 * @var \App\Models\Courier $courier
 */

$courier = $checkListData->getCourier();
?>

<div class="main" style="
  width: 1200px;
  margin: 0 auto;
  font-size: 12px;
  font-family: DejaVu Sans, sans-serif !important;
">
    <br/>
    <br/>

    <div style="font-weight: bold; font-size: 15px; padding-left:5px;">
        <div style="text-align: center;">РАСПИСКА О ПОЛНОЙ МАТЕРИАЛЬНОЙ ОТВЕТСТВЕННОСТИ</div>
        <div>
            <span style="">г.Москва</span>
            <span style="float: right;">Дата {{$checkListData->getStrDateDelivery()}}</span>
        </div>
    </div>

    <table width="100%" style="margin-bottom: 10px">
        <tr>
            <td>
                <div style="padding-left:2px;">
                    Я, {{$courier->name}}, дата рождения {{$courier->birth_day ? $courier->birth_day->format('d.m.Y') : ''}}, паспорт {{$courier->passport_number}},
                    выдан {{$courier->passport_date ? $courier->passport_date->format('d.m.Y') : ''}}, {{$courier->passport_issued_by}},
                    зарегистрирован(а) по адресу: {{$courier->passport_address}}, даю данную расписку, целиком осознавая ее последствия,
                    Генеральному директору ({{$checkListData->getCorporateInfo()}}),
                    действующему на основании Устава, в нижеследующем:
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding-left:2px;">
                    Я, {{$courier->name}}, принимаю на себя полную материальную ответственность за недостачу вверенной мне нижеуказанной продукции:
                </div>
            </td>
        </tr>
    </table>


    <table border="2" width="100%" cellpadding="2" cellspacing="2" style="border-collapse: collapse; width: 100%;">
        <thead>
        <tr>
            <th style="width:13mm; ">№</th>
            <th>Наименование товара</th>
            <th style="width:100mm;">imei</th>
        </tr>
        </thead>
        <tbody >
        @foreach($checkListData->getRealizations() as $realization)
            <tr>
                <td style="width:13mm; text-align: center">{{$loop->index + 1}}</td>
                <td>{{$realization->getStringProductName()}}</td>
                <td>{{$realization->imei}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br />
    <div class="war-text" style="">
        А также за ущерб, возникший у {{setting('corporate.name')}} в результате возмещения им ущерба иным лицам, и, в связи с изложенным, обязуюсь:
        а) бережно относиться к переданной мне для осуществления возложенных на меня функций (обязанностей) продукции и принимать меры к предотвращению ущерба;
        б) своевременно сообщать  непосредственному руководителю о всех обстоятельствах, угрожающих обеспечению сохранности вверенной мне продукции;
        в) вести учет, составлять и представлять в установленном порядке товарно-денежные и другие отчеты о движении и остатках вверенной мне продукции;
        г) участвовать в проведении инвентаризации, ревизии, иной проверке сохранности и состояния вверенной мне продукции.
        <br/>
        Я, {{$courier->name}}, согласен с тем, что определение размера ущерба, причиненного мною {{setting('corporate.name')}}, а также ущерба, возникшего у {{setting('corporate.name')}} в результате возмещения им ущерба иным лицам,
        и порядок их возмещения производятся в соответствии с действующим законодательством, также согласен с тем, что я обязан возмещать ущерб в любом случае.
        <br/>
        Действие расписки распространяется на все время работы с вверенной мне продукцией.
        Настоящая расписка  составлена в двух имеющих одинаковую юридическую силу экземплярах, один из которых  находится у {{setting('corporate.name')}}, а второй - у меня.
    </div>
    <br />
    <br />
    <div>
        <span> Генеральный директор ______________________/_________________________</span>
        <span style="float: right">Сотрудник ______________________/_________________________</span>

    </div>

</div>