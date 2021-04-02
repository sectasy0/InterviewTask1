<?php
    require_once 'vendor/autoload.php';

    require_once 'classes/User.class.php';
    require_once 'classes/Database.class.php';

    use chillerlan\QRCode\QRCode;

    $db = new Database('127.0.0.1', 'root', ''); // Połączenie z baza danych
?>
<!DOCTYPE html>
<html lang="pl">
<html>
    <head>
        <meta charset="utf-8">
        <title>Zadanie rekrutacyjne - Piotr Markiewicz</title>
        <meta name="description" content="Zadanie rekrutacyjne Markiewicz Piotr">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Piotr Markiewicz">

        <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body data-new-gr-c-s-check-loaded="14.1002.0" data-gr-ext-installed="">
        <div class="site-wrapper">
            <?php
                $client = new GuzzleHttp\Client();
                
                // Http GET request na podany w dokumencie url w celu pobrania potrzebnych danych
                $json = $client->request('GET', 'https://jsonplaceholder.typicode.com/users/1');
                $user = new User(); // Utworzenie instancji klasy User
                $user->setFromJSON($json->getBody()->read(1024)); // Załadowanie danych z json

                $jsonUserData = $user->getPersonData();

                // Do poprawnego działania tej biblioteki musi być włączone rozszerzenie gd w pliku php.ini
                // lub w przypadku systemów unixopodobnych doinstalowany pakiet php-gd
                $qrcode = new QRCode();

                // opcjonalnie można zapisać wygenerowany kod QR do pliku np .png
                $qrcode->render($jsonUserData, 'PersonalQR.png');

                $db->insert($user->getEmail());

                echo "<pre>";
                echo $jsonUserData; // wyświetlenie danych użytkownika w formacie json
                echo "</pre>";
                echo "<br>";
                // renderowanie i wyświetlenie kodu QR w formie base64 na podstawie danych json
                echo $renderedQR = '<img src="'.($qrcode)->render($jsonUserData).'" alt="Person data QR Code">';
            ?>
        </div>
    </body>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js">
</html>
