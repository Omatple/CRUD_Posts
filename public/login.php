<?php

use App\Database\User;
use App\Utils\CookieHelper;
use App\Utils\FormInputValidator;
use App\Utils\PageNavigation;
use App\Utils\SessionErrorHandler;
use App\Utils\UserValidator;

session_start();
require __DIR__ . "/../vendor/autoload.php";

if (isset($_SESSION["user"])) {
    PageNavigation::redirectToPage("index.php");
}

if (CookieHelper::canShowLoginButton() && isset($_POST["email"])) {
    $email = FormInputValidator::sanitizeInput($_POST["email"]);
    $password = FormInputValidator::sanitizeInput($_POST["pass"]);
    $hasErrors = false;

    if (!UserValidator::isEmailValid($email)) {
        $hasErrors = true;
    }

    if (!UserValidator::isPasswordLengthValid($password)) {
        $hasErrors = true;
    }

    if ($hasErrors) {
        PageNavigation::reloadCurrentPage();
    }

    if (!UserValidator::areCredentialsValid($email, $password)) {
        CookieHelper::incrementLoginAttempts();
        PageNavigation::reloadCurrentPage();
    }

    if (CookieHelper::getLoginAttempts() > 0) {
        CookieHelper::resetLoginAttempts();
    }

    $_SESSION["user"] = User::fetchUserByEmail($email);
    PageNavigation::redirectToPage("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
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
                <?= SessionErrorHandler::displayError("email") ?>
                <?= SessionErrorHandler::displayError("credentials") ?>
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
                <?= SessionErrorHandler::displayError("password") ?>
                <?= SessionErrorHandler::displayError("attempts") ?>
                <?= SessionErrorHandler::displayError("block") ?>
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
                <?php if (CookieHelper::canShowLoginButton()): ?>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

</html>