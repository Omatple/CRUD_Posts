<?php

namespace App\Utils;

class SessionErrorHandler
{
    public static function displayError(string $errorKey): void
    {
        if ($errorMessage = $_SESSION["error_$errorKey"] ?? false) {
            echo "<p class='mt-2 text-red-600 text-sm italic'>" . htmlspecialchars($errorMessage) . "</p>";
            unset($_SESSION["error_$errorKey"]);
        }
    }
}
