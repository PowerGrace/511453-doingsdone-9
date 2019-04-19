<?php
require_once ('helpers.php');

$show_complete_tasks = rand(0, 1);
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    ['purpose' => 'Собеседование в IT компании',
     'execution_date' => '01.12.2018',
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
];

function numberOfTasks ($tasksList, $projectName) {
    $quantity = 0;
    foreach ($tasksList as $val) {
        if(isset($val['category']) && $val['category'] === $projectName) {
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

function coutingTime ($val) {
    if ($val) {
        $finHour = strtotime($val);
        $hoursCount = $finHour - time();
        
        return $hoursCount;  
    }
};

?>