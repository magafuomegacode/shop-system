<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get latest unread notifications for the current user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $notifications = Notification::where('shop_id', $user->shop_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'count'         => $unreadCount,
                'notifications' => $notifications->map(function ($n) {
                    return [
                        'id'      => $n->id,
                        'type'    => $n->type,
                        'title'   => $n->title,
                        'message' => $n->message,
                        'link'    => $n->link,
                        'icon'    => $n->icon,
                        'is_read' => $n->is_read,
                        'time'    => $n->created_at->diffForHumans(),
                    ];
                }),
            ]);
        }

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $user = Auth::user();

        if ($notification->shop_id !== $user->shop_id) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        Notification::where('shop_id', $user->shop_id)
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->id);
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}