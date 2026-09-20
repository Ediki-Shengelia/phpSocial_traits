<?php
require_once __DIR__ . '/partials/header.php';
if (User::find_by_id($session->getUserId())->role != 'admin') {
    Redirect("../ForUsers/index.php");
}
$users = User::find_all();
?>
<h1 style="font-family: system-ui, -apple-system, sans-serif; color: #1e293b; font-size: 24px; font-weight: 600; margin-bottom: 20px;">
    Hello admin <?= htmlspecialchars(User::find_by_id($session->getUserId())->name); ?>
</h1>

<div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse; font-family: system-ui, -apple-system, sans-serif; text-align: left; background-color: #ffffff;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Id</th>
                <th style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Name</th>
                <th style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Email</th>
                <th style="padding: 12px 16px; font-size: 14px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <?php if ($user->role != "admin"): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 16px; font-size: 14px; color: #334155;"><?= htmlspecialchars($user->id); ?></td>
                        <td style="padding: 12px 16px; font-size: 14px; color: #0f172a; font-weight: 500;"><?= htmlspecialchars($user->name); ?></td>
                        <td style="padding: 12px 16px; font-size: 14px; color: #64748b;"><?= htmlspecialchars($user->email); ?></td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="user_delete.php?user_id=<?= htmlspecialchars($user->id); ?>">
                                <button style="background-color: #ef4444; color: #ffffff; border: none; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; cursor: pointer;">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php

require_once __DIR__ . '/partials/footer.php';
