<?php

require_once __DIR__ . '/partials/header.php';
if (isPostRequest()) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_found = User::verify_user($email, $password);
    if ($user_found) {
        $session->login($user_found);
        Redirect("admin/index.php");
    } else {
        $error = "user NOt found";
    }
}
?>

<h1>Login</h1>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>
<form action="" method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Login">
</form>
<?php

require_once __DIR__ . '/partials/footer.php';
