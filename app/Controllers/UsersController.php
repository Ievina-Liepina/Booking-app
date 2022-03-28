<?php
namespace App\Controllers;

use App\Database;
use App\Errors;
use App\Redirect;
use App\View;
use Doctrine\DBAL\Exception;
use JetBrains\PhpStorm\Pure;


class UsersController {

    #[Pure] 
    public function register()
    {
        if(!empty($_SESSION["login"]))
        {
            return new Redirect("/login");
        } else {

            return new View("register.html", [
                "error" => @$_SESSION["Errors"]
            ]);
        }
    }

    #[Pure] 
    public function login()
    {
        if(!empty($_SESSION["login"]))
        {
            return new Redirect("/home");
        } else {
            return new View("login.html", [
                "error" => @$_SESSION["Errors"]
            ]);
        }
    }
    public function logout() : Redirect
    {
        unset($_SESSION["login"]);
        return new Redirect("/login");
    }

    /**
     * @throws Exception
     */
    public function store(): Redirect
    {
        $dataBase = Database::connection();

        (new Errors())->registerValidation($_POST["name"], $_POST["surname"], $_POST["pwd"], $_POST["pwdRepeat"], $_POST["email"], $_POST["phoneNumber"]);


        if(empty($_SESSION["Errors"]))
        {
            $dataBase->insert('users', [
                "email" => $_POST["email"],
                "password" => password_hash($_POST["pwd"], PASSWORD_DEFAULT)
            ]);

            $idFromUsers = $dataBase->fetchAssociative('SELECT id FROM users WHERE email = ?', [$_POST["email"]]);

            $dataBase->insert('users_profile',
                [
                    "user_id" => $idFromUsers["id"],
                    "email" => $_POST["email"],
                    "name" => $_POST["name"],
                    "surname" => $_POST["surname"],
                    "phone_number" => $_POST["phoneNumber"]
                ]);

            return new Redirect("/login");
        } else {
            return new Redirect("/register");
        }

    }

    /**
     * @throws Exception
     */
    public function session(): Redirect
    {
        $dataBase = Database::connection();
        $userData = $dataBase->fetchAssociative('SELECT * FROM users WHERE email = ?', [$_POST["emailLogin"]]);

        (new Errors())->loginValidation($_POST["emailLogin"], $_POST["pwdLogin"]);

        if(empty($_SESSION["Errors"])) {

            $userProfile = $dataBase->fetchAssociative('SELECT * FROM users_profile WHERE user_id = ?', [$userData["id"]]);

            $_SESSION["login"] = ["id"=> $userProfile["user_id"], "name"=> $userProfile["name"], "surname"=> $userProfile["surname"]];

            return new Redirect("/home");
        } else {
            return new Redirect("/login");
        }
    }
}