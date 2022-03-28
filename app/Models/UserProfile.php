<?php
namespace App\Models;

class UserProfile {
    private int $id;
    private int $userId;
    private string $email;
    private string $name;
    private string $surname;
    private string $phoneNumber;

    public function __construct(int $id, int $userId, string $email, string $name, string $surname, string $phoneNumber)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->phoneNumber = $phoneNumber;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}