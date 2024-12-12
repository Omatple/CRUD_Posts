<?php

namespace App\Database;

use App\Utils\Constants;
use \Faker\Factory;

class UserEntity extends QueryExecutor
{
    private int $id;
    private string $email;
    private string $pass;

    public static function generateFakeUsers(int $amount): void
    {
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $amount; $i++) {
            $prefixEmail = $faker->unique()->userName();
            $email = $prefixEmail . "@" . $faker->freeEmailDomain();
            $password = $prefixEmail . "2024.";
            (new UserEntity)
                ->setEmail($email)
                ->setPass($password)
                ->createUser();
        }
    }

    public function createUser(): void
    {
        parent::create(Constants::USERS_TABLE_NAME, ["email", "pass"], [
            $this->email,
            $this->pass,
        ]);
    }

    public static function getUserByEmail(string $email): array|false
    {
        return parent::getEntityByUniqueAttribute(Constants::USERS_TABLE_NAME, "email", $email);
    }

    public static function deleteAllUsers(): void
    {
        parent::deleteAllRegistres(Constants::USERS_TABLE_NAME);
    }

    public static function resetAutoIncrementId(): void
    {
        parent::resetIncremenntIds(Constants::USERS_TABLE_NAME);
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
     * Get the value of pass
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * Set the value of pass
     */
    public function setPass(string $pass): self
    {
        $this->pass = password_hash($pass, PASSWORD_BCRYPT);

        return $this;
    }
}
