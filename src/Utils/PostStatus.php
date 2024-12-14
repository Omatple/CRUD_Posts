<?php

namespace App\Utils;

enum PostStatus: string
{
    case Published = "PUBLICADO";
    case Draft = "BORRADOR";

    public function getStatusLabel(): string
    {
        return $this->value;
    }
}
