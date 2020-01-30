<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('App.Models.UserGroup.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('notifications.{user_id}', function ($user, $user_id) {
    return true;
});

Broadcast::channel('operator-incomming.{user_id}', function ($user, $user_id) {
    return true;
});

Broadcast::channel('operator-callback.{command_uuid}', function ($user, $command_uuid) {
    return true;
});

Broadcast::channel('order', function () {
    return true;
});

Broadcast::channel('chat.{id}', function () {
    return true;
});

Broadcast::channel('order-views.*', function ($user) {
    return [
        'id'   => $user->id,
        'name' => ($user->description ?? $user->name) . (" ({$user->getUserGroupName()})" ?? ''),
        'time' => date('H:i:s')
    ];
});