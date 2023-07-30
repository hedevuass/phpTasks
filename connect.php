
<?php
require_once 'people.php';
require_once 'peopleList.php';
require_once 'Task5.php';

$host = 'localhost';
$username = 'root';
$password = '1234';
$dbname = 'db_slmax';

$peopleTable = new PeopleTable();
$peopleTable->connect($host, $username, $password, $dbname);


/* сохранение новой записи */
/* class Person {
    public $first_name;
    public $last_name;
    public $birth_date;
    public $gender;
    public $birth_city;
}

$person = new Person();
$person->first_name = 'Jane';
$person->last_name = 'Dark';
$person->birth_date = '2000-10-15';
$person->gender = 0; 
$person->birth_city = 'Grodno';

if ($peopleTable->savePerson($person)) {
    echo "Запись успешно добавлена! <br>";
}
if($result_check === false){
    echo "Запись уже существует. <br>";
} 
else {
    echo "Ошибка при добавлении записи. <br>";
} */

/* присвоение идентификатора и удаление */
$personId = 2;

if ($peopleTable->deletePerson($personId)){
    echo "Запись успешно удалена. <br>";
} else{
    echo "Ошибка";
}

 /* получения всех людей и их возраста */
 $peopleWithAge = $peopleTable->getPeopleWithAge();
    
/* Вывод информации о людях и их возрасте */
 if (!empty($peopleWithAge)) {
     foreach ($peopleWithAge as $person) {
         echo " Имя: " . $person['first_name'] . ", Фамилия: " . $person['last_name'] . ", Возраст: " . $person['age'] . " лет" . ", Пол: " . $person['gender'] . "<br>";
     }
 } else {
     echo "Нет данных о людях в базе. <br>";
 }

$person = new stdClass();
$person->id = 1;
$person->first_name = 'John';
$person->last_name = 'Doe';
$person->birth_date = '1990-01-01';
$person->gender = 1;
$person->city = 'Minsk';

$formattedPerson = $peopleTable->formatPerson($person, true, true);

echo "<br> ID: " . $formattedPerson->id . "<br>";
echo "First Name: " . $formattedPerson->first_name . "<br>";
echo "Last Name: " . $formattedPerson->last_name . "<br>";
echo "Formatted Age: " . $formattedPerson->age . " years old" . "<br>";
echo "Formatted Gender: " . $formattedPerson->gender . "<br>";
echo "City: " . $formattedPerson->city . "<br>";

$person = new Person();

/* Создание нового человека */
/* $person->createOrUpdatePerson(null, 'Имя', 'Фамилия', '1990-01-01', 1, 'Город');

$person->createOrUpdatePerson(1, 'Новое имя', 'Новая фамилия', '1990-01-01', 0, 'Новый город');

$info = $person->getPersonById(1);
print_r($info); */

$peopleList = new PeopleList();
$searchFields = array(
    'first_name' => 'Jane',
    'last_name' => 'Dark'
);

if ($peopleList->searchPeople($searchFields)) {
    $foundIds = $peopleList->getPeopleIds();
    if (!empty($foundIds)) {
        echo "Найдены следующие ID людей: " . implode(', ', $foundIds);
    } else {
        echo "Поиск не дал результатов.";
    }
} else {
    echo "Ошибка при выполнении поиска.";
}

$peopleList = new PeopleList();
$searchFields = array(
    'first_name' => 'Имя',
    'last_name' => 'Фамилия'
);

if ($peopleList->searchPeople($searchFields)) {
    // Получаем массив экземпляров класса Person
    $peopleInstances = $peopleList->getPeopleInstances();

    // Проходимся по массиву экземпляров и выводим информацию о каждом человеке
    foreach ($peopleInstances as $person) {
        echo "ID: " . $person->getId() . ", Имя: " . $person->getFirstName() . ", Фамилия: " . $person->getLastName() . "<br>";
    }
} else {
    echo "Поиск не дал результатов.";
}

?>