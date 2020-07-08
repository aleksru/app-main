<!--
    @var $status App\Models\Other
-->

<div class="badge bg-{{$status->color ?? ''}}" style="font-size: 14px; padding: 7px; white-space: normal">
    {{ $status->name ?? '' }}
</div>