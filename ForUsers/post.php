<?php
require_once __DIR__ . '/../includes/init.php';
$post_id = $_GET['post_id'];
if (empty($post_id)) {
    Redirect("index.php");
}
$post = Post::find_by_id($post_id);

if (isPostRequest()) {
    $comment = trim($_POST['comment']);
    $created_comm = Comment::create_comment($post_id, $comment);
    if ($created_comm) {
        if ($session->getUserId() != $post->user_id) {
            $notification = new Notification("Comment", $created_comm->id);
            $notification->user_id = $session->getUserId();
            $notification->notifyType("Comment Added");
            $user = User::find_by_id($session->getUserId())->name;
            $notification->notifyData($user . " Added Comment on YOUR post -" . $post->title);
            $notification->create_notification();
        }
    }
    Redirect("post.php?post_id={$post_id}");
}
$comments = Comment::find_by_post_id($post_id);
?>
<div style="font-family: Arial, sans-serif; max-width: 650px; margin: 20px auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <a href="index.php" style="display: inline-block; padding: 6px 12px; background-color: #f0f2f5; color: #1c1e21; text-decoration: none; border-radius: 4px; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; border: 1px solid #ccd0d5; transition: background-color 0.2s;">
        ← Home Page
    </a>
    <br>
    <!-- Post Title -->
    <h1 style="display: inline-block; margin: 0 0 10px 0; font-size: 24px; color: #222222;">
        <?= htmlspecialchars($post->title); ?>
    </h1>

    <!-- Post Author Info -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
        <?php $author = User::find_by_id($post->user_id); ?>
        <?php if (!empty($author->image)): ?>
            <img src="../users/<?= htmlspecialchars($author->image); ?>" alt="User Photo" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
        <?php else: ?>
            <div style="width: 45px; height: 45px; border-radius: 50%; background-color: #eee; display: flex; align-items: center; justify-content: center; color: #888; font-size: 12px;">No Pic</div>
        <?php endif; ?>

        <div>
            <span style="color: #2e7d32; font-weight: bold; font-size: 15px;">
                by <?= htmlspecialchars($author->name); ?>
            </span>
        </div>
    </div>

    <!-- Like Section -->
    <?php $user_like = Like::find_by_user_post($session->getUserId(), $post_id); ?>
    <h2 style="font-size: 16px; font-weight: normal; margin: 0 0 15px 0;">
        <a href="toggle_like.php?post_id=<?= (int)$post_id; ?>" style="display: inline-block; padding: 6px 12px; background-color: #1877f2; color: #ffffff; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold;">
            <?= $user_like ? "Unlike" : "Like"; ?>
        </a>
        <span style="color: #666; margin-left: 8px;">
            (<?= Like::count_by_post_id($post_id); ?> Likes)
        </span>
    </h2>

    <!-- Post Content -->
    <div style="font-size: 15px; line-height: 1.6; color: #333333; margin-bottom: 15px;">
        <p style="margin: 0;"><?= htmlspecialchars($post->content); ?></p>
    </div>

    <!-- Post Image -->
    <?php if (!empty($post->image)): ?>
        <div style="margin-bottom: 20px; text-align: center; background-color: #f9f9f9; padding: 10px; border-radius: 6px;">
            <img src="../posts/<?= htmlspecialchars($post->image); ?>" alt="Post Image" style="max-width: 100%; height: auto; max-height: 350px; border-radius: 4px; object-fit: cover;">
        </div>
    <?php endif; ?>

    <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">

    <!-- Comments Section -->
    <div>
        <h3 style="font-size: 18px; color: #444444; margin-bottom: 15px;">Comments</h3>

        <div style="margin-bottom: 20px;">
            <?php foreach ($comments as $commentari): ?>
                <?php $comment_author = User::find_by_id($commentari->user_id); ?>
                <div style="background-color: #f7f9fa; padding: 10px 12px; border-radius: 6px; margin-bottom: 10px; border: 1px solid #eaeaea;">
                    <p style="margin: 0 0 4px 0; font-weight: bold; font-size: 13px; color: #1a1a1a;">
                        <?= htmlspecialchars($comment_author->name); ?>
                    </p>
                    <p style="margin: 0; font-size: 14px; color: #444444;">
                        <?= htmlspecialchars($commentari->comment); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add Comment Form -->
        <div style="background-color: #fafafa; padding: 15px; border-radius: 6px; border: 1px solid #e5e5e5;">
            <h6 style="margin: 0 0 10px 0; font-size: 14px; color: #555555;">Leave a comment</h6>
            <form action="" method="post" style="margin: 0;">
                <textarea name="comment" required placeholder="Write a comment..." style="width: 100%; height: 70px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; font-size: 14px; resize: vertical; margin-bottom: 10px;"></textarea>
                <input type="submit" value="Comment" style="background-color: #2e7d32; color: #ffffff; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 14px;">
            </form>
        </div>
    </div>

</div>
<?php

require_once __DIR__ . '/partials/footer.php';
?>