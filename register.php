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

    $errors = [];
    $values = [];
    $form = $values;
    
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $form =  [
        'name'    => $_POST['name'] ?? null,
        'email'   => $_POST['email'] ?? null,
        'password'=> $_POST['password'] ?? null
    ];

    $req_fields = ['name','email','password'];
    
    //проверка заполненности обязательных полей

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    

    //проверка корректности и уникальности адреса

    elseif (!empty($form['email']) && filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id_user FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    } else {
        $errors['email'] = 'Введите корректный email';
    }

    }

    //запрос на добавление нового пользователя 

    if (!count($errors) && strlen($form['password']) > 6) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (name_user, email, password) VALUES (?, ?, ?)';
    
        db_insert_data($link, $sql, [$form['name'], $form['email'], $password]);
        
        header('Location: /index.php');
        exit();
    
    } else{
        $errors['password'] = 'Минимальная длина пароля 6 символов';
    }
} 

//подключение шаблона

$contentOfPage = include_template ('register.php', [ 
    'errors' => $errors,
    'values' => $values
   
]);
$contentLayout = include_template ('layout.php', [
    'contentOfPage' => $contentOfPage,
    'pageName' => 'Дела в Порядке. Регистрация'
]);
print($contentLayout);

?>