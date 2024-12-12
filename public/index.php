<?php

use App\Database\PostEntity;
use App\Utils\Categories;
use App\Utils\NotificationAlert;
use App\Utils\PostValidator;
use App\Utils\StatusPost;

session_start();
require __DIR__ . "/../vendor/autoload.php";

if (($user = $_SESSION["user"] ?? false) && isset($_POST["id"])) {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    if ($id && ($post = PostEntity::getPostById($id))) {
        $newStatus = ($post["status"] === StatusPost::Published->toString()) ? StatusPost::Draft->toString() : StatusPost::Published->toString();
        PostEntity::updateStatus($id, $newStatus);
    }
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
            <a href="new.php" class="px-4 py-2 bg-pink-500 rounded-lg hover:bg-pink-600 focus:outline-none text-white">
                <i class="fas fa-add mr-2"></i>NUEVO
            </a>
        </div>
    </nav>
    <main class="px-8 mx-auto">
        <div class="mt-4 w-full h-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
            <?php
            foreach (PostEntity::readPosts() as $post):
                if ($user || PostValidator::isPublishedPost($post["status"])):
                    $categoryColor = Categories::getColor($post["nombre"]);
                    $bgPostColor = ($post["status"] === StatusPost::Published->toString()) ? "green" : "red";
            ?>
                    </article>
                    <article class="w-full h-80 p-2 border-2 rounded-xl shadow-xl border-blue-600 bg-<?= $bgPostColor ?>-100 relative">
                        <div class="flex flex-col justify-center w-full h-full">
                            <div>
                                <h1 class="w-3/4 px-2 text-2xl text-teal-800 font-bold py-2 rounded-xl bg-gray-200
                            opacity-50"><?= $post["titulo"] ?></h1>
                            </div>
                            <div class="mt-4">
                                <p class="italic">
                                    <?= $post["contenido"] ?> </p>
                            </div>
                            <div class="mt-4 w-1/4 mx-auto">
                                <p class="font-bold text-white text-center px-2 py-1 rounded-xl bg-<?= $categoryColor ?>-500">
                                    <?= $post["nombre"] ?> </p>
                            </div>
                            <?php if ($user): ?>
                                <form class="mt-4 " action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                                    <input type='hidden' name='id' value="<?= $post["id"] ?>" />
                                    <button type='submit' class="font-bold text-white text-center px-2 py-1 rounded-xl bg-<?= $bgPostColor ?>-600 border-2 border-black">
                                        <i class="fas fa-redo mr-2"></i><?= $post["status"] ?> </button>
                                </form>
                                <div class="absolute bottom-2 right-2">
                                    <form action='delete.php' method="POST">
                                        <input type='hidden' name='id' value="<?= $post["id"] ?>" />
                                        <a href="update.php?id=<?= $post["id"] ?>">
                                            <i class="fas fa-edit text-blue-600 text-lg"></i>
                                        </a>
                                        <button type='submit' onclick="return confirm('¿Borrar definitivamente el Post?');">
                                            <i class="fas fa-trash text-lg text-red-600"></i>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </main>
</body>

<?= NotificationAlert::showAlert() ?>

</html>