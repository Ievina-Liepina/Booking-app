<?php
namespace App\Controllers;
use App\Database;
use App\Errors;
use App\Models\DetailedApartmentInfo;
use App\Redirect;
use App\View;
use Doctrine\DBAL\Exception;
use JetBrains\PhpStorm\Pure;


class ApartmentsController {

    #[Pure] 
    public function create(): View
    {
        return new View("Apartments/create.html", ["error" => @$_SESSION["Errors"]]);
    }

    /**
     * @throws Exception
     */
    public function show($vars): View
    {

        //var_dump($_SESSION);
        $dataBase =  Database::connection();
        $apartment = $dataBase->fetchAssociative('SELECT * FROM apartments WHERE id = ?', [$vars["id"]]);

        $user = $dataBase->fetchAssociative(
            'SELECT name, surname, phone_number, email FROM users_profile WHERE user_id = ?', [$apartment["created_user_id"]]);
        $apartmentObject = new DetailedApartmentInfo(
            $apartment["id"],
            $user["name"],
            $user["surname"],
            $user["phone_number"],
            $user["email"],
            $apartment["country"],
            $apartment["address"],
            $apartment["description"],
            $apartment["rooms"],
            $apartment["available_from"],
            $apartment["available_to"],
            @$apartment["created_at"],
            number_format($apartment["price"], 2),
            $apartment["picture"]);

        $reservations = $dataBase->fetchAllAssociative(
            'SELECT day_from, day_to FROM reservations WHERE apartment_id = ?', [$vars["id"]]);



        return new View("Apartments/show.html", ["apartment" => @$apartmentObject, "error" => @$_SESSION["Errors"], "reservations" => @$reservations]);
    }

    /**
     * @throws Exception
     */
    public function list(): Redirect
    {
        $dataBase =  Database::connection();

        (new Errors())->listApartmentValidation(
            $_POST["country"],
            $_POST["address"],
            $_POST["description"],
            $_POST["rooms"],
            $_POST["availableFrom"],
            $_POST["availableTo"],
            $_POST["price"]);

        if(empty($_SESSION["Errors"])) {
            $fileName = uniqid("", true) .".".explode(".", $_FILES["picture"]["name"])[1];
            move_uploaded_file($_FILES["picture"]["tmp_name"], "Uploads/Apartments/". $fileName);

            $dataBase->insert('apartments',
                [
                    "created_user_id" => $_SESSION["login"]["id"],
                    "country" => $_POST["country"],
                    "address" => $_POST["address"],
                    "description" => $_POST["description"],
                    "rooms" => $_POST["rooms"],
                    "available_from" => $_POST["availableFrom"],
                    "available_to" => $_POST["availableTo"],
                    "price" => $_POST["price"],
                    "picture" => $fileName
                ]);

            return new Redirect("/home");
        } else {
            return new Redirect("/create");
        }


    }

    /**
     * @throws Exception
     */
    public function reserve($vars) : Redirect
    {
        //var_dump($_SESSION);
        (new Errors())->bookingValidation($vars["id"], $_POST["from"], $_POST["to"]);

        if(empty($_SESSION["Errors"])) {
            $dataBase = Database::connection();
            $apartmentPrice = $dataBase->fetchAssociative('SELECT price FROM apartments WHERE id = ?', [$vars["id"]]);

            $price = number_format(count(range(str_replace("-", "", $_POST["from"]), str_replace("-", "", $_POST["to"]))) * $apartmentPrice["price"], 2);

            $dataBase->insert('reservations',
                [
                    "user_id" => $_SESSION["login"]["id"],
                    "apartment_id" => $vars["id"],
                    "day_from" => $_POST["from"],
                    "day_to" => $_POST["to"],
                    "total_price" => $price
                ]);
            return new Redirect("/show/" . $vars["id"]);
        } else {
            return new Redirect("/show/".$vars["id"]);
        }
    }
    public function cancelReservation() {

    }
}