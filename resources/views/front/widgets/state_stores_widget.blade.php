@if( ! $stateStores->isEmpty() )
    <div class="state_stores-widget">
        <div class="row">
            <div class="callout callout-info">
                @include('front.widgets.state_stores_list', ['stateStores' => $stateStores])
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {
                setInterval(updateWidgetStateStore, 300 * 1000);
                async function updateWidgetStateStore() {
                    await axios.get("{{route('stores.state.widget')}}")
                        .then(function (res) {
                            $('.state_stores-widget .callout-info').html(res.data.html)
                        }).catch(function (err) {
                            console.log(err);
                        })
                }
            })
        </script>
    @endpush
@endif