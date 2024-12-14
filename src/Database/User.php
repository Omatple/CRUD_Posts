<?php

namespace App\Database;

use App\Utils\AppConstants;
use \Faker\Factory;

class User extends QueryExecutor
{
    private int $id;
    private string $email;
    private string $password;

    public static function generateFakeUsers(int $count): void
    {
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $count; $i++) {
            $username = $faker->unique()->userName();
            $email = $username . "@" . $faker->freeEmailDomain();
            $password = $username . "2024.";
            (new User)
                ->setEmail($email)
                ->setPassword($password)
                ->saveUser();
        }
    }

    public function saveUser(): void
    {
        parent::insertRecord(AppConstants::TABLE_NAME_USERS, ["email", "password"], [
            $this->email,
            $this->password,
        ]);
    }

    public static function hasEntries(): bool
    {
        return parent::countTableEntries(AppConstants::TABLE_NAME_USERS);
    }

    public static function fetchUserByEmail(string $email): array|false
    {
        return parent::fetchByUniqueAttribute(AppConstants::TABLE_NAME_USERS, "email", $email);
    }

    public static function removeAllUsers(): void
    {
        parent::deleteAllRecords(AppConstants::TABLE_NAME_USERS);
    }

    public static function resetUserAutoIncrement(): void
    {
        parent::resetAutoIncrement(AppConstants::TABLE_NAME_USERS);
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }
}
