<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        View::composer('*', function ($view) {
            // 除外したいルートをここに追加（パターンにマッチ）
            if (Request::is('login') || Request::is('register') || Request::is('logout')) {
                return; // スキップ
            }

            if (!Auth::check()) {
                return;
            }

            // Count the number of all the unread messages (returns an integer)
            $notifications_count = Chat::countUnreadMessages();
            // Get all the unread messages grouped by sender (returns an array)
            $notificationDetails = Chat::getUnreadGroupedBySender();

            // Count follow requests;
            $requests_count      = User::countFollowRequests();

            $total_notifications = $notifications_count + $requests_count;

            $view->with('total_notifications', $total_notifications)
                 ->with('notifications_count', $notifications_count)
                 ->with('notificationDetails', $notificationDetails)
                 ->with('requests_count', $requests_count);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
