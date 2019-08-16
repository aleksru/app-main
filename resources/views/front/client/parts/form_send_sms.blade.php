
<form id="send-sms-form" method="post" class="form-horizontal" action="{{ route('sms.client.send', [$client->id]) }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="text" class="col-sm-2 control-label">Текст</label>

        <div class="col-sm-10">
            <textarea class="form-control" name="text" placeholder="Введите тест сообщения" rows="6"></textarea>
        </div>
    </div>
    <div class="box-footer">
        <button form="send-sms-form" type="submit" class="btn btn-primary pull-right">
            <i class="fa fa-envelope-o"></i> Отправить
        </button>
    </div>
</form>

@push('scripts')
    <script>
        $(function () {
            $('#send-sms-form').submit(async function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                if ( ! validationForm(formData) ){
                    return toast.error('Не заполнены поля!');
                }

                $('#send-sms-form button').toggle();
                try{
                    let res = await axios.post($(this).attr("action"), formData);
                    toast.success(res.data.message);
                }catch (err){
                    toast.error('Что то пошло не так (((');
                    console.log(err);
                }

                $('#send-sms-form button').toggle();
            });

            function validationForm(formData) {
                return formData.get('text') != ''
            }
        });
    </script>
@endpush