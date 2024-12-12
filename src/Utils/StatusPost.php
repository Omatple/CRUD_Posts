<?php

namespace App\Utils;

enum StatusPost: string
{
    case Published = "PUBLICADO";
    case Draft = "BORRADOR";

    public function toString(): string
    {
        return $this->value;
    }
}
