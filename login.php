<?php
session_start();
$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Вход в систему</h1>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="auth.php" method="POST">
        <p><label>Логин: <input type="text" name="username" required></label></p>
        <p><label>Пароль: <input type="password" name="password" required></label></p>
        <p><button type="submit">Войти</button></p>
    </form>
    <p><a href="index.php">Вернуться к анкете</a></p>
</body>
</html>
