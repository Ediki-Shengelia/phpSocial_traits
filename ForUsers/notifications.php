<?php

require_once __DIR__ . '/partials/header.php';


$notificationForOwner = Notification::getNotificationForOwnerUser($session->getUserId());

if (isset($_POST['readThis'])) {
    $id = (int)$_POST['id'];
    $notification = Notification::find_by_id($id); // or similar
    if ($notification && $notification->user_id == $session->getUserId()) {
        Notification::markAsRead($id, $session->getUserId());
    }
    Redirect("notifications.php");
}
if (isset($_POST['allRead'])) {
    Notification::markAsAllRead($session->getUserId());
    Redirect("notifications.php");
}
?>
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; max-width: 900px; margin: 24px auto; padding: 24px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">

    <!-- Header & Action Row -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #f3f4f6;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #111827;">Notifications</h1>
        <form action="" method="post" style="margin: 0;">
            <input type="submit" value="Mark All As Read" name="allRead" style="background-color: #4f46e5; color: #ffffff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#4338ca'" onmouseout="this.style.backgroundColor='#4f46e5'">
        </form>
    </div>

    <!-- Notifications Table -->
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Id</th>
                <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Data</th>
                <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Read At</th>
                <th style="padding: 12px 16px; font-weight: 600; color: #374151; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notificationForOwner as $not): ?>
                <tr onclick="window.location='post.php?post_id=<?= $not->notifiable_id; ?>';" style="cursor:pointer;border-bottom: 1px solid #e5e7eb; <?= empty($not->read_at) ? 'background-color: #f0fdf4;' : ''; ?>">
                    <td style="padding: 12px 16px; color: #6b7280; font-size: 13px;">


                        <?= htmlspecialchars($not->id); ?>

                    </td>
                    <td style="padding: 12px 16px; color: #111827; max-width: 300px; word-break: break-word;"><?= htmlspecialchars($not->data); ?></td>
                    <td style="padding: 12px 16px; color: #6b7280;">
                        <?php if ($not->read_at): ?>
                            <span style="display: inline-block; padding: 2px 8px; background-color: #f3f4f6; color: #374151; border-radius: 12px; font-size: 12px;"><?= htmlspecialchars($not->read_at); ?></span>
                        <?php else: ?>
                            <span style="display: inline-block; padding: 2px 8px; background-color: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 500;">Unread</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px 16px; text-align: right;">
                        <?php if (empty($not->read_at)): ?>
                            <form action="" method="post" style="margin: 0;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($not->id); ?>">
                                <input type="submit" value="Mark as Read" name="readThis" style="background-color: #ffffff; color: #2563eb; border: 1px solid #d1d5db; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#eff6ff'; this.style.borderColor='#93c5fd';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#d1d5db';">
                            </form>
                        <?php else: ?>
                            <span style="color: #9ca3af; font-size: 13px;">Read</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/partials/footer.php';
?>