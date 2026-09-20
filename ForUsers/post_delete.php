<?php

require_once __DIR__ . '/../includes/init.php';

if (!$session->is_signed_in()) {
    Redirect("../login.php");
}

$post_id = $_GET['post_id'];
if (empty($post_id)) {
    Redirect("index.php");
}
$post = Post::find_by_id($post_id);
if ($post) {
    $post->delete_with_photo();
}
Redirect("index.php");
