<?php

namespace App;

use App\Point;
use App\Response;

class EmailController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function displayForm()
    {

        $template = 'form.php';
        $code = 200;

        if (isset($_POST['email'])) {

            // Vérifier l'absence du mail en BDD

            $sql = "SELECT count(*) FROM subscription WHERE address = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $_POST['email']]);
            $number_of_rows = $stmt->fetchColumn();

            if ($number_of_rows > 0) {
                $error = "L'email existe déjà";
            }

            // Vérifier le format de l'email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Format d'email incorrect";
            }

            if (!isset($error)) {

                // Insérer l'email
                $sql = "INSERT INTO subscription (address) VALUES (:email)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['email' => $_POST['email']]);

                // // Retourner un code 201
                $code = 201;

            }
        }

        ob_start();
        $path = 'templates/'.$template;
        require($path);
        $content = ob_get_contents();
        ob_end_clean();

        $response = new Response(
            $content,
            ['Content-Type' => 'text/html'],
            $code
        );

        return $response;

    }

}
