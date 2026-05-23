<?php
session_start();

$db_host = 'localhost';
$db_name = 'u82184';
$db_user = 'u82184';
$db_pass = '6010664';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: login.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'Введите логин и пароль.';
    header('Location: login.php');
    exit();
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, full_name, username, password_hash FROM applications WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: edit.php');
        exit();
    } else {
        $_SESSION['login_error'] = 'Неверный логин или пароль.';
        header('Location: login.php');
        exit();
    }

} catch (PDOException $e) {
    $_SESSION['login_error'] = 'Ошибка базы данных.';
    header('Location: login.php');
    exit();
}
