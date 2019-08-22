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
        return response()->json(['content' => $user->getAllUnreadNotifications()]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountUnReadNotifications(User $user, Request $request)
    {
        $countNotifications = $user->getAllUnreadNotifications()->count();

        return response()->json(['count' => $countNotifications]);
    }

}