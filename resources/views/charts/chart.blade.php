
{!! $chart->container() !!}

@pushonce('scripts', 'chart')
    <script src="{{ asset('assets/vendors/charts/Chart.min.js') }}" charset=utf-8></script>
@endpushonce

@push('scripts')
    {!! $chart->script() !!}
@endpush
