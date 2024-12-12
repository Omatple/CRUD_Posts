<?php

use App\Database\UserEntity;
use App\Utils\CookieManager;
use App\Utils\InputValidator;
use App\Utils\Navigation;
use App\Utils\SessionErrorDisplay;
use App\Utils\UserValidator;

session_start();
require __DIR__ . "/../vendor/autoload.php";
if (isset($_SESSION["user"])) Navigation::redirectTo("index.php");
if (CookieManager::showLoginButton() && isset($_POST["email"])) {
    $email = InputValidator::sanitize($_POST["email"]);
    $pass = InputValidator::sanitize($_POST["pass"]);
    $hasErrors = false;
    if (!UserValidator::isValidEmail($email)) $hasErrors = true;
    if (!UserValidator::isValidLength($pass)) $hasErrors = true;
    if ($hasErrors) Navigation::refresh();
    if (!UserValidator::isValidCredentials($email, $pass)) {
        CookieManager::incrementAttempts();
        Navigation::refresh();
    }
    if (CookieManager::getAttemptsLogin()) CookieManager::deleteAttempts();
    $_SESSION["user"] = UserEntity::getUserByEmail($email);
    Navigation::redirectTo("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="flex items-center justify-center min-h-screen bg-orange-200">
    <div class="bg-white p-8 rounded-xl shadow-xl w-96">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Login</h2>
        <form method='POST' action="<?= $_SERVER["PHP_SELF"] ?>">
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-1">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <div class="relative">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-user absolute top-3 right-3 text-gray-400"></i>
                </div>
                <?= SessionErrorDisplay::showError("email") ?>
                <?= SessionErrorDisplay::showError("credentials") ?>
            </div>
            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-1">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="pass"
                        placeholder="Enter your password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-key absolute top-3 right-3 text-gray-400"></i>
                </div>
                <?= SessionErrorDisplay::showError("password") ?>
                <?= SessionErrorDisplay::showError("attempts") ?>
                <?= SessionErrorDisplay::showError("block") ?>
            </div>
            <!-- Buttons -->
            <div class="flex items-center justify-between">
                <a
                    href="index.php"
                    class="inline-block px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <button
                    type="reset"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
                <?php if (CookieManager::showLoginButton()):   ?>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                <?php endif;  ?>
            </div>
        </form>

    </div>
</body>

</html>