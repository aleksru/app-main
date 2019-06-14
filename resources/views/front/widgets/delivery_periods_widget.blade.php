<div class="delivery-periods-widget">
    <div class="row">
        @include('front.widgets.delivery_periods_list', ['periods' => $periods])
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        setInterval(updateWidget, 60 * 1000);
        async function updateWidget() {
            await axios.get("{{route('logistics.deliveries.widget')}}")
                .then(function (res) {
                    $('.delivery-periods-widget').html(res.data.html)
                }).catch(function (err) {
                    console.log(err);
                })
        }
    })
</script>
@endpush