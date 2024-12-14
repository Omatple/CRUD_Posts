<?php

use App\Database\Category;
use App\Database\Post;
use App\Utils\FormInputValidator;
use App\Utils\PageNavigation;
use App\Utils\PostValidator;
use App\Utils\SessionErrorHandler;
use App\Utils\PostStatus;

session_start();
require __DIR__ . "/../vendor/autoload.php";

$user = $_SESSION["user"] ?? false;

if (isset($_POST["title"])) {
    $title = FormInputValidator::sanitizeInput($_POST["title"]);
    $content = FormInputValidator::sanitizeInput($_POST["content"]);
    $categoryId = (int) FormInputValidator::sanitizeInput($_POST["category_id"]);
    $status = ($user && isset($_POST["status"])) ? PostStatus::Published->getStatusLabel() : PostStatus::Draft->getStatusLabel();
    $hasErrors = false;

    if (!PostValidator::isTitleValid($title)) $hasErrors = true;
    if (!$hasErrors && !PostValidator::isTitleUnique($title)) $hasErrors = true;
    if (!PostValidator::isContentValid($content)) $hasErrors = true;
    if (!PostValidator::isCategoryValid($categoryId)) $hasErrors = true;

    if ($hasErrors) {
        PageNavigation::reloadCurrentPage();
    }

    (new Post)
        ->setTitle($title)
        ->setContent($content)
        ->setCategoryId($categoryId)
        ->setStatus($status)
        ->savePost();

    $_SESSION["alertMessage"] = "Post created successfully.";
    PageNavigation::redirectToPage("index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
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
        <div class="flex items-center space-x-4">
            <img src="img/iesalandalus.png" alt="Profile" class="rounded-full w-10 h-10">
            <a href="index.php" class="text-lg font-semibold hover:underline">
                <i class="fas fa-home mr-2"></i>Home
            </a>
        </div>
        <div class="flex items-center space-x-4">
            <?php if ($user): ?>
                <input type="text" value="<?= htmlspecialchars($user["email"]) ?>" readonly class="px-4 py-2 rounded-lg bg-white text-gray-800 border border-gray-300 focus:outline-none">
                <a href="logout.php" class="px-4 py-2 bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="px-4 py-2 bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none text-white">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <div class="w-1/3 mx-auto p-4 rounded-xl border-2 shadow-xl border-black mt-4">
            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                    <input type="text" id="title" name="title" placeholder="Ingrese el título"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?= SessionErrorHandler::displayError("title") ?>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Contenido</label>
                    <textarea id="content" name="content" rows="5"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Ingrese el contenido"></textarea>
                    <?= SessionErrorHandler::displayError("content") ?>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Categoría</label>
                    <select id="category" name="category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">__ Seleccione una categoría __</option>
                        <?php foreach (Category::fetchCategories() as $category): ?>
                            <option value="<?= $category["id"] ?>"><?= htmlspecialchars($category["name"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= SessionErrorHandler::displayError("category") ?>
                </div>
                <?php if ($user): ?>
                    <div class="mb-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="PUBLICADO" class="sr-only peer" name="status" id="status">
                            <div class="relative w-11 h-6 bg-red-500 rounded-full peer-checked:bg-green-500 transition-colors">
                                <div class="absolute top-[2px] left-[2px] bg-white border rounded-full w-5 h-5 transition-all peer-checked:translate-x-5"></div>
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-900">Publicado</span>
                        </label>
                    </div>
                <?php endif ?>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-paper-plane mr-2"></i> Enviar
                    </button>
                    <button type="reset" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </button>
                    <a href="index.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>