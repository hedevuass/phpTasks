<?php

class PeopleTable {
    private $conn;


    /* Конструктор - соединение с БД */
    public function connect($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        /* Проверка на ошибку соединения */
        if ($this->conn->connect_error) {
            die("Ошибка соединения: " . $this->conn->connect_error);
        }
    }

    /* 1 */
    public function savePerson($person) {
        if (!empty($person->first_name) && !empty($person->last_name) && !empty($person->birth_date) && isset($person->gender) && !empty($person->birth_city)) {
            $first_name = $this->conn->real_escape_string($person->first_name);
            $last_name = $this->conn->real_escape_string($person->last_name);

            $sql_check = "SELECT COUNT(*) AS count FROM people WHERE first_name = '$first_name' AND last_name = '$last_name'";
            $result_check = $this->conn->query($sql_check);

            if ($result_check && $result_check->fetch_assoc()['count'] > 0) {
                return false; // Запись уже существует, возвращаем false
            }

            $birth_date = $this->conn->real_escape_string($person->birth_date);
            $gender = intval($person->gender);
            $birth_city = $this->conn->real_escape_string($person->birth_city);

            $sql = "INSERT INTO people (first_name, last_name, birth_date, gender, birth_city)
                    VALUES ('$first_name', '$last_name', '$birth_date', '$gender', '$birth_city')";

            if ($this->conn->query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 2 */
    public function deletePerson($personId){
        if(!empty($personId)){
            $id = intval($personId);

            $sql = "DELETE FROM people WHERE id = $id";

            if ($this->conn->query($sql) === TRUE){ return true; } 
            else { return false; }
        }
    }

    /* 3 */
    /* Вычисление возраста */
    public static function calculateAge($birth_date) {
        $current_date = new DateTime();
        $birthdate = new DateTime($birth_date);
        $age = $current_date->diff($birthdate)->y;
        return $age;
    }

    /* 4 */
    /* конвертацаия гендера */
    public static function convertGender($gender) {
        return ($gender == 1) ? 'муж' : 'жен';
    }

    public function getPeopleWithAge() {
        $sql = "SELECT first_name, last_name, birth_date, gender FROM people";
        $result = $this->conn->query($sql);

        $peopleWithAge = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $age = self::calculateAge($row['birth_date']); // Вычисляем возраст с помощью статического метода
                $row['age'] = $age; // Добавляем возраст к данным о человеке
                $row['gender'] = self::convertGender($row['gender']);
                $peopleWithAge[] = $row;
            }
        }

        return $peopleWithAge;
    }

    /* 6 */
    public function formatPerson($person, $formatAge = false, $formatGender = false) {
        $formattedPerson = new stdClass();
        $formattedPerson->id = $person->id;
        $formattedPerson->first_name = $person->first_name;
        $formattedPerson->last_name = $person->last_name;
        $formattedPerson->birth_date = $person->birth_date;
        $formattedPerson->gender = $person->gender;
        $formattedPerson->city = $person->city;

        if ($formatAge) {
            $formattedPerson->age = $this->calculateAge($person->birth_date);
        }

        if ($formatGender) {
            $formattedPerson->gender = self::convertGender($person->gender);
        }

        return $formattedPerson;
    }

    /* Закрываем соединение */
    public function closeConnection() {
        $this->conn->close();
    }
}
