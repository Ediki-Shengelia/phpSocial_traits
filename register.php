<?php

require_once __DIR__ . '/partials/header.php';
if (isPostRequest()) {
    if ($_POST['password'] !== $_POST['confirm']) {
        $errors = "password Do not match";
    } else {
        $user = new User();

        $user->ensure_directory_exists("users");
        $user->directory = "users";
        $user->name = trim($_POST['name']);
        $user->role = "user";

        $user->email = trim($_POST['email']);
        $user->password = trim($_POST['confirm']);
        if (!$user->set_file($_FILES['image'])) {
            $errors = $user->errors;
        } elseif (!$user->save_with_photo()) {
            $errors = $user->errors;
        } elseif ($user->save()) {
            Redirect('admin/index.php');
        } else {
            $errors[] = "Could not create the account.";
        }
    }
}
?>

<h1>Register</h1>
<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $e): ?>
        <p style="color:red"><?= htmlspecialchars($e); ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" required>
    <br>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="password">Passowrd</label>
    <input type="password" name="password" id="password" required>
    <br>
    <label for="confirm">Confirm</label>
    <input type="password" name="confirm" id="confirm" required>
    <br>
    <input type="file" name="image" id="">
    <br>
    <input type="submit" value="Register">
</form>

<?php

require_once __DIR__ . '/partials/footer.php';
