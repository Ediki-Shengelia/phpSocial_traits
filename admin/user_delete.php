<?php
require_once __DIR__ . '/../includes/init.php';
if (!$session->is_signed_in()) {
    Redirect("../login.php");
}

$user_id = $_GET['user_id'];
if (empty($user_id)) {
    Redirect("index.php");
}

$user = User::find_by_id($user_id);
if ($user) {
    $user->delete_with_photo();
}

Redirect("index.php");
