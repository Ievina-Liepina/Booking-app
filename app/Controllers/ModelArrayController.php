<?php
namespace App\Controllers;
use App\Database;
use App\Models\DetailedApartmentInfo;
use Doctrine\DBAL\Exception;

class ModelArrayController {

    /**
     * @throws Exception
     */
    public function ApartmentInfoArray(): array
    {
        $dataBase =  Database::connection();
        $apartments = $dataBase->fetchAllAssociative('SELECT * FROM apartments');
        $detailedApartmentInfo = [];
        foreach ($apartments as $construct) {
            $idFromUser = $dataBase->fetchAssociative(
                'SELECT name, surname, phone_number, email FROM users_profile WHERE user_id = ?', [$construct["created_user_id"]]);
            $detailedApartmentInfo[] = new DetailedApartmentInfo(
                $construct["id"],
                $idFromUser["name"],
                $idFromUser["surname"],
                $idFromUser["phone_number"],
                $idFromUser["email"],
                $construct["country"],
                $construct["address"],
                $construct["description"],
                $construct["rooms"],
                $construct["available_from"],
                $construct["available_to"],
                @$construct["created_at"],
                number_format(@$construct["price"], 2),
                $construct["picture"]);
        }
        return $detailedApartmentInfo;

    }

    /**
     * @throws Exception
     */
    public function getReservedAepartments(): array
    {
        $userID = $_SESSION['login']['id'];
        $dataBase =  Database::connection();
        $apartments = $dataBase->fetchAllAssociative('SELECT * FROM `reservations` WHERE user_id="' . $userID . '" ');

        $output = [];
        foreach($apartments as $row){
            $output[] = $row['apartment_id'];
        }

        return $output;
    }

    /**
     * @throws Exception
     */
    public function ApartmentInfoReserved(): array
    {
        $dataBase =  Database::connection();
        $apartments = $this -> getReservedAepartments();
        //var_dump($apartments);

        $detailedApartmentInfo = [];
        foreach ($apartments as $appartmentID) {
            $idFromUser = $dataBase->fetchAssociative(
                'SELECT name, surname, phone_number, email FROM users_profile WHERE user_id = ?', [$_SESSION['login']['id']]);
            $construct = $dataBase->fetchAllAssociative('SELECT * FROM apartments WHERE id= ? . $appartmentID . "" LIMIT 1')[0];
            //var_dump($construct);
            $detailedApartmentInfo[] = new DetailedApartmentInfo(
                $construct["id"],
                $idFromUser["name"],
                $idFromUser["surname"],
                $idFromUser["phone_number"],
                $idFromUser["email"],
                $construct["country"],
                $construct["address"],
                $construct["description"],
                $construct["rooms"],
                $construct["available_from"],
                $construct["available_to"],
                @$construct["created_at"],
                number_format(@$construct["price"], 2),
                $construct["picture"]);
        }
        return $detailedApartmentInfo;

    }
}