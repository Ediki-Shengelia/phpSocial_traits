<?php
require_once __DIR__ . '/../includes/init.php';
$post_id = $_GET['post_id'];
if (empty($post_id)) {
    Redirect("index.php");
}
$post = Post::find_by_id($post_id);

?>

<h1 style="display: inline;"><?= $post->title; ?></h1>
<span style="color: green;"> by <?= User::find_by_id($post->user_id)->name; ?>

</span>
<div>
    <p>User Photo</p>
    <?php if (!empty(User::find_by_id($post->user_id)->image)): ?>
        <img style="width:50px;height:50px;" src="../users/<?= htmlspecialchars(User::find_by_id($post->user_id)->image); ?>" alt="">
    <?php endif; ?>
</div>
<div>
    <p><?= $post->content; ?></p>
</div>
<div>
    <p>post Photo</p>
    <?php if (!empty($post->image)): ?>
        <img style="width: 350px;height:300px;" src="../posts/<?= htmlspecialchars($post->image); ?>" alt="">
    <?php endif; ?>
</div>

<?php

require_once __DIR__ . '/partials/footer.php';
?>