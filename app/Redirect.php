<?php
namespace App;

class Redirect {

    private string $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function getLocation(): string {
        return $this->location;
    }
}