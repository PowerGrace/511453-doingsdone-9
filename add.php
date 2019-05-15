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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $required = ['name', 'project'];
    $dict = ['name' => 'Название задачи', 'project' => 'Название проекта', 'date' => 'Дата выполнения'];

    foreach ($required as $key) {
		if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    //проверка даты

    if ($_POST['date'] !== '') {
        $current_time = strtotime('today');
        $deadline_time = $_POST['date'];
        if (strtotime($deadline_time) < $current_time) {
            $errors['date'] = 'Дата выполнения должна быть больше текущей дате';
        }
        if (!is_date_valid($deadline_time)) {
            $errors['date'] = 'Неправильный формат даты';
        }
    } else {
        $task['date'] = null;
    }

    //проверка указан ли проект

    $project_error = true;
    foreach ($projects as $val) {
        if ($val['id_name'] == $_POST['project']) {
            $project_error = false;
        }
    }
    if ($project_error) {
        $errors['project'] = "Пожалуйста, укажите тип проекта";
    }

    //загрузка файла

    $file = null;

    if ($_FILES['file']['error'] === UPLOAD_ERR_OK && !count($errors)) {
            $file_name = $_FILES['file']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);

            $file = $file_url;
        } else if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE){
            $errors['file'] ='Не удалось загрузить файл';
        }
    
    //запрос на добавление задачи
    
    if (!count($errors)) {
            $sql = 'INSERT INTO tasks(name, dt_creat, status, file, deadline, id_name, id_user)
                    VALUE(?,NOW(),0,?,?,?,2)';

            $result = db_insert_data($link, $sql, [$task['name'], $file, $task['date'], $task['project']]);
            if ($result) {
                header('Location: index.php');
            }   
    
    }
}

// рендеринг шаблона

$contentOfPage = include_template ('add.php', [
    'projects' => $projects,
    'task' => $task,
    'errors' => $errors
    ]);

$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage, 
    'projects' => $projects,
    'pageName' => 'Дела в Порядке',
    'userName' => 'Светлана Быстрова']);
    print($contentLayout);

?>