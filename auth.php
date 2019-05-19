<?php
require_once('functions.php');
require_once('helpers.php');

    $errors = [];
    $values = [];
    $form = [];

// подключение к БД

$link = mysqli_connect ('localhost', 'root', '', 'doingsdone');

// кодировка

mysqli_set_charset($link, 'utf8');

// проверка подключения

if ($link === false) {
    die ('Ошибка подключения:' . mysqli_connect_error());
}
    
    session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $val) {
        if (empty($_POST[$val])) {
            $errors[$val] = 'Поле не заполнено';
        }
    }

    $email = mysqli_real_escape_string($link, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $contentOfPage = include_template('auth.php', ['errors' => $errors]);
        $contentLayout = include_template('layout.php', [
			'contentOfPage' => $contentOfPage,
			'pageName' => 'Дела в Порядке. Авторизация'
		]);
    }  else {
        $contentOfPage = include_template('index.php', [
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks
          ]);
        $contentLayout = include_template('layout.php', [
            'projects' => $projects,
            'contentOfPage' => $contentOfPage,
            'user' => $_SESSION['user']
        ]);
    }
} else {
    $contentOfPage = include_template('auth.php');
    $contentLayout = include_template('layout.php', [
        'contentOfPage' => $contentOfPage,
        'pageName' => 'Дела в Порядке. Авторизация'
    ]);
}
print($contentLayout);

?>