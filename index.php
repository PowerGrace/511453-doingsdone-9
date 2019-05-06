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
$id_name = '';

//получаем ссылку
$params = $_GET;
$params['id_name'] = '';
/*$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$query = http_build_query($params);
$url = '/' . $scriptname . '?' . $query;*/


// проверка подключения

if ($link === false) {
    die ('Ошибка подключения:' . mysqli_connect_error());
} else {
        
    // запрос на получение списка проектов для пользователя id = 2
    $sql_projects = "SELECT title AS category
    FROM projects"; 
    /*WHERE projects.id_user = 2";*/

    // запрос на получение списка задач для пользователя id = 2

    $sql_tasks = "SELECT name AS purpose, deadline AS execution_date, status AS done, title AS category, file
    FROM tasks 
    JOIN projects
    ON projects.id_name = tasks.id_name";
    /*WHERE projects.id_user = 2";*/

    //получение ресурса результата

    $res_projects = mysqli_query($link, $sql_projects);

    $res_tasks = mysqli_query($link, $sql_tasks);

    //проверка запросов

    if ($res_projects === false && $res_tasks === false) {
        die ('Ошибка при выполнении SQL запроса: ' . mysqli_error($link));
    }
        
    $projects = mysqli_fetch_all($res_projects, MYSQLI_ASSOC);
    
    $tasks = mysqli_fetch_all($res_tasks, MYSQLI_ASSOC);

}

if (isset($_GET['id_name'])) {

if($_GET(['id_name'] === '')) {
    @require_once($_SERVER['DOCUMENT_ROOT'].'/404.php');
    exit();
    }
    
$id_name = $_GET['id_name'];
$id_user = 2;

$sql_id_name = $sql_tasks . 'WHERE tasks.id_name = ? AND tasks.id_user = ?';

$stmt = db_get_prepare_stmt($link, $sql_id_name, [$id_name, $id_user]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($result === false) {
    exit('Ошибка при попытке получить результат prepared statement: ' . mysqli_stmt_error($stmt));}
        
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (empty($tasks)) {
        @require_once($_SERVER['DOCUMENT_ROOT'].'/404.php');
        exit();
    }
}


$contentOfPage = include_template ('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks
    ]);
$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage, 
    'projects' => $projects,
    'tasks' => $tasks,
    'active_project' => $id_name,
    'userName' => 'Светлана Быстрова',
    'pageName' => 'Дела в Порядке']);
print($contentLayout);



?>