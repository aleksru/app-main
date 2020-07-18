<form id="report-dates" method="get" action="{{ $route }}">
    <div class="row">
        <div class="col-md-2">
            <label>Дата начала</label>
            <input type="date" class="form-control" name="dateFrom">
        </div>
        <div class="col-md-2">
            <label>Дата окончания</label>
            <input type="date" name="dateTo" class="form-control">
        </div>
        {{ $slot ?? '' }}
        <div class="col-md-2">
            <label></label>
            <button type="submit" class="btn btn-primary form-control">
                <p>Вывести</p>
            </button>
        </div>
    </div>
</form>
