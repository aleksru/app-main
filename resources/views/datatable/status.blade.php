@if (!$status && !$status_code)
    <h3><span class="badge bg-green">New</span></h3>
@elseif ($status && !$status_code)
    <h5><span class="badge bg-blue">В работе</span></h5>
@elseif ($status_code)
    <h5><span class="badge">Завершено</span></h5>
@endif