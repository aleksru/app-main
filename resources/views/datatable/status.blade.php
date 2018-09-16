@if (!$status && !$status_code)
    <h3><span class="badge badge-success">New</span></h3>
@elseif ($status && !$status_code)
    <h5><span class="badge badge-info">В работе</span></h5>
@elseif ($status_code)
    <h5><span class="badge badge-secondary">Завершено</span></h5>
@endif