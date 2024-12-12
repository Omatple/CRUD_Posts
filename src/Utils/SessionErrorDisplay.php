<?php

namespace App\Utils;

class SessionErrorDisplay
{
    public static function showError(string $errorName): void
    {
        if ($errorMessage = $_SESSION["error_$errorName"] ?? false) {
            echo "<p class='mt-2 text-red-600 text-sm italic'>" . $errorMessage . "</p>";
            unset($_SESSION["error_$errorName"]);
        }
    }
}
