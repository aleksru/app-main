@if(isset($store))
    <div class="modal fade" id="modal-store_state" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Лог состояния</h4>
                </div>
                <div class="modal-body">
                    <textarea rows="20" cols="75" disabled="disabled">

                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <button id="btn_state_store"
            type="submit"
            class="btn btn-info"
            data-route="{{route('admin.remote-store.state', $store->id)}}">
        <i class="fa fa-cloud-upload"></i> Проверить состояние
    </button>
    @push('scripts')
    <script>
        $(function () {
            $('#btn_state_store').click(async function () {
                this.disabled = true;
                await axios.get(this.dataset.route).then((response) => {
                    if(response.data.success){
                        toast.success(response.data.success);
                    }
                    if(response.data.error){
                        toast.error(response.data.error);
                    }
                    this.disabled = false;
                    $('#modal-store_state .modal-body textarea').html(response.data.state);
                    $('#modal-store_state').modal('show');
                }).catch((err) => {
                    toast.error('Произошла ошибка выполнения');
                    this.disabled = false;
                    console.log(err);
                });
            });
        });
    </script>
    @endpush
@endif