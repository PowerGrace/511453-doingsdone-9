<?php
require_once ('helpers.php');

$show_complete_tasks = rand(0, 1);

//$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

/*$tasks = [
    ['purpose' => 'Собеседование в IT компании',
     'execution_date' => '01.12.2019',
     'category' => 'Работа',
     'done' => false],
    ['purpose' => 'Выполнить тестовое задание',
    'execution_date' => '25.12.2018',
    'category' => 'Работа',
    'done' => false],
    ['purpose' => 'Сделать задание первого раздела',
    'execution_date' => '21.12.2018',
    'category' => 'Учёба',
    'done' => true],
    ['purpose' => 'Встреча с другом',
    'execution_date' => '22.12.2018',
    'category' => 'Входящие',
    'done' => false],
    ['purpose' => 'Купить корм для кота',
    'execution_date' => null,
    'category' => 'Домашние дела',
    'done' => false],
    ['purpose' => 'Заказать пиццу',
    'execution_date' => null,
    'category' => 'Домашние дела',
    'done' => false]
];*/

// подключение к БД

$link = mysqli_connect ('localhost', 'root', '', 'doingsdone');

// кодировка

mysqli_set_charset($link, 'utf8');

// проверка подключения

if ($link == false) {
    print ('Ошибка подключения:' . mysqli_connect_error());
} else {
    // запрос на получение списка проектов для пользователя id = 2
    $sql_projects = "SELECT title AS category 
    FROM projects 
    JOIN users 
    ON projects.id_user = users.id_user
     WHERE projects.id_user = 2";

    // запрос на получение списка задач для пользователя id = 2

    $sql_tasks = "SELECT name AS purpose, deadline AS execution_date, status AS done, title AS category
    FROM tasks 
    JOIN users 
    ON tasks.id_user = users.id_user 
    JOIN projects
    ON projects.id_name = tasks.id_name
    WHERE users.id_user = 2";

    //получение ресурса результата

    $res_projects = mysqli_query($link, $sql_projects);

    $res_tasks = mysqli_query($link, $sql_tasks);

    //проверка запросов

    if ($res_projects) {
        $projects = mysqli_fetch_all($res_projects, MYSQLI_ASSOC);
    } else {
        $error_query = mysqli_error($link);
        echo ($error_query);
    }

    
    if ($res_tasks) {
        $tasks = mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);
    } else {
        $error_query = mysqli_error($link);
        echo ($error_query);
    }

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