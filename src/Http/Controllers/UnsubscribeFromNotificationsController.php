<?php

namespace Spatie\Comments\Http\Controllers;

use Spatie\Comments\Models\CommentNotificationSubscription;

class UnsubscribeFromNotificationsController
{
    public function askConfirmation(CommentNotificationSubscription $commentNotificationSubscription)
    {
        return view('comments::signed.notificationSubscription.unsubscribe', $commentNotificationSubscription);
    }

    public function unsubscribe(CommentNotificationSubscription $commentNotificationSubscription)
    {
        $commentNotificationSubscription->delete();

        return view('comments::signed.notificationSubscription.unsubscribe', $commentNotificationSubscription);
    }
}
