<?php

require_once __DIR__ . '/partials/header.php';

$posts = Post::find_all();
?>
<h2>Welcome <?= User::find_by_id($session->getUserId())->email; ?></h2>
<div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); color: #333333;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 24px; color: #1a1a1a; font-weight: 600;">For Users Folder</h1>
        <a href="post_add.php" style="display: inline-block; padding: 10px 18px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px; transition: background-color 0.2s;">+ Add Post</a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569;">Post ID</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569;">Name of Owner</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569;">Email of Owner</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569;">Title</th>
                    <th style="padding: 12px 16px; font-weight: 600; color: #475569; text-align: center;">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php $current_user = User::find_by_id($session->getUserId()); ?>
                <?php foreach ($posts as $post): ?>

                    <?php $owner = User::find_by_id($post->user_id); ?>

                    <tr onclick="window.location='post.php?post_id=<?= (int)$post->id; ?>';" style="border-bottom: 1px solid #f1f5f9;cursor:pointer">
                        <td style="padding: 12px 16px; color: #64748b; font-weight: 500;">

                            <?= htmlspecialchars($post->id); ?>

                        </td>
                        <td style="padding: 12px 16px; color: #1e293b;"><?= htmlspecialchars($owner ? $owner->name : 'N/A'); ?></td>
                        <td style="padding: 12px 16px; color: #64748b;"><?= htmlspecialchars($owner ? $owner->email : 'N/A'); ?></td>
                        <td style="padding: 12px 16px; color: #1e293b; font-weight: 500;">
                            <?= htmlspecialchars($post->title); ?>
                            <div>
                                <?php $user_like = Like::find_by_user_post($session->getUserId(), $post->id); ?>

                                <?= $user_like ? "❤️" : "🤍"; ?>

                                (<?= Like::count_by_post_id($post->id); ?> Likes)
                            </div>
                        </td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <?php
                            $can_delete = $current_user && (
                                $current_user->role === 'admin' ||
                                (int)$post->user_id === (int)$current_user->id
                            );
                            ?>
                            <?php if ($can_delete): ?>
                                <form action="post_delete.php" method="post" style="margin: 0;"
                                    onsubmit="return confirm('Delete this post?');">
                                    <input type="hidden" name="post_id" value="<?= (int)$post->id; ?>">
                                    <button type="submit" style="padding: 6px 12px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 4px; font-size: 13px; font-weight: 500; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: #999; font-size: 13px;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<?php

require_once __DIR__ . '/partials/footer.php';
?>