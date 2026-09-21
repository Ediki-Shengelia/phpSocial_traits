<?php

require_once __DIR__ . '/partials/header.php';

$notificationForOwner = Notification::getNotificationForOwnerUser($session->getUserId());

?>

<h1>Notifications</h1>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>data</th>
            <th>read_at</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($notificationForOwner as $not): ?>
            <tr>
                <td><?= $not->id; ?></td>
                <td><?= $not->data; ?></td>
                <td><?= $not->read_at ?? "-"; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once __DIR__ . '/partials/footer.php';
?>