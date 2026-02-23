<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $unreadNotificationsCount = 0;
        if (Auth::check()) {
            $unreadNotificationsCount = Auth::user()->unreadNotifications()->count();
        }

        return view('layouts.app', [
            'unreadNotificationsCount' => $unreadNotificationsCount,
        ]);
    }
}
