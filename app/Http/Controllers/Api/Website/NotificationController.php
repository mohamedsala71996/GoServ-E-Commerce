<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

//     public function showNotifications(Request $request)
// {
//     $user = $request->user(); // Get the authenticated user

//     $unreadNotifications = $user->getUnreadNotifications();
//     $readNotifications = $user->getReadNotifications();
//     $allNotifications = $user->getAllNotifications();

//     return response()->json([
//         'unread_notifications' => $unreadNotifications,
//         'read_notifications' => $readNotifications,
//         'all_notifications' => $allNotifications,
//     ]);
// }

public function showUnreadNotifications(Request $request)
{
    $user = $request->user(); // Get the authenticated user
    $unreadNotifications = $user->getUnreadNotifications();

    return response()->json([
        'unread_notifications' => $unreadNotifications,
    ]);
}

/**
 * Get read notifications.
 */
public function showReadNotifications(Request $request)
{
    $user = $request->user(); // Get the authenticated user
    $readNotifications = $user->getReadNotifications();

    return response()->json([
        'read_notifications' => $readNotifications,
    ]);
}

/**
 * Get all notifications.
 */
public function showAllNotifications(Request $request)
{
    $user = $request->user(); // Get the authenticated user
    $allNotifications = $user->getAllNotifications();

    return response()->json([
        'all_notifications' => $allNotifications,
    ]);
}

public function markAsRead(Request $request, $notificationId)
{
    $user = $request->user(); // Get the authenticated user

    // Find the notification
    $notification = $user->notifications()->find($notificationId);

    if (!$notification) {
        return response()->json([
            'status' => 'error',
            'message' => 'Notification not found.',
        ], 404);
    }

    // Mark the notification as read
    $notification->markAsRead();

    return response()->json([
        'status' => 'success',
        'message' => 'Notification marked as read successfully.',
    ]);
}


    // public function showProductReview($id)
    // {
    //     $review = ProductReview::with(['user', 'product','replies'])->findOrFail($id);
    //     return new ProductReviewResource($review);
    // }
}
