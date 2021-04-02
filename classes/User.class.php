<?php

class User {
    private $id;

    private $name;
    private $username;
    private $email;

    private $address = array();
    private $phone;
    private $website;
    private $company = array();

    function setFromJSON($json) {
        // funkcja przyjmujaca string'a w formacie json
        // Konwertuje jsonArray na odpowiednie atrybuty klasy User
        $jsonArray = json_decode($json, true);
        foreach($jsonArray as $key=>$value){
           $this->$key = $value;
        }
    }

    function getDomain() {
        // Zwrócenie samej domeny, czyli wszystkich znaków po @
        // + 1 jest po to aby ominąć znak @, inaczej zwraca z tym znakiem
        return substr($this->email, strpos($this->email, '@') + 1);
    }

    function getPersonData() {
        // Konwersja obiektu klasy User na obiekt string w formacie json
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
        
    }

    function getFullName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getCompanyInfo() {
        return $this->company;
    }

    function getUsername() {
        return $this->username;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setWebsite($website) {
        $this->website = $phonwebsitee;
    }
}

?>