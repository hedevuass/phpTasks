<?php
class Person
{
    private $conn;

    public function __construct()
    {
        $host = 'localhost';
        $username = 'root';
        $password = '1234';
        $dbname = 'db_slmax';

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Ошибка подключения к БД: ' . $e->getMessage();
        }
    }

    public function createOrUpdatePerson($id = null, $first_name, $last_name, $birth_date, $gender, $birth_city)
    {
        if (empty($first_name) || empty($last_name) || empty($birth_date) || !in_array($gender, [0, 1]) || empty($birth_city)) {
            return false;
        }

        try {
            if ($id) {
                $stmt = $this->conn->prepare("UPDATE people SET first_name = ?, last_name = ?, birth_date = ?, gender = ?, birth_city = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $birth_date, $gender, $birth_city, $id]);
            } else {
                $stmt = $this->conn->prepare("INSERT INTO people (first_name, last_name, birth_date, gender, birth_city) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $birth_date, $gender, $birth_city]);
            }

            return true;
        } catch (PDOException $e) {
            echo 'Ошибка запроса: ' . $e->getMessage();
            return false;
        }
    }

    public function getPersonById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM people WHERE id = ?");
            $stmt->execute([$id]);

            $person = $stmt->fetch(PDO::FETCH_ASSOC);

            return $person;
        } catch (PDOException $e) {
            echo 'Ошибка запроса: ' . $e->getMessage();
            return null;
        }
    }
}

?>
