<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->take(20)->get()->map(function ($n) {
            return [
                'id'      => $n->id,
                'title'   => $n->data['title'] ?? '',
                'message' => $n->data['message'] ?? '',
                'type'    => $n->data['type'] ?? 'info',
                'url'     => $n->data['url'] ?? null,
                'read'    => ! is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
