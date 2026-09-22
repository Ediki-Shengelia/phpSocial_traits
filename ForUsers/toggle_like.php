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
    if ($like->create()) {
        $post = Post::find_by_id($post_id);
        $liker = User::find_by_id($user_id);
        if ($session->getUserId() != $post->user_id) {
            $notification = new Notification("Like", $post->id);
            $notification->user_id = $post->user_id;
            $notification->notifyType("Post Liked");
            $notification->notifyData($liker->name . " Liked YOur post -" . $post->title);
            $notification->create_notification();
        }
    }
}

Redirect("post.php?post_id={$post_id}");
