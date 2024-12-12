<?php

use App\Database\CategoryEntity;
use App\Database\PostEntity;
use App\Utils\InputValidator;
use App\Utils\Navigation;
use App\Utils\PostValidator;
use App\Utils\SessionErrorDisplay;
use App\Utils\StatusPost;

session_start();
require __DIR__ . "/../vendor/autoload.php";

$user = $_SESSION["user"] ?? false;

if (isset($_POST["titulo"])) {
    $title = InputValidator::sanitize($_POST["titulo"]);
    $content = InputValidator::sanitize($_POST["contenido"]);
    $categoryId = (int) InputValidator::sanitize($_POST["categoria_id"]);
    $status = ($user && isset($_POST["status"])) ? StatusPost::Published->toString() : StatusPost::Draft->toString();
    $hasErrors = false;
    if (!PostValidator::isValidTitle($title)) $hasErrors = true;
    if (!$hasErrors && !PostValidator::isUniqueTitle($title)) $hasErrors = true;
    if (!PostValidator::isValidContent($content)) $hasErrors = true;
    if (!PostValidator::isValidCategory($categoryId)) $hasErrors = true;
    if ($hasErrors) Navigation::refresh();
    (new PostEntity)
        ->setTitulo($title)
        ->setContenido($content)
        ->setCategoriaId($categoryId)
        ->setStatus($status)
        ->createPost();
    $_SESSION["message"] = "Post created succesly";
    Navigation::redirectTo("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-blue-500 text-white px-4 py-2 flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">
            <img src="img/iesalandalus.png" alt="Profile" class="rounded-full w-10 h-10">
            <a href="index.php" class="text-lg font-semibold hover:underline">
                <i class="fas fa-home mr-2"></i>Home
            </a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <?php if ($user): ?>
                <!-- Email Display -->
                <input
                    type="text"
                    value="<?= $user["email"] ?>"
                    readonly
                    class="px-4 py-2 rounded-lg bg-white text-gray-800 border border-gray-300 focus:outline-none">
                <!-- Logout Button -->
                <a href="logout.php" class="px-4 py-2 bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            <?php else: ?>
                <!-- Login Button -->
                <a href="login.php" class="px-4 py-2 bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none text-white">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            <?php endif; ?>
        </div>
    </nav>
    <main>
        <div class="w-1/3 mx-auto p-4 rounded-xl border-2 shadow-xl border-black mt-4">
            <form method='POST' action="<?= $_SERVER["PHP_SELF"] ?>">
                <!-- Título -->
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Ingrese el título"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?= SessionErrorDisplay::showError("title") ?>
                </div>

                <!-- Contenido -->
                <div class="mb-4">
                    <label for="contenido" class="block text-gray-700 text-sm font-bold mb-2">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="5"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Ingrese el contenido"></textarea>
                    <?= SessionErrorDisplay::showError("content") ?>
                </div>

                <!-- Categoría -->
                <div class="mb-4">
                    <label for="categoria" class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select id="categoria" name="categoria_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">__ Seleccione una categoría __</option>
                        <?php foreach (CategoryEntity::readCategory() as $category): ?>
                            <option value="<?= $category["id"] ?>"><?= $category["nombre"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= SessionErrorDisplay::showError("category") ?>
                </div>

                <?php if ($user): ?>
                    <!-- Toggle Switch -->
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                        <label class="inline-flex items-center mb-5 cursor-pointer">
                            <input type="checkbox" value="PUBLICADO" class="sr-only peer" name="status" id="status">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Publicado</span>
                        </label>
                    </div>
                <?php endif ?>

                <!-- Botones -->
                <div class="flex items-center justify-between">
                    <!-- Botón Enviar -->
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar
                    </button>
                    <!-- Botón Reset -->
                    <button type="reset"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </button>
                    <!-- Enlace a index -->
                    <a href="index.php"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                </div>
            </form>
        </div>

    </main>
</body>

</html>