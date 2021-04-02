<?php

class Database {
    protected $connection; // zmienna przechowujaca połaczenie do bazy

    // Konstruktor klasy Database przyjmujący dane do połączenia z bazą
    function __construct($dbhost, $dbuser, $dbpass) {
        // zainicjowanie połaczenia z baza danych.
        $this->connection = @new mysqli($dbhost, $dbuser, $dbpass);
        if($this->connection->connect_error) {
            die('Failed to connect to database: '.$this->connection->connect_error);
        }
        // Jeśli nie udało się połaczyć z baza danych, zakończ działanie i wyświetl komunikat

        // utwożenie bazy danych jeśli nie istnieje
        $this->query("CREATE DATABASE IF NOT EXISTS zadanie");

        // wybranie bazy danych na te utworzona wyżej
        $this->connection->select_db('zadanie') or die($this->connection->error);
        // jeśli nie udało się wybrać bazy danych, zakończy działnie i wyświetli komunikat.


        // stworzenie odpowiedniej tabeli
        $this->query("CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            numberOfOccurrences INT NOT NULL DEFAULT 1,
            counter INT NOT NULL DEFAULT 0

        )");
    }

    public function insert($email) {
            // Wprowadzenie danych do bazy
            $this->query("INSERT INTO users (email) VALUES ('$email')");
            
            $domainStr = substr($email, strpos($email, '@') +1);
            $result = $this->fetch("SELECT user_id, (SUBSTRING_INDEX(SUBSTR(email, INSTR(email, '@') + 1),' ', 1)) 
                                AS domain, counter
                                FROM users 
                                HAVING domain = '".$domainStr."'", FALSE);

            if($result) {
                $this->query("UPDATE users SET numberOfOccurrences = '".count($result)."' 
                                WHERE email 
                                LIKE '%@".$domainStr."'");

                if(count($result) > 1) {
                    $newCounter = 0;
                    for($x = 0; $x <= count($result) - 1; $x++) {
                        if($x > 0 ) {
                            $this->query("UPDATE users SET counter=".$newCounter." 
                                        WHERE email LIKE '%@".$domainStr."' AND user_id = '".$result[$x][0]."'");
                        }
                        $newCounter = $newCounter + 1;

                    }
                }

            }
            
    }

    public function fetchAll() {
        $this->query("SELECT * FROM users");
    }

    public function fetch($query, $asoc = TRUE) {
        $result = $this->connection->query($query) or
            die("Error fetching data: ".$this->connection->error);

        if($asoc) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return $result->fetch_all();
        
    }

    public function query($query) {
        $this->connection->query($query) or 
                die("Error executing query: ".$this->connection->error);
    }

}
?>
