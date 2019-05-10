<?php
require_once('functions.php');
require_once('helpers.php');

// подключение к БД

$link = mysqli_connect ('localhost', 'root', '', 'doingsdone');

// кодировка

mysqli_set_charset($link, 'utf8');


// проверка подключения

if ($link === false) {
    die ('Ошибка подключения:' . mysqli_connect_error());
}

$projects = [];
$id_user = 2;

$sql_projects = "SELECT projects.id_name, title AS category, COUNT(tasks.id_name) tasks_count
    FROM projects
    JOIN tasks
    ON tasks.id_name = projects.id_name
    WHERE projects.id_user = ?
    GROUP BY projects.id_name";

$values = [$id_user];

$projects = db_fetch_data($link, $sql_projects, $values);

//валидация формы в добавлении задачи

$task = [
    'name'    => $_POST['name'] ?? null,
    'project' => $_POST['project'] ?? null,
    'date'    => $_POST['date'] ?? null,
    'file'    => $_POST['file'] ?? null
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];
    
    $required = ['name', 'project', 'data'];
    $dict = ['name' => 'Название задачи', 'project' => 'Название проекта', 'data' => 'Дата выполнения'];

    foreach ($required as $key) {
		if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    //проверка даты

    if ($_POST['date'] !== '') {
        $current_time = strtotime('now');
        $deadline_time = $_POST['date'];
        if (strtotime($deadline_time) <= $current_time) {
            $errors['date'] = 'Дата выполнения должна быть больше или равна текущей дате';
        }
        if (!is_date_valid($deadline_time)) {
            $errors['date'] = 'Неправильный формат даты';
        }
    } 

    //проверка указан ли проект

    $project_error = true;
    foreach ($projects as $val) {
        if ($val['category'] == $_POST['project']) {
            $project_error = false;
        }
    }
    if ($project_error) {
        $errors["project"] = "Пожалуйста, укажите тип проекта";
    }

    //загрузка файла

    if (isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
            $file = $file_url;
        }else {
            $errors['file'] = 'Вы не загрузили файл';
        }


    if (!count($errors)) {
            $sql = 'INSERT INTO tasks SET name = ?, id_name = ?, id_user = 2, file = ?'; 
        }   
    
    }

// рендеринг шаблона

$contentOfPage = include_template ('add.php', [
    'projects' => $projects,
    'task' => $task
    ]);

$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage, 
    'projects' => $projects,
    'pageName' => 'Дела в Порядке',
    'userName' => 'Светлана Быстрова']);
    print($contentLayout);

?>