<?php
session_start();
require_once '../config/config.php'; // Обновленный путь к config.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        header('Location: dashboard.php');  // Перенаправление на страницу после успешной авторизации
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admin_styles.css">
    <style>
        /* Общие сбросы и базовые стили */
        * {
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Montserrat", sans-serif;
            line-height: 1.5;
            background: url("./Flux_Dev_____Mercedes_EClass_______TAXI____________TAXI________1.jpeg") center/cover no-repeat fixed;
            color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        /* CSS-переменные */
        :root {
            --color-primary: #f8f8f8;
            --color-secondary: #3fd10f;
            --color-tertiary: #1a1a1a;
            --color-action: #3fd10f;
            --color-white: #fff;
            --color-dark: #333;
            --color-shadow: rgba(0, 0, 0, 0.5);
            --color-accent: #e74c3c;
            --transition: all 0.3s ease;
        }

        /* Контейнер для формы логина */
        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 350px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.7);
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: var(--color-primary);
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-container label {
            font-size: 1rem;
            color: var(--color-primary);
            text-align: left;
            margin-bottom: 5px;
        }

        .login-container input {
            padding: 12px;
            border: 1px solid var(--color-secondary);
            border-radius: 4px;
            background-color: var(--color-tertiary);
            color: var(--color-primary);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .login-container input:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .login-container button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            background-color: var(--color-secondary);
            color: var(--color-tertiary);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .login-container button:hover {
            background-color: #2e9a0a;
            transform: translateY(-3px);
        }

        .login-container a {
            margin-top: 20px;
            color: var(--color-secondary);
            font-size: 1rem;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-container a:hover {
            color: #2e9a0a;
        }

        .error-message {
            color: var(--color-accent);
            margin-bottom: 20px;
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        <!-- Выводим сообщения об ошибках или успешной авторизации -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>

        <br>
        <a href="register.php">
            <button type="button">Register</button>
        </a>
    </div>
</body>
</html>



