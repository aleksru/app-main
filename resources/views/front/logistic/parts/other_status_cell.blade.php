<!--
    @var $status App\Models\Other
-->

<span class="badge bg-{{$status->color ?? ''}}" style="font-size: 14px; padding: 7px">
    {{ $status->name ?? '' }}
</span>