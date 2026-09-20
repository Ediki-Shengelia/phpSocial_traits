<?php
require_once __DIR__ . '/../includes/init.php';


if (!$session->is_signed_in()) {
    Redirect("../login.php");
}
$user_id = $session->getUserId();
$post_id = $_GET['post_id'];

$like = Like::find_by_user_post($user_id, $post_id);

if ($like) {
    $like->delete();
} else {
    $like = new Like();
    $like->user_id = $user_id;
    $like->post_id = $post_id;
    $like->save();
}

Redirect("post.php?post_id={$post_id}");
