
    <form id="user-form" role="form" method="post" class="form-horizontal" action="{{ isset($client) ? route('clients.update', $client->id) :  route('clients.store') }}">
        {{ csrf_field() }}

        @if (isset($client))
            {{ method_field('PUT') }}
        @endif

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Имя</label>

            <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{ $client->name ?? '' }}" name="name">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Телефон</label>

            <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{ $client->phone ?? '' }}" name="phone">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Описание</label>

            <div class="col-sm-10">
                <textarea class="form-control" rows="4"name="description">{{ $client->description ?? '' }}</textarea>
            </div>
        </div>

        @if (isset($client))
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Звонки</label>

                <div class="col-sm-2">
                    <span class="pull-left badge bg-aqua">{{ $client->calls()->count() ?? 0 }}</span>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Заказы</label>

                <div class="col-sm-2">
                    <span class="pull-left badge bg-green">{{ $client->orders()->count() ?? 0 }}</span>
                </div>
            </div>
        @endif

        <div class="col-sm-12">
            <button form="user-form" type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-save"></i> Сохранить
            </button>

        </div>
    </form>