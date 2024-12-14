<?php

namespace App\Utils;

class AlertNotification
{
    public static function displayAlert(): void
    {
        if ($alertMessage = $_SESSION["alertMessage"] ?? false) {
            echo <<<HTML
                <script>
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "$alertMessage",
                        showConfirmButton: false,
                        timer: 1000
                    });
                </script>
            HTML;
            unset($_SESSION["alertMessage"]);
        }
    }
}
