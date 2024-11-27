<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');

    }

    public function ReadNotifcation($notification_id)
    {
        $user = Auth::user();

        $notification = $user->notifications->find($notification_id);
        $notification->markAsRead();
        return redirect()->route('invoiceDetails.show',$notification->data['id']);
    }

    public function ReadAllNotifcation()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications;
        if($notifications->count() > 0 )
        {
         $notifications->markAsRead();
        }

        return redirect()->back();

    }

   
}
