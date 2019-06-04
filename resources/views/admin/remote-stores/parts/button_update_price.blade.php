@if(isset($store))
    <button id="btn_updated_prices"
            type="submit"
            class="btn btn-warning"
            data-route="{{route('admin.remote-store.update-prices', $store->id)}}">
        <i class="fa fa-cloud-upload"></i> Обновить цены
    </button>
    @push('scripts')
        <script>
            $(function () {
                $('#btn_updated_prices').click(async function () {
                    await axios.get(this.dataset.route).then(function(response) {
                        if(response.data.success){
                            toast.success(response.data.success);
                        }
                        if(response.data.error){
                            toast.error(response.data.error);
                        }
                    }).catch(function (err) {
                        toast.error('Произошла ошибка выполнения');
                        console.log(err);
                    });
                });
            });
        </script>
    @endpush
@endif
