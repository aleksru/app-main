<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function unReadNotifications(User $user)
    {
        $content = view('layouts.adminlte.parts.chunks.notifications_list', [
            'notifications' => $user->unreadNotifications
        ])->render();

        return response()->json(['content' => $content]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountUnReadNotifications(User $user, Request $request)
    {
        $countNotifications = $user->unreadNotifications->count();
        $message = null;
        if($request->session()->pull('count_notifications') < $countNotifications){
            $message = 'У вас новые уведомления!';
        }
        $request->session()->put('count_notifications', $countNotifications);

        return response()->json(['count' => $countNotifications, 'message' => $message]);
    }

}