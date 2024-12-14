<?php

namespace App\Utils;

use App\Database\Category;
use App\Database\Post;

class PostValidator
{
    public static function isPostPublished(string $postStatus): bool
    {
        return PostStatus::Published->getStatusLabel() === $postStatus;
    }

    public static function isTitleValid(string $title): bool
    {
        return FormInputValidator::validateLength(
            $title,
            "title",
            AppConstants::TITLE_MIN_LENGTH,
            AppConstants::TITLE_MAX_LENGTH
        );
    }

    public static function isContentValid(string $content): bool
    {
        return FormInputValidator::validateLength(
            $content,
            "content",
            AppConstants::CONTENT_MIN_LENGTH,
            AppConstants::CONTENT_MAX_LENGTH
        );
    }

    public static function isCategoryValid(int $categoryId): bool
    {
        if (!in_array($categoryId, Category::fetchCategoryIds())) {
            $_SESSION["error_category"] = "Please select a valid category.";
            return false;
        }
        return true;
    }

    public static function isTitleUnique(string $title, ?int $postId = null): bool
    {
        if (!Post::isTitleUnique($title, $postId)) {
            $_SESSION["error_title"] = "The title is already in use.";
            return false;
        }
        return true;
    }
}
