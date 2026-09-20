<?php
require_once __DIR__ . '/partials/header.php';
if (User::find_by_id($session->getUserId())->role != 'admin') {
    Redirect("../ForUsers/index.php");
}
?>
<h1>Hello admins</h1>

<?php

require_once __DIR__ . '/partials/footer.php';
