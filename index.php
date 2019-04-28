<?php
require_once ('helpers.php');

$show_complete_tasks = rand(0, 1);

// подключение к БД

$link = mysqli_connect ('localhost', 'root', '', 'doingsdone');

// кодировка

mysqli_set_charset($link, 'utf8');

$projects = [];
$tasks = [];

// проверка подключения

if ($link === false) {
    die ('Ошибка подключения:' . mysqli_connect_error());
} else {
    // запрос на получение списка проектов для пользователя id = 2
    $sql_projects = "SELECT title AS category 
    FROM projects  
    WHERE projects.id_user = 2";

    // запрос на получение списка задач для пользователя id = 2

    $sql_tasks = "SELECT name AS purpose, deadline AS execution_date, status AS done, title AS category, file
    FROM tasks 
    JOIN projects
    ON projects.id_name = tasks.id_name
    WHERE projects.id_user = 2";

    //получение ресурса результата

    $res_projects = mysqli_query($link, $sql_projects);

    $res_tasks = mysqli_query($link, $sql_tasks);

    //проверка запросов

    if ($res_projects === false && $res_tasks === false) {
        die ('Ошибка при выполнении SQL запроса: ' . mysqli_error($link));
    }
        
    $projects = mysqli_fetch_all($res_projects, MYSQLI_ASSOC);
    
    $tasks = mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    

};

function numberOfTasks ($tasksList, $projectName) {
    $quantity = 0;
    foreach ($tasksList as $val) {
        if (isset($val['category']) && ($val['category']) === $projectName) {
            $quantity++;
        }
    }
    return $quantity; 
};

$contentOfPage = include_template ('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks
    ]);
$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage, 
    'projects' => $projects,
    'tasks' => $tasks,
    'userName' => 'Светлана Быстрова',
    'pageName' => 'Дела в Порядке']);
print($contentLayout);

function isDateImportant ($val) {
    $finHour = strtotime($val);
    $hoursCount = $finHour - time();
        if ($hoursCount <= 86400 && $val !== null) {
            return true;
        }
    return false;
};

?>