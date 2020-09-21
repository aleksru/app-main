
<div title="{{$comment}}">
    {{ strlen($comment) > 50 ? (substr($comment, 0, 50) . '...') : $comment }}
</div>
