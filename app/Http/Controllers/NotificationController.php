<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setReadNotification(Request $request)
    {
        $notifyId = $request->get('notification_id');
        if( ! $notifyId ) {
            return response()->json(['error' => 'Field notification_id not found!'], 400);
        }
        $notify = DatabaseNotification::find($request->get('notification_id'));
        if( ! $notify ) {
            return response()->json(['error' => 'Notification not found'], 400);
        }
        $notify->markAsRead();

        return response()->json(['message' => 'Уведомление прочитано'], 200);
    }
}