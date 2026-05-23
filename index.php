<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_host = 'localhost';
$db_name = 'u82184';
$db_user = 'u82184';
$db_pass = '6010664';

$languages = [];
$error_message = '';

$cookie_errors = [];
if (isset($_COOKIE['form_errors'])) {
    $cookie_errors = json_decode($_COOKIE['form_errors'], true) ?: [];
    setcookie('form_errors', '', time() - 3600, '/');
}

$cookie_old = [];
if (isset($_COOKIE['form_old_values'])) {
    $cookie_old = json_decode($_COOKIE['form_old_values'], true) ?: [];
    setcookie('form_old_values', '', time() - 3600, '/');
}

$saved_data = [];
if (isset($_COOKIE['form_saved_data'])) {
    $saved_data = json_decode($_COOKIE['form_saved_data'], true) ?: [];
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables_exist = $pdo->query("SHOW TABLES LIKE 'programming_languages'")->rowCount() > 0;

    if (!$tables_exist) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS programming_languages (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                language_name VARCHAR(50) NOT NULL UNIQUE
            );
            
            INSERT INTO programming_languages (language_name) VALUES 
            ('Pascal'), ('C'), ('C++'), ('JavaScript'), ('PHP'), 
            ('Python'), ('Java'), ('Haskell'), ('Clojure'), 
            ('Prolog'), ('Scala'), ('Go');
            
            CREATE TABLE IF NOT EXISTS applications (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(150) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                email VARCHAR(100) NOT NULL,
                birth_date DATE NOT NULL,
                gender ENUM('male', 'female', 'other') NOT NULL,
                biography TEXT,
                agreed_to_contract TINYINT(1) DEFAULT 0,
                username VARCHAR(50) UNIQUE,
                password_hash VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
            
            CREATE TABLE IF NOT EXISTS application_languages (
                application_id INT UNSIGNED NOT NULL,
                language_id INT UNSIGNED NOT NULL,
                PRIMARY KEY (application_id, language_id),
                FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
                FOREIGN KEY (language_id) REFERENCES programming_languages(id) ON DELETE CASCADE
            );
        ");
    }

    $stmt = $pdo->query("SELECT id, language_name FROM programming_languages ORDER BY language_name");
    $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error_message = "Ошибка подключения к БД: " . $e->getMessage();

    $languages = [
        ['id' => 1, 'language_name' => 'Pascal'],
        ['id' => 2, 'language_name' => 'C'],
        ['id' => 3, 'language_name' => 'C++'],
        ['id' => 4, 'language_name' => 'JavaScript'],
        ['id' => 5, 'language_name' =>
