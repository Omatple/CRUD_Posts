<?php

namespace App\Utils;

use App\Database\CategoryEntity;
use App\Database\PostEntity;

class PostValidator
{
    public static function isPublishedPost(string $statusPost): bool
    {
        return StatusPost::Published->toString() === $statusPost;
    }

    public static function isValidTitle(string $title): bool
    {
        return InputValidator::isLengthWithInLimit($title, "title", Constants::TITLE_ALLOWED_MIN_CHARS, Constants::TITLE_ALLOWED_MAX_CHARS);
    }

    public static function isValidContent(string $content): bool
    {
        return InputValidator::isLengthWithInLimit($content, "content", Constants::CONTENT_ALLOWED_MIN_CHARS, Constants::CONTENT_ALLOWED_MAX_CHARS);
    }

    public static function isValidCategory(int $categoryId): bool
    {
        if (!in_array($categoryId, CategoryEntity::getIdsCategory())) {
            $_SESSION["error_category"] = "Please select a valid category type.";
            return false;
        }
        return true;
    }

    public static function isUniqueTitle(string $title, ?int $id = null): bool
    {
        if (!PostEntity::isTitleUnique($title, $id)) {
            $_SESSION["error_title"] = "Title is already in use.";
            return false;
        }
        return true;
    }
}
