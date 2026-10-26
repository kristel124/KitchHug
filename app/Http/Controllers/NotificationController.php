<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return view('notifications.index', [
            'notifications' => $user->notifications()->latest()->paginate(20),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $record = $user->notifications()->whereKey($notification)->firstOrFail();
        abort_unless($record instanceof DatabaseNotification, 404);

        $record->markAsRead();
        $url = $record->data['url'] ?? route('notifications.index');
        $target = parse_url($url);
        abort_if(isset($target['host']) && $target['host'] !== $request->getHost(), 400);

        return redirect()->to($url);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $user->unreadNotifications()->update(['read_at' => now()]);

        return to_route('notifications.index')->with('status', 'All notifications marked as read.');
    }
}
