<?php
namespace App\Controllers;

use App\Redirect;
use App\View;
use Doctrine\DBAL\Exception;
use JetBrains\PhpStorm\Pure;

class WebsiteController {

    /**
     * @throws Exception
     */
    public function index(): View
    {
        //var_dump($_SESSION);
        $apartments = (new ModelArrayController)->ApartmentInfoArray();

        return new View("Apartments/home.html", ["apartments"=> $apartments]);
    }

    /**
     * @throws Exception
     */
    public function reserved(): View
    {
        $apartments = (new ModelArrayController)->ApartmentInfoReserved();
        return new View("Apartments/reserved.html", ["apartments"=> $apartments]);
        
    }

    #[Pure] 
    public function send(): Redirect
    {
        return new Redirect("/login");
    }
}