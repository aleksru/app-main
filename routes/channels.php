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