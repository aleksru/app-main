
<div class="col-md-6">
    <form id="report" method="get" action="{{ $route}}">
        <div class="row">
            <div class="col-md-4">
                <label>Дата начала</label>
                <input type="date" class="form-control" name="dateFrom" id="dateFrom" required>
            </div>
            <div class="col-md-4">
                <label>Дата окончания</label>
                <input type="date" name="dateTo" class="form-control" id="dateTo">
            </div>
            <div class="col-md-4">
                <label></label>
                <button form="report" type="submit" class="btn btn-primary form-control" id="btn-result">
                    <p>Вывести</p>
                </button>
            </div>
        </div>
    </form>
</div>
<div class="col-md-2">
    <form id="report_to_day" method="get" action="{{ $route }}">
        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->toDateString()}}">

        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->addDay()->toDateString()}}">

        <button form="report_to_day" type="submit" class="btn btn-success form-control" id="btn-result">
            <p>Сегодня</p>
        </button>
    </form>
</div>
<div class="col-md-2">
    <form id="report_yesterday" method="get" action="{{ $route }}">
        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->subDay(1)->toDateString()}}">

        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->toDateString()}}">

        <button form="report_yesterday" type="submit" class="btn btn-info form-control" id="btn-result">
            <p>Вчера</p>
        </button>
    </form>
</div>
<div class="col-md-2">
    <form id="report_week" method="get" action="{{ $route }}">
        <input type="hidden" class="form-control" name="dateFrom" value="{{\Carbon\Carbon::today()->subDay(7)->toDateString()}}">

        <input type="hidden" name="dateTo" class="form-control" value="{{\Carbon\Carbon::today()->addDay()->toDateString()}}">

        <button form="report_week" type="submit" class="btn btn-warning form-control" id="btn-result">
            <p>Неделя</p>
        </button>
    </form>
</div>
