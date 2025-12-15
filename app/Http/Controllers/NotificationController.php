<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mark all unread notifications as read
        $user->unreadNotifications->markAsRead();

        // Load all notifications
        $notifications = $user->notifications()->latest()->get();
        return view('dashboard.notifications.index',compact('notifications'));
    }

     public function markNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'ok']);
    }

     public function loadMoreNotifications()
    {
        // auth()->user()->unreadNotifications->markAsRead();
        // return response()->json(['status' => 'ok']);
    }

    public function read($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        // Mark as read
        $notification->markAsRead();

        // Redirect where you want

        return view('dashboard.notifications.read',compact('notification'));
        
    }

    
}
