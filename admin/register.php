<?php
session_start();
require_once '../config/config.php'; // Обновленный путь к config.php

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Проверка на пустые поля
    if (empty($username) || empty($password)) {
        $error = 'Username and password cannot be empty.';
    } else {
        // Проверяем, существует ли уже пользователь с таким именем
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = 'This username is already taken. Please choose another one.';
        } else {
            // Хешируем пароль для безопасного хранения
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Вставляем нового пользователя в базу данных
            try {
                $stmt = $pdo->prepare('INSERT INTO admins (username, password) VALUES (:username, :password)');
                $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

                // Успешная регистрация, перенаправляем на страницу входа
                $_SESSION['success'] = 'Registration successful. Please log in.';
                header('Location: login.php');
                exit;
            } catch (PDOException $e) {
                // Обрабатываем ошибку при вставке
                error_log("❌ Ошибка при регистрации: " . $e->getMessage());
                $error = 'An error occurred. Please try again later.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        /* Контейнер для формы регистрации */
        .register-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 350px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .register-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.7);
        }

        .register-container h2 {
            margin-bottom: 20px;
            color: var(--color-primary);
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .register-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .register-container label {
            font-size: 1rem;
            color: var(--color-primary);
            text-align: left;
            margin-bottom: 5px;
        }

        .register-container input {
            padding: 12px;
            border: 1px solid var(--color-secondary);
            border-radius: 4px;
            background-color: var(--color-tertiary);
            color: var(--color-primary);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .register-container input:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .register-container button {
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

        .register-container button:hover {
            background-color: #2e9a0a;
            transform: translateY(-3px);
        }

        .register-container a {
            margin-top: 20px;
            color: var(--color-secondary);
            font-size: 1rem;
            font-weight: 600;
            transition: color 0.3s;
        }

        .register-container a:hover {
            color: #2e9a0a;
        }

        .error-message {
            color: var(--color-accent);
            margin-bottom: 20px;
            font-size: 1rem;
            font-weight: 600;
        }

        .success-message {
            color: #3fd10f;
            margin-bottom: 20px;
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Admin Register</h2>

        <!-- Выводим сообщения об ошибках или успешной регистрации -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>

        <br>
        <a href="login.php">
            <button type="button">Back to Login</button>
        </a>
    </div>
</body>
</html>

