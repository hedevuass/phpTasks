<?php
require_once 'people.php';
require_once 'Task5.php';

if (!class_exists('PeopleTable')) {
    echo 'Ошибка: Класс не определен.';
    exit(); 
}

class PeopleList
{
    private $conn;
    private $peopleIds;

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

    public function searchPeople($fields)
    {
        if (empty($fields)) {
            return false;
        }

        $person = new PeopleTable();

        $condition = array();
        foreach ($fields as $field => $value) {
            $condition[] = "$field = ?";
        }
        $condition = implode(' AND ', $condition);

        try {
            $stmt = $this->conn->prepare("SELECT id FROM people WHERE $condition");
            $stmt->execute(array_values($fields));

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->peopleIds[] = $row['id'];
            }

            return true;
        } catch (PDOException $e) {
            echo 'Ошибка запроса: ' . $e->getMessage();
            return false;
        }
    }

    public function getPeopleInstances()
    {
        $peopleInstances = array();
        $peopleTable = new Person(); // Создаем экземпляр класса PeopleTable

        if (is_array($this->peopleIds) && !empty($this->peopleIds)) {
            foreach ($this->peopleIds as $personId) {
                $personData = $peopleTable->getPersonById($personId); // Используем метод getPersonById из класса PeopleTable

                if ($personData) {
                    // Создаем экземпляр класса Person на основе полученных данных
                    $personInstance = new Person();
                    $personInstance->createOrUpdatePerson(
                        $personData['id'],
                        $personData['first_name'],
                        $personData['last_name'],
                        $personData['birth_date'],
                        $personData['gender'],
                        $personData['birth_city']
                    );

                    // Добавляем экземпляр класса Person в массив $peopleInstances
                    $peopleInstances[] = $personInstance;
                }
            }
        }

        return $peopleInstances;
    }
    public function getPeopleIds()
    {
        return $this->peopleIds ?? array(); 
    }
}

?>
