<?php
require_once ('helpers.php');
require_once ('functions.php');

$show_complete_tasks = rand(0, 1);

// подключение к БД

$link = mysqli_connect ('localhost', 'root', '', 'doingsdone');

// кодировка

mysqli_set_charset($link, 'utf8');

$projects = [];
$tasks = [];
$id_user = '';


// проверка подключения

if ($link === false) {
    die ('Ошибка подключения:' . mysqli_connect_error());
} 
        
    $id_user = 2;
    $projectId = null;

if (isset($_GET['id_name']) && $_GET['id_name'] === '') {
    http_response_code(404);
    require_once '404.php';
    exit();
}

if (isset($_GET['id_name']) && $_GET['id_name'] !== '') {
    $projectId = (int)$_GET['id_name']; // В $_GET всегда содержатся строки, а нам нужно число
}

$sql_tasks = 'SELECT name AS purpose, deadline AS execution_date, status AS done, title AS category, file
    FROM tasks 
    JOIN projects
    ON projects.id_name = tasks.id_name
    WHERE tasks.id_user = ?';

$sql_projects = 'SELECT  title AS category
    FROM projects
    JOIN tasks
    ON projects.id_name = tasks.id_name
    WHERE tasks.id_user = ?';

$values = [$id_user];

if ($projectId !== null) {
    $sql_tasks .= ' AND tasks.id_name = ?';
    $values[]  = $projectId;
}
$projects = db_fetch_data($link, $sql_projects, $values);

$tasks = db_fetch_data($link, $sql_tasks, $values);

if (empty($tasks)) {
    require_once '404.php';
    exit();
}



$contentOfPage = include_template ('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks
    ]);
$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage, 
    'projects' => $projects,
    'tasks' => $tasks,
    'userName' => 'Светлана Быстрова',
    'pageName' => 'Дела в Порядке']);
print($contentLayout);

?>