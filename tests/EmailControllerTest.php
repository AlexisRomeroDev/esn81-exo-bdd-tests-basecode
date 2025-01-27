<?php

namespace Test;

use App\EmailController;
use PHPUnit\Framework\TestCase;

class EmailControllerTest extends TestCase
{
    protected static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$pdo->exec('CREATE TABLE `subscription` (
            `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `address` varchar(255) NOT NULL
          )');
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }

    public function test_invalid_adresse_return_message()
    {

        $_POST['email'] = '1234';

        $controller = new EmailController(self::$pdo);
        $response = $controller->displayForm();
        $this->assertStringContainsString("Format d'email incorrect", $response->getContent());
    }

    public function test_existing_adresse_return_message()
    {
        $this->markTestSkipped();
    }


    public function test_new_email_is_inserted_in_db()
    {

        $_POST['email'] = 'john@free.fr';

        $response = (new EmailController(self::$pdo))->displayForm();

        $this->assertEquals(201, $response->getStatusCode());

        $sql = "SELECT count(*) FROM subscription WHERE address = :email";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['email' => 'john@free.fr']);

        $this->assertEquals(1, $stmt->fetchColumn());

    }



}
