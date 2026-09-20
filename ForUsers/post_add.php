<?php

require_once __DIR__ . '/partials/header.php';

$errors = array();
if (isPostRequest()) {
    $post = new Post();
    $post->user_id = $session->getUserId();
    $post->title = trim($_POST['title']);
    $post->content = trim($_POST['content']);

    if (!$post->set_file($_FILES['image'])) {
        $errors = $post->errors;
    } elseif (!$post->save_with_photo()) {
        $errors = $post->errors;
    } elseif ($post->save()) {
        Redirect("index.php");
    } else {
        $errors[] = "Could NOt create The Post";
    }
}
?>
<h1>Add a Post</h1>
<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $e): ?>
        <p style="color: red;"><?= htmlspecialchars($e); ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>
    <br>
    <label for="content">Content</label>
    <textarea name="content" id="content"></textarea>
    <br>
    <input type="file" name="image" id="">
    <br>
    <input type="submit" value="Add Post">
</form>

<?php

require_once __DIR__ . '/partials/footer.php';
