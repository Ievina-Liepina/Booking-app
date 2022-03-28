<?php
namespace App\Models;
class DetailedApartmentInfo {
    private $name;
    private $surname;
    private $country;
    private $address;
    private $description;
    private $rooms;
    private $availableFrom;
    private $availableTo;
    private $createdAt;
    private $phoneNumber;
    private $email;
    private $id;
    private $price;
    private $picture;

    public function __construct($id, $name, $surname, $phoneNumber, $email, $country, $address, $description, $rooms, $availableFrom, $availableTo, $createdAt, $price, $picture)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->country = $country;
        $this->address = $address;
        $this->description = $description;
        $this->rooms = $rooms;
        $this->availableFrom = $availableFrom;
        $this->availableTo = $availableTo;
        $this->createdAt = $createdAt;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->id = $id;
        $this->price = $price;

        $this->picture = $picture;
    }


    public function getName()
    {
        return $this->name;
    }


    public function getSurname()
    {
        return $this->surname;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function getAddress()
    {
        return $this->address;
    }


    public function getAvailableFrom()
    {
        return $this->availableFrom;
    }


    public function getAvailableTo()
    {
        return $this->availableTo;
    }


    public function getCountry()
    {
        return $this->country;
    }


    public function getRooms()
    {
        return $this->rooms;
    }


    public function getEmail()
    {
        return $this->email;
    }


    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }


    public function getPicture()
    {
        return $this->picture;
    }
}