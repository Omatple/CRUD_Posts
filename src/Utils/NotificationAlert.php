<?php

namespace App\Utils;

class NotificationAlert
{
    public static function showAlert(): void
    {
        if ($message = $_SESSION["message"] ?? false) {
            echo <<< HTML
                <script>
                    Swal.fire({
                      position: "center",
                      icon: "success",
                      title: "$message",
                      showConfirmButton: false,
                      timer: 1000
                    });                    
                </script>
            HTML;
            unset($_SESSION["message"]);
        }
    }
}
